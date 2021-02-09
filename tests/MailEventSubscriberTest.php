<?php

namespace TJVB\MailCatchall\Tests;

use Illuminate\Contracts\Config\Repository;
use TJVB\MailCatchall\MailEventSubscriber;

/**
 * Test the Mail Event Subscriber test
 *
 * @author Tobias van Beek <t.vanbeek@tjvb.nl>
 *
 * @group subscriber
 */
class MailEventSubscriverTest extends TestCase
{
    /**
     * Test that it will subscrive to the event
     *
     * @test
     */
    public function itWillSubscribeToTheEvent(): void
    {
        $dispatcher = $this->app->get('events');
        $subscriber = new MailEventSubscriber($this->app->get(Repository::class));
        $this->assertFalse($dispatcher->hasListeners(\config('mailcatchall.event')));
        $subscriber->subscribe($dispatcher);
        $this->assertTrue($dispatcher->hasListeners(\config('mailcatchall.event')));
    }
}
