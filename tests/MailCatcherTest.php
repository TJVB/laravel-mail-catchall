<?php

declare(strict_types=1);

namespace TJVB\MailCatchall\Tests;

use Faker\Factory;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Mail\Events\MessageSending;
use Mockery;
use Mockery\MockInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use TJVB\MailCatchall\MailCatcher;

/**
 * Test the MailCatcher
 *
 * @author Tobias van Beek <t.vanbeek@tjvb.nl>
 *
 * @group mailcatcher
 */
final class MailCatcherTest extends TestCase
{
    /**
     * Test that it will do nothing if catchmail isn't enabled
     *
     * @test
     */
    public function itWillDoNothingIfCatchmailIsNotEnabled(): void
    {
        $faker = Factory::create();
        $originalConfig = config('mailcatchall.enabled');
        config(['mailcatchall.enabled' => false]);

        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $originalTo = $faker->email();

        $message = new Email();
        $message->to($originalTo);
        $message->text($faker->text());
        $message->addCc($faker->email());
        $message->addBcc($faker->email());

        $event = new MessageSending($message);

        $catcher->catchmail($event);

        $this->assertNotEmpty($message->getCc());
        $this->assertNotEmpty($message->getBcc());
        $this->assertTo($message, $originalTo);

        config(['mailcatchall.enabled' => $originalConfig]);
    }

    /**
     * Test that it will do nothing if catchmail isn't enabled
     *
     * @test
     */
    public function itWillLogAnErrorIfCatchmailIsEnabledButNoReceiverIsSet(): void
    {
        $faker = Factory::create();
        $originalConfig = config('mailcatchall.enabled');
        $originalReceiver = config('mailcatchall.receiver');
        config(['mailcatchall.receiver' => null]);

        $loggerMock = $this->getLoggerMock();
        $loggerMock->shouldReceive('error')->once();
        $catcher = new MailCatcher(
            $loggerMock,
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $originalTo = $faker->email();

        $message = new Email();
        $message->to($originalTo);
        $message->text($faker->text());
        $message->addCc($faker->email());
        $message->addBcc($faker->email());

        $event = new MessageSending($message);

        $catcher->catchmail($event);

        $this->assertNotEmpty($message->getCc());
        $this->assertNotEmpty($message->getBcc());
        $this->assertTo($message, $originalTo);

        config(['mailcatchall.enabled' => $originalConfig]);
        config(['mailcatchall.receiver' => $originalReceiver]);
    }

    /**
     * Test that the receiver will set as the to
     *
     * @test
     */
    public function itWillSetTheReceiverInTheTo(): void
    {
        $faker = Factory::create();
        $originalConfig = config('mailcatchall.enabled');
        $originalReceiver = config('mailcatchall.receiver');
        $receiver = $faker->email;
        config(['mailcatchall.receiver' => $receiver]);

        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );

        $message = new Email();
        $message->text($faker->text);

        $event = new MessageSending($message);

        $catcher->catchmail($event);

        $this->assertTo($message, $receiver);

        config(['mailcatchall.enabled' => $originalConfig]);
        config(['mailcatchall.receiver' => $originalReceiver]);
    }

    /**
     * Test that the cc receivers will be removed
     *
     * @test
     */
    public function itWillRemoveTheCcReceivers(): void
    {
        $faker = Factory::create();
        $originalConfig = config('mailcatchall.enabled');
        $originalReceiver = config('mailcatchall.receiver');
        $receiver = $faker->email;
        config(['mailcatchall.receiver' => $receiver]);

        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );

        $message = new Email();
        $message->cc($faker->email);

        $event = new MessageSending($message);

        $catcher->catchmail($event);
        $this->assertEmpty($message->getBcc());
        $this->assertEmpty($message->getCc());
        $this->assertTo($message, $receiver);

