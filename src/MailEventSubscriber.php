<?php

namespace TJVB\MailCatchall;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Events\Dispatcher;

/**
 *
 * @author Tobias van Beek <t.vanbeek@tjvb.nl>
 */
class MailEventSubscriber
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
        $receiver = \config('mailcatchall.receiver');

        if (!$receiver) {
            // there isn't a catch all adres configurated so we don't need to do anything
            app('log')->error('We can\'t send the mail because the mailcatchall.receiver config value isn\'t set');
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

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     *
     * @return void
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(\config('mailcatchall.event'), '\TJVB\MailCatchall\MailEventSubscriver@catchmail');
    }
}