<?php
// oauth2callback.php

session_start();
require 'vendor/autoload.php'; // Include Composer's autoloader

// Load client secrets
$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/client_secret_1045868393633-9moipf0u97pvqhs30nuqj4ga0fkjbhqv.apps.googleusercontent.com.json');
$client->setRedirectUri('http://localhost/Meal-Match/oauth2callback.php');  // Ensure this matches the redirect URL in your Google Console
$client->addScope(Google_Service_Calendar::CALENDAR);
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

// Exchange the authorization code for an access token
if (isset($_GET['code'])) {
    $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['access_token'] = $accessToken;
    header('Location: profile.php'); // Redirect to profile or other page
    exit();
}

// If no authorization code, redirect to Google's OAuth 2.0 server
if (!isset($_GET['code'])) {
    header('Location: ' . $client->createAuthUrl());
    exit();
}
?>