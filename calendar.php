<?php
session_start();
require 'vendor/autoload.php'; // Include Composer's autoloader

$mealId = $_GET['mealId'] ?? null;
$mealName = $_GET['mealName'] ?? null;
$mealInstructions = $_GET['mealInstructions'] ?? null;

// Load client secrets
$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/client_secret_1045868393633-9moipf0u97pvqhs30nuqj4ga0fkjbhqv.apps.googleusercontent.com.json');
$client->setRedirectUri('http://localhost/Meal-Match/oauth2callback.php');
$client->addScope(Google_Service_Calendar::CALENDAR);
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

// Check if user has a valid access token
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
    $service = new Google_Service_Calendar($client);

    // If a mealId is provided, fetch meal details and create calendar event
    if ($mealId && $mealName && $mealInstructions) {
        // Prepare the event details
        $event = new Google_Service_Calendar_Event(array(
            'summary' => $mealName,
            'description' => $mealInstructions,
            'start' => array(
                'dateTime' => '2023-01-01T10:00:00-07:00', // Example start time
                'timeZone' => 'America/Los_Angeles',
            ),
            'end' => array(
                'dateTime' => '2023-01-01T11:00:00-07:00', // Example end time
                'timeZone' => 'America/Los_Angeles',
            ),
        ));

        // Insert the event into the calendar
        $calendarId = 'primary';
        $event = $service->events->insert($calendarId, $event);
        echo 'Event created: ' . $event->htmlLink;
    } else {
        echo 'Invalid meal data.';
    }
} else {
    header('Location: oauth2callback.php');
    exit();
}
?>
