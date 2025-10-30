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
        // setup / mock
        $faker = Factory::create();
        config(['mailcatchall.enabled' => false]);

        $originalTo = $faker->email();

        $message = new Email();
        $message->to($originalTo);
        $message->text($faker->text());
        $message->addCc($faker->email());
        $message->addBcc($faker->email());

        $event = new MessageSending($message);

        // run
        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $catcher->catchmail($event);

        // verify/assert
        $this->assertNotEmpty($message->getCc());
        $this->assertNotEmpty($message->getBcc());
        $this->assertTo($message, $originalTo);
    }

    /**
     * Test that it will do nothing if catchmail isn't enabled
     *
     * @test
     */
    public function itWillLogAnErrorIfCatchmailIsEnabledButNoReceiverIsSet(): void
    {
        // setup / mock
        $faker = Factory::create();
        config(['mailcatchall.receiver' => null]);

        $loggerMock = $this->getLoggerMock();
        $loggerMock->shouldReceive('error')->once();
        $originalTo = $faker->email();

        $message = new Email();
        $message->to($originalTo);
        $message->text($faker->text());
        $message->addCc($faker->email());
        $message->addBcc($faker->email());

        $event = new MessageSending($message);

        // run
        $catcher = new MailCatcher(
            $loggerMock,
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $catcher->catchmail($event);

        // verify/assert
        $this->assertNotEmpty($message->getCc());
        $this->assertNotEmpty($message->getBcc());
        $this->assertTo($message, $originalTo);
    }

    /**
     * Test that it will do nothing if catchmail isn't enabled
     *
     * @test
     */
    public function itWillLogAnErrorIfCatchmailIsEnabledButNoReceiverIsInvalid(): void
    {
        // setup / mock
        $faker = Factory::create();
        config(['mailcatchall.receiver' => [123]]);

        $loggerMock = $this->getLoggerMock();
        $loggerMock->shouldReceive('error')->once();
        $originalTo = $faker->email();

        $message = new Email();
        $message->to($originalTo);
        $message->text($faker->text());
        $message->addCc($faker->email());
        $message->addBcc($faker->email());

        $event = new MessageSending($message);

        //run
        $catcher = new MailCatcher(
            $loggerMock,
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $catcher->catchmail($event);

        // verify/assert
        $this->assertNotEmpty($message->getCc());
        $this->assertNotEmpty($message->getBcc());
        $this->assertTo($message, $originalTo);
    }

    /**
     * Test that the receiver will set as the to
     *
     * @test
     */
    public function itWillSetTheReceiverInTheTo(): void
    {
        // setup / mock
        $faker = Factory::create();
        $receiver = $faker->email;
        config(['mailcatchall.receiver' => $receiver]);

        $message = new Email();
        $message->text($faker->text);
        $event = new MessageSending($message);

        // run
        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $catcher->catchmail($event);

        // verify/assert
        $this->assertTo($message, $receiver);
    }

    /**
     * Test that the cc receivers will be removed
     *
     * @test
     */
    public function itWillRemoveTheCcReceivers(): void
    {
        // setup / mock
        $faker = Factory::create();
        $receiver = $faker->email;
        config(['mailcatchall.receiver' => $receiver]);

        $message = new Email();
        $message->cc($faker->email);

        $event = new MessageSending($message);

        // run
        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $catcher->catchmail($event);

        // verify/assert
        $this->assertEmpty($message->getBcc());
        $this->assertEmpty($message->getCc());
        $this->assertTo($message, $receiver);
    }

    /**
     * Test that the bcc receivers will be removed
     *
     * @test
     */
    public function itWillRemoveTheBccReceivers(): void
    {
        // setup / mock
        $faker = Factory::create();
        $receiver = $faker->email;
        config(['mailcatchall.receiver' => $receiver]);

        $message = new Email();
        $message->text($faker->text);
        $message->addBcc($faker->email);

        $event = new MessageSending($message);

        // run
        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $catcher->catchmail($event);

        // verify/assert
        $this->assertEmpty($message->getBcc());
        $this->assertTo($message, $receiver);
    }

    /**
     * Test that the to is set in the text view
     *
     * @test
     * @param string|array<string>|Address $originalReceiver
     * @dataProvider originalReceiverProvider
     */
    public function itWillAddOriginalToInTextView(string|array|Address $originalReceiver): void
    {
        // setup / mock
        $faker = Factory::create();
        $receiver = $faker->email;
        config(['mailcatchall.receiver' => $receiver]);
        config(['mailcatchall.add_receivers_to_text' => true]);

        $message = new Email();
        $message->text($faker->text);
        if (is_array($originalReceiver)) {
            $message->to(...$originalReceiver);
        } else {
            $message->to($originalReceiver);
        }

        $event = new MessageSending($message);

        // run
        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $catcher->catchmail($event);

        // verify/assert
        if (is_array($originalReceiver)) {
            $originalReceiver = $originalReceiver[0];
        }

        if ($originalReceiver instanceof Address) {
            $originalReceiver = $originalReceiver->getAddress();
        }
        $this->assertStringContainsStringIgnoringCase($originalReceiver, $message->getTextBody());
    }

    /**
     * Test that the to is set in the html view
     *
     * @test
     * @param string|array<string>|Address $originalReceiver
     * @dataProvider originalReceiverProvider
     */
    public function itWillAddOriginalToInHtmlView(string|array|Address $originalReceiver): void
    {
        // setup / mock
        $faker = Factory::create();
        $receiver = $faker->email;
        config(['mailcatchall.receiver' => $receiver]);
        config(['mailcatchall.add_receivers_to_html' => true]);

        $message = new Email();
        $message->html($faker->text);
        if (is_array($originalReceiver)) {
            $message->to(...$originalReceiver);
        } else {
            $message->to($originalReceiver);
        }

        $event = new MessageSending($message);

        // run
        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $catcher->catchmail($event);

        // verify/assert
        if (is_array($originalReceiver)) {
            $originalReceiver = $originalReceiver[0];
        }

        if ($originalReceiver instanceof Address) {
            $originalReceiver = $originalReceiver->getAddress();
        }
        $this->assertStringContainsStringIgnoringCase($originalReceiver, $message->getHtmlBody());
    }

    /**
     * Test that the to is not set in the html view if disabled
     *
     * @test
     * @param string|array<string>|Address $originalReceiver
     * @dataProvider originalReceiverProvider
     */
    public function itWillNotAddOriginalToInHtmlViewIfDisabled(string|array|Address $originalReceiver): void
    {
        // setup / mock
        $faker = Factory::create();
        $receiver = $faker->email;
        config(['mailcatchall.receiver' => $receiver]);

        $message = new Email();
        $message->html($faker->text);
        if (is_array($originalReceiver)) {
            $message->to(...$originalReceiver);
        } else {
            $message->to($originalReceiver);
        }

        $event = new MessageSending($message);

        // run
        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $catcher->catchmail($event);

        // verify/assert
        if (is_array($originalReceiver)) {
            $originalReceiver = $originalReceiver[0];
        }

        if ($originalReceiver instanceof Address) {
            $originalReceiver = $originalReceiver->getAddress();
        }
        $this->assertStringNotContainsStringIgnoringCase($originalReceiver, $message->getHtmlBody());
    }

    /**
     * Test that the cc is set in the text view
     *
     * @test
     * @param string|array<string>|Address $originalReceiver
     * @dataProvider originalReceiverProvider
     */
    public function itWillAddOriginalCcInTextView(string|array|Address $originalReceiver): void
    {
        // setup / mock
        $faker = Factory::create();
        $receiver = $faker->email;
        config(['mailcatchall.receiver' => $receiver]);
        config(['mailcatchall.add_receivers_to_text' => true]);

        $message = new Email();
        $message->text($faker->text);
        if (is_array($originalReceiver)) {
            $message->cc(...$originalReceiver);
        } else {
            $message->cc($originalReceiver);
        }

        $event = new MessageSending($message);

        // run
        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $catcher->catchmail($event);

        // verify/assert
        if (is_array($originalReceiver)) {
            $originalReceiver = $originalReceiver[0];
        }

        if ($originalReceiver instanceof Address) {
            $originalReceiver = $originalReceiver->getAddress();
        }

        $this->assertStringContainsStringIgnoringCase($originalReceiver, $message->getTextBody());
    }

    /**
     * Test that the cc is set in the html view
     *
     * @test
     * @param string|array<string>|Address $originalReceiver
     * @dataProvider originalReceiverProvider
     */
    public function itWillAddOriginalCcInHtmlView(string|array|Address $originalReceiver): void
    {
        // setup / mock
        $faker = Factory::create();
        $receiver = $faker->email;
        config(['mailcatchall.receiver' => $receiver]);
        config(['mailcatchall.add_receivers_to_html' => true]);

        $message = new Email();
        $message->html($faker->text);
        if (is_array($originalReceiver)) {
            $message->cc(...$originalReceiver);
        } else {
            $message->cc($originalReceiver);
        }

        $event = new MessageSending($message);

        // run
        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $catcher->catchmail($event);

        // verify/assert
        if (is_array($originalReceiver)) {
            $originalReceiver = $originalReceiver[0];
        }

        if ($originalReceiver instanceof Address) {
            $originalReceiver = $originalReceiver->getAddress();
        }

        $this->assertStringContainsStringIgnoringCase($originalReceiver, $message->getHtmlBody());
    }

    /**
     * Test that the bcc is set in the text view
     *
     * @test
     * @param string|array<string>|Address $originalReceiver
     * @dataProvider originalReceiverProvider
     */
    public function itWillAddOriginalBccInTextView(string|array|Address $originalReceiver): void
    {
        // setup / mock
        $faker = Factory::create();
        $receiver = $faker->email;
        config(['mailcatchall.receiver' => $receiver]);
        config(['mailcatchall.add_receivers_to_text' => true]);

        $message = new Email();
        $message->text($faker->text);
        if (is_array($originalReceiver)) {
            $message->bcc(...$originalReceiver);
        } else {
            $message->bcc($originalReceiver);
        }

        $event = new MessageSending($message);

        // run
        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $catcher->catchmail($event);

        // verify/assert
        if (is_array($originalReceiver)) {
            $originalReceiver = $originalReceiver[0];
        }

        if ($originalReceiver instanceof Address) {
            $originalReceiver = $originalReceiver->getAddress();
        }

        $this->assertStringContainsStringIgnoringCase($originalReceiver, $message->getTextBody());
    }

    /**
     * Test that the bcc is set in the html view
     *
     * @test
     * @param string|array<string>|Address $originalReceiver
     * @dataProvider originalReceiverProvider
     */
    public function itWillAddOriginalBccInHtmlView(string|array|Address $originalReceiver): void
    {
        // setup / mock
        $faker = Factory::create();
        $receiver = $faker->email;
        config(['mailcatchall.receiver' => $receiver]);
        config(['mailcatchall.add_receivers_to_html' => true]);

        $message = new Email();
        $message->html($faker->text);
        if (is_array($originalReceiver)) {
            $message->bcc(...$originalReceiver);
        } else {
            $message->bcc($originalReceiver);
        }

        /** @var MockInterface&MessageSending $eventMock */
        $eventMock = Mockery::mock(MessageSending::class);
        $eventMock->message = $message;

        // run
        $catcher = new MailCatcher(
            $this->getLoggerMock(),
            $this->getViewFactory(),
            $this->getConfigRepository()
        );
        $catcher->catchmail($eventMock);

        // verify/assert
        if (is_array($originalReceiver)) {
            $originalReceiver = $originalReceiver[0];
        }

        if ($originalReceiver instanceof Address) {
            $originalReceiver = $originalReceiver->getAddress();
        }
        $this->assertStringContainsStringIgnoringCase($originalReceiver, $message->getHtmlBody());
    }

    /**
     * @return array<array-key,array<string|array<string>|Address>>
     */
    public static function originalReceiverProvider(): array
    {
        $faker = Factory::create();
        return [
            'email' => [
                $faker->email
            ],
            'email array' => [
                [$faker->email]
            ],
            'address class' => [
                new Address($faker->email),
            ],
        ];
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
