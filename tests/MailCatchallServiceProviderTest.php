<?php

declare(strict_types=1);

namespace TJVB\MailCatchall\Tests;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Mail\Events\MessageSending;
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
        $eventName = MessageSending::class;
        $provider = new MailCatchallServiceProvider($this->app);
        /** @var Dispatcher $dispatcher */
        $dispatcher = $this->app['events'];
        $this->assertFalse($dispatcher->hasListeners($eventName));
        $provider->boot();
        $this->assertTrue($dispatcher->hasListeners($eventName));
    }

    /**
     * It will not register the event listener if disabled
     *
     * @test
     */
    public function itWillNotRegisterTheEventListenerIfDisabled(): void
    {
        config(['mailcatchall.enabled' => false]);
        $eventName = MessageSending::class;
        $provider = new MailCatchallServiceProvider($this->app);
        /** @var Dispatcher $dispatcher */
        $dispatcher = $this->app['events'];
        $this->assertFalse($dispatcher->hasListeners($eventName));
        $provider->boot();
        $this->assertFalse($dispatcher->hasListeners($eventName));
    }
}