        config(['mailcatchall.enabled' => $originalConfig]);
        config(['mailcatchall.receiver' => $originalReceiver]);
    }

    /**
     * Test that the bcc receivers will be removed
     *
     * @test
     */
    public function itWillRemoveTheBccReceivers(): void
    {
        $faker = Factory::create();
        $originalConfig = config('mailcatchall.enabled');
        $originalReceiver = config('mailcatchall.receiver');
        $receiver = $faker->email;
        config(['mailcatchall.receiver' => $receiver]);

        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );

        $message = new Email();
        $message->text($faker->text);
        $message->addBcc($faker->email);

        $event = new MessageSending($message);

        $catcher->catchmail($event);

        $this->assertEmpty($message->getBcc());
        $this->assertTo($message, $receiver);

        config(['mailcatchall.enabled' => $originalConfig]);
        config(['mailcatchall.receiver' => $originalReceiver]);
    }

    /**
     * Test that the to is set in the text view
     *
     * @test
     */
    public function itWillAddOriginalToInTextView(): void
    {
        $faker = Factory::create();
        $originalConfig = config('mailcatchall.enabled');
        $originalReceiver = config('mailcatchall.receiver');
        $receiver = $faker->email;
        $originalTo = $faker->email;
        config(['mailcatchall.receiver' => $receiver]);
        config(['mailcatchall.add_receivers_to_text' => true]);

        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );

        $message = new Email();
        $message->text($faker->text);
        $message->to($originalTo);

        $event = new MessageSending($message);

        $catcher->catchmail($event);

        $this->assertStringContainsStringIgnoringCase($originalTo, $message->getTextBody());

        config(['mailcatchall.enabled' => $originalConfig]);
        config(['mailcatchall.receiver' => $originalReceiver]);
    }

    /**
     * Test that the to is set in the html view
     *
     * @test
     */
    public function itWillAddOriginalToInHtmlView(): void
    {
        $faker = Factory::create();
        $originalConfig = config('mailcatchall.enabled');
        $originalReceiver = config('mailcatchall.receiver');
        $receiver = $faker->email;
        $originalTo = $faker->email;
        config(['mailcatchall.receiver' => $receiver]);
        config(['mailcatchall.add_receivers_to_html' => true]);

        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );

        $message = new Email();
        $message->html($faker->text);
        $message->to($originalTo);

        $event = new MessageSending($message);

        $catcher->catchmail($event);

        $this->assertStringContainsStringIgnoringCase($originalTo, $message->getHtmlBody());

        config(['mailcatchall.enabled' => $originalConfig]);
        config(['mailcatchall.receiver' => $originalReceiver]);
    }

    /**
     * Test that the to is not set in the html view if disabled
     *
     * @test
     */
    public function itWillNotAddOriginalToInHtmlViewIfDisabled(): void
    {
        $faker = Factory::create();
        $originalConfig = config('mailcatchall.enabled');
        $originalReceiver = config('mailcatchall.receiver');
        $receiver = $faker->email;
        $originalTo = $faker->email;
        config(['mailcatchall.receiver' => $receiver]);

        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );

        $message = new Email();
        $message->html($faker->text);
        $message->to($originalTo);

        $event = new MessageSending($message);

        $catcher->catchmail($event);

        $this->assertStringNotContainsStringIgnoringCase($originalTo, $message->getHtmlBody());

        config(['mailcatchall.enabled' => $originalConfig]);
        config(['mailcatchall.receiver' => $originalReceiver]);
    }

    /**
     * Test that the cc is set in the text view
     *
     * @test
     */
    public function itWillAddOriginalCcInTextView(): void
    {
        $faker = Factory::create();
        $originalConfig = config('mailcatchall.enabled');
        $originalReceiver = config('mailcatchall.receiver');
        $receiver = $faker->email;
        $originalCC = $faker->email;
        config(['mailcatchall.receiver' => $receiver]);
        config(['mailcatchall.add_receivers_to_text' => true]);

        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );

        $message = new Email();
        $message->text($faker->text);
        $message->cc($originalCC);

        $event = new MessageSending($message);

        $catcher->catchmail($event);

        $this->assertStringContainsStringIgnoringCase($originalCC, $message->getTextBody());

        config(['mailcatchall.enabled' => $originalConfig]);
        config(['mailcatchall.receiver' => $originalReceiver]);
    }

    /**
     * Test that the cc is set in the html view
     *
     * @test
     */
    public function itWillAddOriginalCcInHtmlView(): void
    {
        $faker = Factory::create();
        $originalConfig = config('mailcatchall.enabled');
        $originalReceiver = config('mailcatchall.receiver');
        $receiver = $faker->email;
        $originalCC = $faker->email;
        config(['mailcatchall.receiver' => $receiver]);
        config(['mailcatchall.add_receivers_to_html' => true]);

        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );

        $message = new Email();
        $message->html($faker->text);
        $message->cc($originalCC);

        $event = new MessageSending($message);

        $catcher->catchmail($event);

        $this->assertStringContainsStringIgnoringCase($originalCC, $message->getHtmlBody());

        config(['mailcatchall.enabled' => $originalConfig]);
        config(['mailcatchall.receiver' => $originalReceiver]);
    }

    /**
     * Test that the bcc is set in the text view
     *
     * @test
     */
    public function itWillAddOriginalBccInTextView(): void
    {
        $faker = Factory::create();
        $originalConfig = config('mailcatchall.enabled');
        $originalReceiver = config('mailcatchall.receiver');
        $receiver = $faker->email;
        $originalBcc = $faker->email;
        config(['mailcatchall.receiver' => $receiver]);
        config(['mailcatchall.add_receivers_to_text' => true]);

        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );

        $message = new Email();
        $message->text($faker->text);
        $message->bcc($originalBcc);

        $event = new MessageSending($message);

        $catcher->catchmail($event);

        $this->assertStringContainsStringIgnoringCase($originalBcc, $message->getTextBody());

        config(['mailcatchall.enabled' => $originalConfig]);
        config(['mailcatchall.receiver' => $originalReceiver]);
    }

    /**
     * Test that the bcc is set in the html view
     *
     * @test
     */
    public function itWillAddOriginalBccInHtmlView(): void
    {
        $faker = Factory::create();
        $originalConfig = config('mailcatchall.enabled');
        $originalReceiver = config('mailcatchall.receiver');
        $receiver = $faker->email;
        $originalBcc = $faker->email;
        config(['mailcatchall.receiver' => $receiver]);
        config(['mailcatchall.add_receivers_to_html' => true]);

        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );

        /** @var MockInterface&MessageSending $eventMock */
        $eventMock = Mockery::mock(MessageSending::class);

        $message = new Email();
        $message->html($faker->text);
        $message->bcc($originalBcc);

        $eventMock->message = $message;

        $catcher->catchmail($eventMock);

        $this->assertStringContainsStringIgnoringCase($originalBcc, $message->getHtmlBody());

        config(['mailcatchall.enabled' => $originalConfig]);
        config(['mailcatchall.receiver' => $originalReceiver]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Orchestra\Testbench\TestCase::setUp()
     */
    protected function setUp(): void
    {
        parent::setUp();
        // we need it for almost every test
        config(['mailcatchall.enabled' => true]);
        // we don't need this for the most of the test so we disable it by default
        config(['mailcatchall.add_receivers_to_html' => false]);
        config(['mailcatchall.add_receivers_to_text' => false]);
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

    private function assertTo(Email $message, string $receiver): void
    {
        $to = $message->getTo();
        $this->assertArrayHasKey(0, $to);
        $this->assertInstanceOf(Address::class, $to[0]);
        $this->assertEquals($receiver, $to[0]->getAddress());
    }
}
