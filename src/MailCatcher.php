<?php

namespace TJVB\MailCatchall;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Log;

/**
 * The class to catch the mail
 *
 * @author Tobias van Beek <t.vanbeek@connexx.nl>
 */
class MailCatcher
{
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
            Log::error('We can\'t send the mail because the mailcatchall.receiver config value isn\'t set');
            return;
        }
        $event->message->setTo($receiver);
        if ($event->message->getCc()) {
            // remove the cc
            $event->message->setCc([]);
        }
        if ($event->message->getBcc()) {
            //remove the bcc
            $event->message->setBcc([]);
        }
    }
}