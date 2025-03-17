<?php
session_start();
require 'vendor/autoload.php'; // Include Composer's autoloader

$mealId = $_GET['mealId'] ?? null; // Retrieve meal ID
$mealName = $_GET['mealName'] ?? null; // Retrieve meal name
$mealInstructions = $_GET['mealInstructions'] ?? null; // Retrieve meal instructions

// Load client secrets
$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/client_secret_1045868393633-9moipf0u97pvqhs30nuqj4ga0fkjbhqv.apps.googleusercontent.com.json');
$client->setRedirectUri('http://localhost/Meal-Match/oauth2callback.php');  // Ensure this matches the redirect URL in your Google Console
$client->addScope(Google_Service_Calendar::CALENDAR);
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

// Check if user has a valid access token
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
    $service = new Google_Service_Calendar($client);

    // If meal data exists, create a calendar event
    if ($mealId && $mealName && $mealInstructions) { // Check if meal data exists
        // Prepare the event details
        $event = new Google_Service_Calendar_Event(array(
            'summary' => $mealName,
            'description' => $mealInstructions,
            'start' => array(
                'dateTime' => '2023-01-01T10:00:00-07:00', // Example start time, adjust as needed
                'timeZone' => 'America/Los_Angeles',
            ),
            'end' => array(
                'dateTime' => '2023-01-01T11:00:00-07:00', // Example end time, adjust as needed
                'timeZone' => 'America/Los_Angeles',
            ),
        ));

        // Insert the event into the calendar
        $calendarId = 'primary';
        try {
            $event = $service->events->insert($calendarId, $event);
            echo 'Event created: ' . $event->htmlLink; // Provide a link to the event
        } catch (Exception $e) {
            echo 'Error creating event: ' . $e->getMessage(); // Log any errors
            error_log('Error creating event: ' . $e->getMessage()); // Log error to server log
            error_log('Event details: ' . print_r($event, true)); // Log event details for debugging
        }
    } else {
        echo 'Invalid meal data. Received: ' . print_r($_GET, true); // Log received parameters for debugging
        error_log('Invalid meal data. Received: ' . print_r($_GET, true)); // Log to server log
    }
} else {
    // If no valid access token, redirect to oauth2callback.php for OAuth authentication
    header('Location: oauth2callback.php');
    exit();
}

