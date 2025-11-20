<?php
// Set the CORS headers to allow all origins
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Function to get data from an API endpoint
function getCachedData($endpoint, $cacheKey, $cacheDuration) {
    // Define the cache directory
    $cacheDir = 'cache/';
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0777, true);
    }

    // Define the cache file path
    $cacheFile = $cacheDir . $cacheKey . '.json';

    // Check if the cache file exists and is within the cache duration
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheDuration)) {
        // Return cached data
        $cachedData = file_get_contents($cacheFile);
        echo $cachedData;
        return;
    }

    // Fetch fresh data from the endpoint
    $data = file_get_contents($endpoint);

    if ($data !== false) {
        // Cache the data
        file_put_contents($cacheFile, $data);

        // Return the fetched data
        echo $data;
    } else {
        // Return an error response if fetching failed
        http_response_code(500);
        echo json_encode(["error" => "Failed to fetch data from endpoint"]);
    }
}

// Define base URL and endpoint details
$baseUrl = 'https://gateway.gofundme.com/web-gateway/v1/feed/';
$campaignName = $_GET['campaign_name'] ?? 'zemefest'; // default if not specified
$limit = $_GET['limit'] ?? 10; // default limit
$type = $_GET['type'] ?? 'donations';
$endpoint = $baseUrl . $campaignName . "/" . $type . "?limit=" . $limit . "&offset=0";

// Generate a cache key based on the endpoint
$cacheKey = md5($endpoint);

// Set cache duration to 2 minutes (120 seconds)
$cacheDuration = 120;

// Fetch and return data with caching
getCachedData($endpoint, $cacheKey, $cacheDuration);
?>
