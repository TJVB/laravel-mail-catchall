<?php

declare(strict_types=1);

namespace TJVB\MailCatchall;

use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;

/**
 * The ServiceProvider to load the Mail Catch All package
 *
 * @author Tobias van Beek <t.vanbeek@tjvb.nl>
 */
final class MailCatchallServiceProvider extends ServiceProvider
{
    /**
     * Boot the module, this is called after everything is registered
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'mailcatchall');

        $this->publishes([
            __DIR__ . '/../config/mailcatchall.php' => config_path('mailcatchall.php'),
        ], 'config');
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/mailcatchall'),
        ], 'views');

        $this->registerEventListener();
        $this->registerAboutInformation();
    }

    /**
     * Register the module, let the application know what is available
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/mailcatchall.php',
            'mailcatchall'
        );
    }

    /**
     * Register the event listener
     *
     * @return void
     */
    protected function registerEventListener(): void
    {
        if (!config('mailcatchall.enabled')) {
            return;
        }
        /** @var Dispatcher $dispatcher */
        $dispatcher = $this->app->get('events');

        $dispatcher->subscribe(MailEventSubscriber::class);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function registerAboutInformation(): void
    {
        if (!class_exists(AboutCommand::class)) {
            return;
        }
        $enabled = 'NOT Enabled';
        if (config('mailcatchall.enabled')) {
            $enabled = '<fg=yellow;options=bold>ENABLED</>';
        }

        AboutCommand::add('Laravel Mail Catchall', static fn () => [
            'Enabled' => $enabled,
            'Receiver' => config('mailcatchall.receiver'),
        ]);
    }
}
