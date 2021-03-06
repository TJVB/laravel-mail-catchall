<?php

namespace TJVB\MailCatchall\Tests;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Mockery;
use Psr\Log\LoggerInterface;
use TJVB\MailCatchall\MailCatcher;
use Illuminate\Mail\Events\MessageSending;
use Faker\Factory;

/**
 * Test the MailCatcher
 *
 * @author Tobias van Beek <t.vanbeek@tjvb.nl>
 *
 * @group mailcatcher
 */
class MailCatcherTest extends TestCase
{
    /**
     *
     * {@inheritDoc}
     * @see \Orchestra\Testbench\TestCase::setUp()
     */
    protected function setUp(): void
    {
        parent::setUp();
        // we need it for almost every test
        \config(['mailcatchall.enabled' => true]);
        // we don't need this for the most of the test so we disable it by default
        \config(['mailcatchall.add_receivers_to_html' => false]);
        \config(['mailcatchall.add_receivers_to_text' => false]);
    }

    /**
     * Test that it will do nothing if catchmail isn't enabled
     *
     * @test
     */
    public function itWillDoNothingIfCatchmailIsNotEnabled(): void
    {
        $originalConfig = \config('mailcatchall.enabled');
        \config(['mailcatchall.enabled' => false]);

        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $eventMock = Mockery::mock(MessageSending::class);
        $messageMock = Mockery::mock(\Swift_Message::class);
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
     * @test
     */
    public function itWillLogAnErrorIfCatchmailIsEnabledButNoReceiverIsSet(): void
    {
        $originalConfig = \config('mailcatchall.enabled');
        $originalReceiver = \config('mailcatchall.receiver');
        \config(['mailcatchall.receiver' => null]);

        $loggerMock = $this->getLoggerMock();
        $loggerMock->shouldReceive('error')->once();
        $catcher = new MailCatcher(
            $loggerMock,
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $eventMock = Mockery::mock(MessageSending::class);
        $messageMock = Mockery::mock(\Swift_Message::class);
        $messageMock->shouldNotReceive([
            'setTo',
            'getCc',
            'getBcc',
        ]);

        $eventMock->message = $messageMock;

        $catcher->catchmail($eventMock);

        \config(['mailcatchall.enabled' => $originalConfig]);
        \config(['mailcatchall.receiver' => $originalReceiver]);
    }

    /**
     * Test that the receiver will set as the to
     *
     * @test
     */
    public function itWillSetTheReceiverInTheTo(): void
    {
        $faker = Factory::create();
        $originalConfig = \config('mailcatchall.enabled');
        $originalReceiver = \config('mailcatchall.receiver');
        $receiver = $faker->email;
        \config(['mailcatchall.receiver' => $receiver]);

        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $eventMock = Mockery::mock(MessageSending::class);
        $messageMock = Mockery::mock(\Swift_Message::class);
        $messageMock->shouldNotReceive([
            'setCc',
            'setBcc',
        ]);
        $messageMock->shouldReceive('setTo')->once()->with($receiver);
        $messageMock->shouldReceive('getTo');
        $messageMock->shouldReceive('getCc');
        $messageMock->shouldReceive('getBcc');
        $messageMock->shouldReceive('getContentType');

        $eventMock->message = $messageMock;

        $catcher->catchmail($eventMock);

        \config(['mailcatchall.enabled' => $originalConfig]);
        \config(['mailcatchall.receiver' => $originalReceiver]);
    }

    /**
     * Test that the cc receivers will be removed
     *
     * @test
     */
    public function itWillRemoveTheCcReceivers(): void
    {
        $faker = Factory::create();
        $originalConfig = \config('mailcatchall.enabled');
        $originalReceiver = \config('mailcatchall.receiver');
        $receiver = $faker->email;
        \config(['mailcatchall.receiver' => $receiver]);

        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $eventMock = Mockery::mock(MessageSending::class);
        $messageMock = Mockery::mock(\Swift_Message::class);
        $messageMock->shouldNotReceive([
            'setBcc',
        ]);
        $messageMock->shouldReceive('getTo');
        $messageMock->shouldReceive('setTo')->once()->with($receiver);
        $messageMock->shouldReceive('setCc')->once()->with([]);
        $messageMock->shouldReceive('getCc')->andReturn([$faker->email]);
        $messageMock->shouldReceive('getBcc');
        $messageMock->shouldReceive('getContentType');

        $eventMock->message = $messageMock;

        $catcher->catchmail($eventMock);

        \config(['mailcatchall.enabled' => $originalConfig]);
        \config(['mailcatchall.receiver' => $originalReceiver]);
    }

    /**
     * Test that the bcc receivers will be removed
     *
     * @test
     */
    public function itWillRemoveTheBccReceivers(): void
    {
        $faker = Factory::create();
        $originalConfig = \config('mailcatchall.enabled');
        $originalReceiver = \config('mailcatchall.receiver');
        $receiver = $faker->email;
        \config(['mailcatchall.receiver' => $receiver]);

        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $eventMock = Mockery::mock(MessageSending::class);
        $messageMock = Mockery::mock(\Swift_Message::class);
        $messageMock->shouldNotReceive([
            'setCc',
        ]);
        $messageMock->shouldReceive('getTo');
        $messageMock->shouldReceive('setTo')->once()->with($receiver);
        $messageMock->shouldReceive('getCc');
        $messageMock->shouldReceive('getBcc')->andReturn([$faker->email]);
        $messageMock->shouldReceive('setBcc')->once()->with([]);
        $messageMock->shouldReceive('getContentType');

        $eventMock->message = $messageMock;

        $catcher->catchmail($eventMock);

        \config(['mailcatchall.enabled' => $originalConfig]);
        \config(['mailcatchall.receiver' => $originalReceiver]);
    }

    /**
     * Test that the to is set in the text view
     *
     * @test
     */
    public function itWillAddOriginalToInTextView(): void
    {
        $faker = Factory::create();
        $originalConfig = \config('mailcatchall.enabled');
        $originalReceiver = \config('mailcatchall.receiver');
        $receiver = $faker->email;
        $originalTo = $faker->email;
        \config(['mailcatchall.receiver' => $receiver]);
        \config(['mailcatchall.add_receivers_to_text' => true]);

        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $eventMock = Mockery::mock(MessageSending::class);

        $message = new \Swift_Message();
        $message->setBody($faker->text, 'text');
        $message->setTo($originalTo);

        $eventMock->message = $message;

        $catcher->catchmail($eventMock);

        $this->assertStringContainsStringIgnoringCase($originalTo, $message->getBody());

        \config(['mailcatchall.enabled' => $originalConfig]);
        \config(['mailcatchall.receiver' => $originalReceiver]);
    }

    /**
     * Test that the to is set in the html view
     *
     * @test
     */
    public function itWillAddOriginalToInHtmlView(): void
    {
        $faker = Factory::create();
        $originalConfig = \config('mailcatchall.enabled');
        $originalReceiver = \config('mailcatchall.receiver');
        $receiver = $faker->email;
        $originalTo = $faker->email;
        \config(['mailcatchall.receiver' => $receiver]);
        \config(['mailcatchall.add_receivers_to_html' => true]);

        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $eventMock = Mockery::mock(MessageSending::class);

        $message = new \Swift_Message();
        $message->setBody($faker->text, 'html');
        $message->setTo($originalTo);

        $eventMock->message = $message;

        $catcher->catchmail($eventMock);

        $this->assertStringContainsStringIgnoringCase($originalTo, $message->getBody());

        \config(['mailcatchall.enabled' => $originalConfig]);
        \config(['mailcatchall.receiver' => $originalReceiver]);
    }

    /**
     * Test that the to is not set in the html view if disabled
     *
     * @test
     */
    public function itWillNotAddOriginalToInHtmlViewIfDisabled(): void
    {
        $faker = Factory::create();
        $originalConfig = \config('mailcatchall.enabled');
        $originalReceiver = \config('mailcatchall.receiver');
        $receiver = $faker->email;
        $originalTo = $faker->email;
        \config(['mailcatchall.receiver' => $receiver]);

        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $eventMock = Mockery::mock(MessageSending::class);

        $message = new \Swift_Message();
        $message->setBody($faker->text, 'html');
        $message->setTo($originalTo);

        $eventMock->message = $message;

        $catcher->catchmail($eventMock);

        $this->assertStringNotContainsStringIgnoringCase($originalTo, $message->getBody());

        \config(['mailcatchall.enabled' => $originalConfig]);
        \config(['mailcatchall.receiver' => $originalReceiver]);
    }

    /**
     * Test that the cc is set in the text view
     *
     * @test
     */
    public function itWillAddOriginalCcInTextView(): void
    {
        $faker = Factory::create();
        $originalConfig = \config('mailcatchall.enabled');
        $originalReceiver = \config('mailcatchall.receiver');
        $receiver = $faker->email;
        $originalCC = $faker->email;
        \config(['mailcatchall.receiver' => $receiver]);
        \config(['mailcatchall.add_receivers_to_text' => true]);

        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $eventMock = Mockery::mock(MessageSending::class);

        $message = new \Swift_Message();
        $message->setBody($faker->text, 'text');
        $message->setCc($originalCC);

        $eventMock->message = $message;

        $catcher->catchmail($eventMock);

        $this->assertStringContainsStringIgnoringCase($originalCC, $message->getBody());

        \config(['mailcatchall.enabled' => $originalConfig]);
        \config(['mailcatchall.receiver' => $originalReceiver]);
    }

    /**
     * Test that the cc is set in the html view
     *
     * @test
     */
    public function itWillAddOriginalCcInHtmlView(): void
    {
        $faker = Factory::create();
        $originalConfig = \config('mailcatchall.enabled');
        $originalReceiver = \config('mailcatchall.receiver');
        $receiver = $faker->email;
        $originalCC = $faker->email;
        \config(['mailcatchall.receiver' => $receiver]);
        \config(['mailcatchall.add_receivers_to_html' => true]);

        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $eventMock = Mockery::mock(MessageSending::class);

        $message = new \Swift_Message();
        $message->setBody($faker->text, 'html');
        $message->setCc($originalCC);

        $eventMock->message = $message;

        $catcher->catchmail($eventMock);

        $this->assertStringContainsStringIgnoringCase($originalCC, $message->getBody());

        \config(['mailcatchall.enabled' => $originalConfig]);
        \config(['mailcatchall.receiver' => $originalReceiver]);
    }

    /**
     * Test that the bcc is set in the text view
     *
     * @test
     */
    public function itWillAddOriginalBccInTextView(): void
    {
        $faker = Factory::create();
        $originalConfig = \config('mailcatchall.enabled');
        $originalReceiver = \config('mailcatchall.receiver');
        $receiver = $faker->email;
        $originalBcc = $faker->email;
        \config(['mailcatchall.receiver' => $receiver]);
        \config(['mailcatchall.add_receivers_to_text' => true]);

        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $eventMock = Mockery::mock(MessageSending::class);

        $message = new \Swift_Message();
        $message->setBody($faker->text, 'text');
        $message->setBcc($originalBcc);

        $eventMock->message = $message;

        $catcher->catchmail($eventMock);

        $this->assertStringContainsStringIgnoringCase($originalBcc, $message->getBody());

        \config(['mailcatchall.enabled' => $originalConfig]);
        \config(['mailcatchall.receiver' => $originalReceiver]);
    }

    /**
     * Test that the bcc is set in the html view
     *
     * @test
     */
    public function itWillAddOriginalBccInHtmlView(): void
    {
        $faker = Factory::create();
        $originalConfig = \config('mailcatchall.enabled');
        $originalReceiver = \config('mailcatchall.receiver');
        $receiver = $faker->email;
        $originalBcc = $faker->email;
        \config(['mailcatchall.receiver' => $receiver]);
        \config(['mailcatchall.add_receivers_to_html' => true]);

        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $eventMock = Mockery::mock(MessageSending::class);

        $message = new \Swift_Message();
        $message->setBody($faker->text, 'html');
        $message->setBcc($originalBcc);

        $eventMock->message = $message;

        $catcher->catchmail($eventMock);

        $this->assertStringContainsStringIgnoringCase($originalBcc, $message->getBody());

        \config(['mailcatchall.enabled' => $originalConfig]);
        \config(['mailcatchall.receiver' => $originalReceiver]);
    }

    private function getLoggerMock()
    {
        return Mockery::mock(LoggerInterface::class);
    }

    private function getViewFactory(): ViewFactory
    {
        return $this->app->make(ViewFactory::class);
    }

    private function getConfigRepository(): Repository
    {
        return $this->app->make(Repository::class);
    }
}
