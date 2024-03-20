<?php

declare(strict_types=1);

namespace TJVB\MailCatchall;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Mail\Events\MessageSending;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Address;

/**
 * The class to catch the mail
 *
 * @author Tobias van Beek <t.vanbeek@tjvb.nl>
 */
final class MailCatcher
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Factory
     */
    private $viewFactory;
    /**
     * @var Repository
     */
    private $config;

    public function __construct(LoggerInterface $logger, Factory $viewFactory, Repository $config)
    {
        $this->logger = $logger;
        $this->viewFactory = $viewFactory;
        $this->config = $config;
    }

    /**
     * Handle the event.
     *
     * @param MessageSending $event
     *
     * @return void
     */
    public function catchmail(MessageSending $event): void
    {
        if (!$this->config->get('mailcatchall.enabled')) {
            // this isn't enabled so we do nothing
            return;
        }
        $receiver = (string) $this->config->get('mailcatchall.receiver');

        if (!$receiver) {
            // there isn't a catch all address configured so we don't need to do anything
            $this->logger->error('We can\'t send the mail because the mailcatchall.receiver config value isn\'t set');
            return;
        }
        $originalReceivers = [
            'to' => $event->message->getTo(),
            'cc' => $event->message->getCc(),
            'bcc' => $event->message->getBcc(),
        ];
        $event->message->to($receiver);
        if ($event->message->getCc() || $event->message->getBcc()) {
            $headers = $event->message->getHeaders();
            // remove the cc
            $headers->remove('cc');
            //remove the bcc
            $headers->remove('bcc');
            $event->message->setHeaders($headers);
        }
        $this->appendReceivers($event, $originalReceivers);
    }

    /**
     * Append the receivers to the body
     *
     * @param MessageSending $event
     * @param array $receivers
     *
     * @return void
     */
    private function appendReceivers(MessageSending $event, array $receivers): void
    {
        $map = static function (string|Address $receiver): string {
            if ($receiver instanceof Address) {
                return $receiver->toString();
            }
            return $receiver;
        };
        $receivers['to'] = array_map($map, (array) $receivers['to']);
        $receivers['cc'] = array_map($map, (array) $receivers['cc']);
        $receivers['bcc'] = array_map($map, (array) $receivers['bcc']);

        if ($event->message->getHtmlBody() !== null) {
            $this->appendHtmlReceiver($event, $receivers);
            return;
        }
        $this->appendTextReceiver($event, $receivers);
    }

    /**
     * Append the receivers to the html
     *
     * @param MessageSending $event
     * @param array $receivers
     *
     * @return void
     */
    private function appendHtmlReceiver(MessageSending $event, array $receivers): void
    {
        if (!$this->config->get('mailcatchall.add_receivers_to_html')) {
            return;
        }
        $body = (string) $event->message->getHtmlBody();
        $body .= $this->viewFactory->make('mailcatchall::receivers.html')
            ->with('receivers', $receivers)
            ->render();
        $event->message->html($body, $event->message->getHtmlCharset() ?? 'utf-8');
    }

    /**
     * Append the receivers to the text
     *
     * @param MessageSending $event
     * @param array $receivers
     *
     * @return void
     */
    private function appendTextReceiver(MessageSending $event, array $receivers): void
    {
        if (!$this->config->get('mailcatchall.add_receivers_to_text')) {
            return;
        }
        $body = (string) $event->message->getTextBody();
        $body .= $this->viewFactory->make('mailcatchall::receivers.text')
            ->with('receivers', $receivers)
            ->render();
        $event->message->text($body);
    }
}
