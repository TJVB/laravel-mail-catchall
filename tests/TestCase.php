<?php

namespace TJVB\MailCatchall\Tests;

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
     * @param \Illuminate\Foundation\Application $app
     *
     * @return string[]
     */
    protected function getPackageProviders($app)
    {
        return [
            'TJVB\MailCatchall\MailCatchallServiceProvider'
        ];
    }
}
