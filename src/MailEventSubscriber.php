<?php

declare(strict_types=1);

namespace TJVB\MailCatchall;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Events\Dispatcher;

/**
 * The subscriber for the Mail events
 *
 * @author Tobias van Beek <t.vanbeek@tjvb.nl>
 */
final class MailEventSubscriber
{
    /**
     * @var Repository
     */
    private $config;

    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Dispatcher  $events
     *
     * @return void
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen((string) $this->config->get('mailcatchall.event'), '\TJVB\MailCatchall\MailCatcher@catchmail');
    }
}
