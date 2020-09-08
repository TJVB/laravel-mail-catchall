<?php

namespace TJVB\MailCatchall;

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

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Handle the event.
     *
     * @param MessageSending $event
     *
     * @return void
     */
    public function catchmail(MessageSending $event)
    {
        if (!\config('mailcatchall.enabled')) {
            // this isn't enabled so we do nothing
            return;
        }
        $receiver = \config('mailcatchall.receiver');

        if (!$receiver) {
            // there isn't a catch all adres configurated so we don't need to do anything
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
    protected function appendReceivers(MessageSending $event, array $receivers)
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
    protected function appendHtmlReceiver(MessageSending $event, array $receivers)
    {
        if (!\config('mailcatchall.add_receivers_to_html')) {
            return;
        }
        $body = $event->message->getBody();
        $body = $body . \view('mailcatchall::receivers.html', ['receivers' => $receivers]);
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
    protected function appendTextReceiver(MessageSending $event, array $receivers)
    {
        if (!\config('mailcatchall.add_receivers_to_text')) {
            return;
        }
        $body = $event->message->getBody();
        $body = $body . \view('mailcatchall::receivers.text', ['receivers' => $receivers]);
        $event->message->setBody($body);
    }
}
