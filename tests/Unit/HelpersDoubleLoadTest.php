<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

/**
 * app/helpers.php is loaded by Composer's autoload "files", but it sits in the PSR-4 root (App\ => app/), so asking the
 * autoloader for a class named App\helpers includes it a second time. A VS Code extension (Laravel Extra Intellisense)
 * does exactly that every minute, which filled laravel.log with "Cannot redeclare is_active_route()"
 * (docs/HANDOVER.md §12). A second include must now be harmless.
 *
 * Runs in a separate PHP process: a redeclared function is a fatal error that would end PHPUnit itself.
 */
class HelpersDoubleLoadTest extends TestCase
{
    public function test_including_helpers_a_second_time_is_harmless(): void
    {
        $base = dirname(__DIR__, 2);

        // Warnings become exceptions, as under Laravel's error handler (a second define() of the constant is a warning).
        $code = <<<'PHP'
            set_error_handler(function ($severity, $message, $file, $line) {
                throw new ErrorException($message, 0, $severity, $file, $line);
            });
            require 'vendor/autoload.php';
            echo class_exists('App\helpers') ? 'class' : 'no-class', PHP_EOL;
            echo function_exists('is_active_route') && CURRENCY_POSITION === 'post' ? 'helpers-ok' : 'helpers-broken', PHP_EOL;
            PHP;

        $process = new Process([PHP_BINARY, '-d', 'xdebug.mode=off', '-r', $code], $base);
        $process->run();

        $this->assertSame(0, $process->getExitCode(), $process->getOutput() . $process->getErrorOutput());
        $this->assertSame("no-class\nhelpers-ok\n", str_replace("\r\n", "\n", $process->getOutput()));
    }
}
