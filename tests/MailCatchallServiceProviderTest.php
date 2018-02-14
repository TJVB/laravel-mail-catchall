<?php

namespace TJVB\MailCatchall\Tests;

use TJVB\MailCatchall\MailCatchallServiceProvider;

/**
 * Test the MailCatchallServiceProvider
 *
 * @author Tobias van Beek <t.vanbeek@tjvb.nl>
 *
 * @group providers
 */
class MailCatchallServiceProviderTest extends TestCase
{
    /**
     * It will register the event listener if enabled
     *
     * @test
     */
    public function it_will_register_the_event_listener_if_enabled()
    {
        \config(['mailcatchall.enabled' => true]);
        $provider = new MailCatchallServiceProvider($this->app);
        $dispatcher = $this->app['events'];
        $this->assertFalse($dispatcher->hasListeners(\config('mailcatchall.event')));
        $provider->boot();
        $this->assertTrue($dispatcher->hasListeners(\config('mailcatchall.event')));
    }

    /**
     * It will not register the event listener if disabled
     *
     * @test
     */
    public function it_will_not_register_the_event_listener_if_disabled()
    {
        \config(['mailcatchall.enabled' => false]);
        $provider = new MailCatchallServiceProvider($this->app);
        $dispatcher = $this->app['events'];
        $this->assertFalse($dispatcher->hasListeners(\config('mailcatchall.event')));
        $provider->boot();
        $this->assertFalse($dispatcher->hasListeners(\config('mailcatchall.event')));
    }
}