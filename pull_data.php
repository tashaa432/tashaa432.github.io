<?php
// Fetch quiz data from API
function fetchQuizData() {
    
    $url = 'https://opentdb.com/api.php?amount=1'; // API URL, will replace this with dynamic at some point

    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Bypass SSL certificate verification (not recommended for production)

    // Execute cURL request
    $response = curl_exec($ch);

    // Check if request was successful
    if ($response === false) {
        echo "Failed to fetch data from API: " . curl_error($ch);
        curl_close($ch);
        return null;
    }

    // Close cURL session
    curl_close($ch);

    // Decode JSON response
    $data = json_decode($response, true);

    // Check if JSON decoding was successful
    if ($data === null) {
        echo "Failed to decode JSON data.";
        return null;
    }

    return $data;
}

