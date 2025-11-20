<?php

// set the URL to fetch
$url = "http://146.59.158.11/";

// initialize cURL
$curl = curl_init($url);

// set options for the cURL session
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // return the response instead of outputting it

// execute the cURL session
$response = curl_exec($curl);

// check for errors
if (curl_errno($curl)) {
    echo "Error fetching URL: " . curl_error($curl);
    exit;
}

// close the cURL session
curl_close($curl);

// output the response
echo $response;
