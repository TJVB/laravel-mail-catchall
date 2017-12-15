<?php
return [

    /**
     * If we have the mail catchall enabled
     */
    'enabled' => env('MAILCATCHALL_ENABLED', false),

    'receiver' => env('MAILCATCHALL_RECEIVER'),

    'event' => '\Illuminate\Mail\Events\MessageSending'
];