<?php

namespace TJVB\MailCatchall\Tests;

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
    public function it_will_subscribe_to_the_event()
    {
        $dispatcher = $this->app['events'];
        $subscriber = new MailEventSubscriber();
        $this->assertFalse($dispatcher->hasListeners(\config('mailcatchall.event')));
        $subscriber->subscribe($dispatcher);
        $this->assertTrue($dispatcher->hasListeners(\config('mailcatchall.event')));
    }
}
