<?php

// Define the API endpoints and client credentials
$tokenUrl = "https://v5api.tiltify.com/oauth/token";
$client_id = "59d8282e6682ba9baf18db56f2bfb40bc50ac20bc5eda2cf67bb9652a7340f47";
$client_secret = "2a12e8609964e70ac72e21391dc6d9c767757630c69cb71332aebf19df483021";

// Define the team campaign ID
$teamCampaignId = "01d3ae02-b377-427e-ab10-1fd4650c1388";

// Prepare the POST data for the token request
$postData = http_build_query([
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'grant_type' => 'client_credentials'
]);

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $tokenUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

// Execute the cURL request
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    // Decode the JSON response
    $tokenData = json_decode($response, true);

    // Check if the token was successfully retrieved
    if (isset($tokenData['access_token'])) {
        $accessToken = $tokenData['access_token'];

        // Prepare the URL for the team campaign request
        $teamCampaignUrl = "https://v5api.tiltify.com/api/public/team_campaigns/{$teamCampaignId}";

        // Initialize cURL session for the team campaign request
        $chPublic = curl_init();
        curl_setopt($chPublic, CURLOPT_URL, $teamCampaignUrl);
        curl_setopt($chPublic, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chPublic, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ]);

        // Execute the cURL request
        $publicResponse = curl_exec($chPublic);

        // Check for errors
        if (curl_errno($chPublic)) {
            echo 'Error:' . curl_error($chPublic);
        } else {
            // Decode the JSON response
            $campaignData = json_decode($publicResponse, true);

            // Clean up the response to only include the required fields
            $cleanedData = [
                'name' => isset($campaignData['data']['name']) ? $campaignData['data']['name'] : null,
                'amount_raised' => isset($campaignData['data']['amount_raised']) ? $campaignData['data']['amount_raised'] : null,
                'goal' => isset($campaignData['data']['goal']) ? $campaignData['data']['goal'] : null
            ];

            // Prepare the URL for the donations request
            $donationsUrl = "https://v5api.tiltify.com/api/public/team_campaigns/{$teamCampaignId}/donations";

            // Initialize cURL session for the donations request
            $chDonations = curl_init();
            curl_setopt($chDonations, CURLOPT_URL, $donationsUrl);
            curl_setopt($chDonations, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chDonations, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json'
            ]);

            // Execute the cURL request
            $donationsResponse = curl_exec($chDonations);

            // Check for errors
            if (curl_errno($chDonations)) {
                echo 'Error:' . curl_error($chDonations);
            } else {
                // Decode the JSON response
                $donationsData = json_decode($donationsResponse, true);

                // Clean up the donations data
                $cleanedDonations = [];
                foreach ($donationsData['data'] as $donation) {
                    $cleanedDonations[] = [
                        'amount' => isset($donation['amount']) ? $donation['amount'] : null,
                        'donor_name' => isset($donation['donor_name']) ? $donation['donor_name'] : null,
                        'completed_at' => isset($donation['completed_at']) ? $donation['completed_at'] : null
                    ];
                }

                // Add the cleaned donations data to the cleaned data
                $cleanedData['donations'] = $cleanedDonations;

                // Output the cleaned response to the caller
                header('Content-Type: application/json');
                echo json_encode($cleanedData);
            }

            // Close the cURL session
            curl_close($chDonations);
        }

        // Close the cURL session
        curl_close($chPublic);
    } else {
        echo 'Failed to retrieve access token.';
    }
}

// Close the cURL session
curl_close($ch);

?>
