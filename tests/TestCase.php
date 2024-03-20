<?php

declare(strict_types=1);

namespace TJVB\MailCatchall\Tests;

use Illuminate\Foundation\Application;

/**
 * The base TestCase for all the tests we have
 *
 * @author Tobias van Beek <t.vanbeek@tjvb.nl>
 */
abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Get the custom Service Provider
     *
     * @param Application $app
     *
     * @return string[]
     */
    protected function getPackageProviders($app): array
    {
        return [
            'TJVB\MailCatchall\MailCatchallServiceProvider'
        ];
    }
}
