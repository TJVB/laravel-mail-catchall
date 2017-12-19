<?php

namespace TJVB\MailCatchall\Tests;

use TJVB\MailCatchall\MailCatcher;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Log;
use Faker\Factory;

/**
 * Test the MailCatcher
 *
 * @author Tobias van Beek <t.vanbeek@connexx.nl>
 *
 * @group mailcatcher
 */
class MailCatcherTest extends TestCase
{

    /**
     * Test that it will do nothing if catchmail isn't enabled
     *
     * @tests
     */
    public function it_will_do_nothing_if_catchmail_is_not_enabled()
    {
        $originalConfig = \config('mailcatchall.enabled');
        \config(['mailcatchall.enabled' => false]);

        $catcher = new MailCatcher();
        $eventMock = \Mockery::mock(MessageSending::class);
        $messageMock = \Mockery::mock(\Swift_Message::class);
        $messageMock->shouldNotReceive([
            'setTo',
            'getCc',
            'getBcc',
        ]);

        $eventMock->message = $messageMock;

        $catcher->catchmail($eventMock);

        \config(['mailcatchall.enabled' => $originalConfig]);
    }

    /**
     * Test that it will do nothing if catchmail isn't enabled
     *
     * @tests
     */
    public function it_will_log_an_error_if_catchmail_is_enabled_but_no_receiver_is_set()
    {
        $originalConfig = \config('mailcatchall.enabled');
        $originalReceiver = \config('mailcatchall.receiver');
        \config(['mailcatchall.enabled' => true]);
        \config(['mailcatchall.receiver' => null]);

        $catcher = new MailCatcher();
        $eventMock = \Mockery::mock(MessageSending::class);
        $messageMock = \Mockery::mock(\Swift_Message::class);
        $messageMock->shouldNotReceive([
            'setTo',
            'getCc',
            'getBcc',
        ]);

        Log::shouldReceive('error')->once();

        $eventMock->message = $messageMock;

        $catcher->catchmail($eventMock);

        \config(['mailcatchall.enabled' => $originalConfig]);
        \config(['mailcatchall.receiver' => $originalReceiver]);
    }

    /**
     * Test that the receiver will set as the to
     *
     * @tests
     */
    public function it_will_set_the_receiver_in_the_to()
    {
        $faker = Factory::create();
        $originalConfig = \config('mailcatchall.enabled');
        $originalReceiver = \config('mailcatchall.receiver');
        $receiver = $faker->email;
        \config(['mailcatchall.enabled' => true]);
        \config(['mailcatchall.receiver' => $receiver]);

        $catcher = new MailCatcher();
        $eventMock = \Mockery::mock(MessageSending::class);
        $messageMock = \Mockery::mock(\Swift_Message::class);
        $messageMock->shouldNotReceive([
            'setCc',
            'setBcc',
        ]);
        $messageMock->shouldReceive('setTo')->once()->with($receiver);
        $messageMock->shouldReceive('getCc');
        $messageMock->shouldReceive('getBcc');

        $eventMock->message = $messageMock;

        $catcher->catchmail($eventMock);

        \config(['mailcatchall.enabled' => $originalConfig]);
        \config(['mailcatchall.receiver' => $originalReceiver]);
    }

    /**
     * Test that the cc receivers will be removed
     *
     * @tests
     */
    public function it_will_remove_the_cc_receivers()
    {
        $faker = Factory::create();
        $originalConfig = \config('mailcatchall.enabled');
        $originalReceiver = \config('mailcatchall.receiver');
        $receiver = $faker->email;
        \config(['mailcatchall.enabled' => true]);
        \config(['mailcatchall.receiver' => $receiver]);

        $catcher = new MailCatcher();
        $eventMock = \Mockery::mock(MessageSending::class);
        $messageMock = \Mockery::mock(\Swift_Message::class);
        $messageMock->shouldNotReceive([
            'setBcc',
        ]);
        $messageMock->shouldReceive('setTo')->once()->with($receiver);
        $messageMock->shouldReceive('setCc')->once()->with([]);
        $messageMock->shouldReceive('getCc')->andReturn([$faker->email]);
        $messageMock->shouldReceive('getBcc');

        $eventMock->message = $messageMock;

        $catcher->catchmail($eventMock);

        \config(['mailcatchall.enabled' => $originalConfig]);
        \config(['mailcatchall.receiver' => $originalReceiver]);
    }

    /**
     * Test that the bcc receivers will be removed
     *
     * @tests
     */
    public function it_will_remove_the_bcc_receivers()
    {
        $faker = Factory::create();
        $originalConfig = \config('mailcatchall.enabled');
        $originalReceiver = \config('mailcatchall.receiver');
        $receiver = $faker->email;
        \config(['mailcatchall.enabled' => true]);
        \config(['mailcatchall.receiver' => $receiver]);

        $catcher = new MailCatcher();
        $eventMock = \Mockery::mock(MessageSending::class);
        $messageMock = \Mockery::mock(\Swift_Message::class);
        $messageMock->shouldNotReceive([
            'setCc',
        ]);
        $messageMock->shouldReceive('setTo')->once()->with($receiver);
        $messageMock->shouldReceive('getCc');
        $messageMock->shouldReceive('getBcc')->andReturn([$faker->email]);
        $messageMock->shouldReceive('setBcc')->once()->with([]);

        $eventMock->message = $messageMock;

        $catcher->catchmail($eventMock);

        \config(['mailcatchall.enabled' => $originalConfig]);
        \config(['mailcatchall.receiver' => $originalReceiver]);
    }
}