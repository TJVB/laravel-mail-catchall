<?php
return [

    /**
     * If we have the mail catchall enabled
     */
    'enabled' => env('MAILCATCHALL_ENABLED', false),

    /**
     * The receiver for all the mail
     */
    'receiver' => env('MAILCATCHALL_RECEIVER'),

    /**
     * The event that we catch to change the receiver
     */
    'event' => 'Illuminate\Mail\Events\MessageSending',

    /**
     * If we add the receivers to text mail
     */
    'add_receivers_to_text' => true,

    /**
     * If we add the receivers to html mail
     */
    'add_receivers_to_html' => true,
];