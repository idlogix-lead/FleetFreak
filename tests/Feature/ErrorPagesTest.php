<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Mockery;
use RuntimeException;
use Tests\TestCase;

/**
 * Production error pages (APP_DEBUG=false): a styled, self-contained page with a plain
 * message, a link to the dashboard and a reference ID that matches a log entry. No stack
 * traces, file paths or exception messages, in HTML or in AJAX JSON. With debug on, local
 * development keeps Laravel's detailed error output.
 */
class ErrorPagesTest extends TestCase
{
    use RefreshDatabase;

    private const SECRET = 'secret detail in /var/www/app/Secret.php';

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('web')->group(function () {
            Route::get('/__test/boom', fn () => throw new RuntimeException(self::SECRET));
            Route::get('/__test/forbidden', fn () => abort(403, self::SECRET));
            Route::get('/__test/expired', fn () => throw new TokenMismatchException(self::SECRET));
            Route::get('/__test/maintenance', fn () => abort(503, self::SECRET));
        });

        config(['app.debug' => false]);
    }

    /** @return array<string, array{string, int, string, string}> */
    public static function pages(): array
    {
        return [
            '500' => ['/__test/boom', 500, 'Something went wrong', 'error'],
            '403' => ['/__test/forbidden', 403, 'have access to this page', 'warning'],
            '419' => ['/__test/expired', 419, 'Your session expired', 'debug'],
            '404' => ['/__test/no-such-page', 404, 'find that page', 'debug'],
            '503' => ['/__test/maintenance', 503, 'down for maintenance', 'debug'],
        ];
    }

    /** @dataProvider pages */
    public function test_error_page_is_styled_safe_and_traceable(string $uri, int $status, string $title, string $logLevel): void
    {
        $logged = [];
        Log::spy();
        Log::shouldReceive($logLevel, 'log')->andReturnUsing(function (...$args) use (&$logged) {
            $logged[] = $args;
        });

        $response = $this->get($uri);

        $response->assertStatus($status);
        $html = $response->getContent();
        $this->assertStringContainsString($title, html_entity_decode($html));
        $this->assertStringContainsString('Back to dashboard', $html);

        // Nothing leaks: no exception message, path, class or trace.
        foreach (['secret detail', 'Secret.php', '/var/www', 'RuntimeException', 'TokenMismatch', 'Stack trace', 'vendor/'] as $leak) {
            $this->assertStringNotContainsString($leak, $html, "leaked: {$leak}");
        }

        // Self-contained: no external stylesheet, script or font.
        $this->assertDoesNotMatchRegularExpression('#<link[^>]+stylesheet|<script[^>]+src=|fonts\.googleapis|cdn\.|unpkg\.|code\.jquery#i', $html);

        // The reference ID on the page is the one in the log entry.
        $this->assertMatchesRegularExpression('#<code id="error-ref">(FF-[0-9A-Z]{10})</code>#', $html);
        preg_match('#<code id="error-ref">(FF-[0-9A-Z]{10})</code>#', $html, $m);
        $refs = array_map(fn ($args) => end($args)['error_ref'] ?? null, $logged);
        $this->assertContains($m[1], $refs, 'the page reference matches a log entry');
    }

    public function test_full_error_details_still_go_to_the_log(): void
    {
        $context = [];
        Log::spy();
        Log::shouldReceive('error')->andReturnUsing(function ($message, $ctx) use (&$context) {
            $context = ['message' => $message] + $ctx;
        });

        $this->get('/__test/boom')->assertStatus(500);

        $this->assertSame(self::SECRET, $context['message']);
        $this->assertInstanceOf(RuntimeException::class, $context['exception']); // the full exception, trace included
        $this->assertMatchesRegularExpression('/^FF-[0-9A-Z]{10}$/', $context['error_ref']);
        $this->assertStringContainsString('/__test/boom', $context['url']);
    }

    public function test_ajax_errors_return_a_plain_message_and_a_reference_only(): void
    {
        $response = $this->getJson('/__test/boom');
        $response->assertStatus(500);
        $this->assertSame(['message', 'ref'], array_keys($response->json()));
        $this->assertSame('Something went wrong on our side. Please try again.', $response->json('message'));
        $this->assertMatchesRegularExpression('/^FF-[0-9A-Z]{10}$/', $response->json('ref'));

        $forbidden = $this->getJson('/__test/forbidden');
        $forbidden->assertStatus(403);
        $this->assertSame("You don't have permission to do that.", $forbidden->json('message'));
        $this->assertStringNotContainsString('secret', strtolower($forbidden->getContent()));

        $this->getJson('/__test/expired')->assertStatus(419)->assertJsonPath('message', 'Your session expired. Reload the page and try again.');
    }

    public function test_local_debug_mode_keeps_detailed_errors(): void
    {
        config(['app.debug' => true]);

        $response = $this->get('/__test/boom');

        $response->assertStatus(500);
        $this->assertStringContainsString('secret detail', $response->getContent());
        // Not our error page (Ignition quotes this test file, so match the page's own markup).
        $this->assertStringNotContainsString('<main class="card">', $response->getContent());
    }

    public function test_unauthorized_page_matches_the_error_pages(): void
    {
        $this->seed();
        $admin = User::where('email', 'admin@idl.pk')->first();

        $html = $this->actingAs($admin)->get('/unauthorized')->assertOk()->getContent();

        $this->assertStringContainsString('have access to this page', html_entity_decode($html));
        $this->assertStringContainsString('Back to dashboard', $html);
        $this->assertDoesNotMatchRegularExpression('#cdn\.jsdelivr|<link[^>]+stylesheet|<script[^>]+src=#i', $html);
        $this->assertStringNotContainsString('error-ref', $html, 'a redirect target, not a logged error: no reference ID');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
