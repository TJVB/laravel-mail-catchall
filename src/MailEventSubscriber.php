<?php

namespace TJVB\MailCatchall;

use Illuminate\Events\Dispatcher;

/**
 * The subscriber for the Mail events
 *
 * @author Tobias van Beek <t.vanbeek@tjvb.nl>
 */
class MailEventSubscriber
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     *
     * @return void
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(\config('mailcatchall.event'), '\TJVB\MailCatchall\MailCatcher@catchmail');
    }
}
