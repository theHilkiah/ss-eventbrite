<?php

namespace SS_EventBrite;

// define("EVENTBRITE_OAUTH_BASE", "https://www.eventbrite.com/oauth/");

function createAuthorizeUrl($client_key)
{
    $event_base = getenv('EVENTBRITE_OAUTH_BASE', EVENTBRITE_OAUTH_BASE);
    return $event_base . 'authorize?response_type=code&client_id=' . $client_key;
}

function handshake($code, $client_secret, $app_key)
{
    $post_args = array(
        'code' => $code,
        'client_secret' => $client_secret,
        'client_id' => $app_key,
        'grant_type' => 'authorization_code'
    );
    $data = http_build_query($post_args);

    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => "Content-type: application/x-www-form-urlencoded",
            'content' => $data,
            'ignore_errors' => true
        )
    );

    $url = EVENTBRITE_OAUTH_BASE . 'token';

    $context = stream_context_create($options);

    $result = file_get_contents($url, false, $context);

    /* this is where we will handle errors. Eventbrite errors are a part of the response payload and are returned as an associative array. */
    return json_decode($result, true);

}
