<?php
// google-auth.php

// Manually include Google API classes
require_once __DIR__ . '/google-api-php-client-main/src/Client.php';
require_once __DIR__ . '/google-api-php-client-main/src/Service/Calendar.php';

session_start();

// Set up Google Client
$client = new Google_Client();
$client->setClientId('1045868393633-9moipf0u97pvqhs30nuqj4ga0fkjbhqv.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/Meal-Match/oauth2callback.php');
$client->addScope(Google_Service_Calendar::CALENDAR);  // This scope grants access to Google Calendar

// If there's an existing token, set it
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
}

// Redirect to Google for authentication if no token is available
if (!$client->getAccessToken()) {
    $authUrl = $client->createAuthUrl();
    header('Location: ' . $authUrl);
    exit();
}
?>
