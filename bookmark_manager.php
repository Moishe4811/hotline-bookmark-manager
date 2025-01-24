<?php

// Configurations
define("API_URL", "https://www.call2all.co.il/ym/api/");
define("USERNAME", "0733183618"); // Replace with your username
define("PASSWORD", "5588726"); // Replace with your password

/**
 * Log in to the API and return the token
 */
function login() {
    $url = API_URL . "Login?username=" . urlencode(USERNAME) . "&password=" . urlencode(PASSWORD);
    $response = json_decode(file_get_contents($url), true);

    if ($response['responseStatus'] === "OK") {
        return $response['token'];
    } else {
        die("Login failed: " . $response['message']);
    }
}

/**
 * Save a bookmark (phone number and position) to a file on the hotline system
 */
function saveBookmark($token, $phone, $bookmark) {
    $filePath = "ivr2:/bookmarks.txt";
    $data = json_encode(["phone" => $phone, "bookmark" => $bookmark]);
    $url = API_URL . "UploadTextFile?token=$token&what=$filePath&contents=" . urlencode($data);

    $response = json_decode(file_get_contents($url), true);

    if ($response['responseStatus'] === "OK") {
        echo "Bookmark saved successfully!\n";
    } else {
        die("Error saving bookmark: " . $response['message']);
    }
}

/**
 * Retrieve all bookmarks from the hotline system
 */
function getBookmarks($token) {
    $filePath = "ivr2:/bookmarks.txt";
    $url = API_URL . "DownloadFile?token=$token&what=$filePath";

    $response = file_get_contents($url);

    if ($response) {
        echo "File contents: " . $response . "\n";
        return json_decode($response, true);
    } else {
        die("Error retrieving file.");
    }
}

// Example usage:
$token = login(); // Step 1: Log in and get a token

// Save a new bookmark
saveBookmark($token, "123456789", "5");

// Retrieve bookmarks
$bookmarks = getBookmarks($token);
print_r($bookmarks);

?>
