<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use App\Database\Connection;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}

