<?php

namespace TJVB\MailCatchall;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Mail\Events\MessageSending;
use Psr\Log\LoggerInterface;

/**
 * The class to catch the mail
 *
 * @author Tobias van Beek <t.vanbeek@tjvb.nl>
 */
class MailCatcher
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
        $receiver = $this->config->get('mailcatchall.receiver');

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
        $event->message->setTo($receiver);
        if ($event->message->getCc()) {
            // remove the cc
            $event->message->setCc([]);
        }
        if ($event->message->getBcc()) {
            //remove the bcc
            $event->message->setBcc([]);
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
    protected function appendReceivers(MessageSending $event, array $receivers): void
    {
        $contentType = $event->message->getContentType();
        if (\stripos($contentType, 'html') !== false) {
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
    protected function appendHtmlReceiver(MessageSending $event, array $receivers): void
    {
        if (!$this->config->get('mailcatchall.add_receivers_to_html')) {
            return;
        }
        $body = $event->message->getBody();
        $body .= $this->viewFactory->make('mailcatchall::receivers.html')
            ->with('receivers', $receivers)
            ->render();
        $event->message->setBody($body);
    }

    /**
     * Append the receivers to the text
     *
     * @param MessageSending $event
     * @param array $receivers
     *
     * @return void
     */
    protected function appendTextReceiver(MessageSending $event, array $receivers): void
    {
        if (!$this->config->get('mailcatchall.add_receivers_to_text')) {
            return;
        }
        $body = $event->message->getBody();
        $body .= $this->viewFactory->make('mailcatchall::receivers.text')
            ->with('receivers', $receivers)
            ->render();
        $event->message->setBody($body);
    }
}
