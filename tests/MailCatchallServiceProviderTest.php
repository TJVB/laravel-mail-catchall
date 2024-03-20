<?php

declare(strict_types=1);

namespace TJVB\MailCatchall\Tests;

use TJVB\MailCatchall\MailCatchallServiceProvider;

/**
 * Test the MailCatchallServiceProvider
 *
 * @author Tobias van Beek <t.vanbeek@tjvb.nl>
 *
 * @group providers
 */
final class MailCatchallServiceProviderTest extends TestCase
{
    /**
     * It will register the event listener if enabled
     *
     * @test
     */
    public function itWillRegisterTheEventListenerIfEnabled(): void
    {
        config(['mailcatchall.enabled' => true]);
        $provider = new MailCatchallServiceProvider($this->app);
        $dispatcher = $this->app['events'];
        $this->assertFalse($dispatcher->hasListeners(config('mailcatchall.event')));
        $provider->boot();
        $this->assertTrue($dispatcher->hasListeners(config('mailcatchall.event')));
    }

    /**
     * It will not register the event listener if disabled
     *
     * @test
     */
    public function itWillNotRegisterTheEventListenerIfDisabled(): void
    {
        config(['mailcatchall.enabled' => false]);
        $provider = new MailCatchallServiceProvider($this->app);
        $dispatcher = $this->app['events'];
        $this->assertFalse($dispatcher->hasListeners(config('mailcatchall.event')));
        $provider->boot();
        $this->assertFalse($dispatcher->hasListeners(config('mailcatchall.event')));
    }
}
