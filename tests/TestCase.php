<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Seed once per run, not once per test (docs/HANDOVER.md §10). RefreshDatabase runs migrate:fresh once per
     * process; with this it adds --seed, the seed is committed, and every test still runs in a transaction that is
     * rolled back, so each test starts from the same seeded database. Set it here only, never in a test class: the
     * first class to run would decide for the whole process. Tests don't call $this->seed().
     */
    protected $seed = true;
}
