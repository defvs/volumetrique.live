<?php
// Load the page from the provided URL
$page_url = "https://www.gofundme.com/f/zemefest2024/widget/large";
$html = file_get_contents($page_url);

// Check if the content was fetched successfully
if ($html === false) {
    die("Could not fetch the page.");
}

// Load the HTML content into a DOMDocument
$doc = new DOMDocument();
libxml_use_internal_errors(true); // Ignore parsing errors
$doc->loadHTML($html);
libxml_clear_errors();

// Create a new meta tag
$refresh_meta = $doc->createElement("meta");
$refresh_meta->setAttribute("http-equiv", "refresh");
$refresh_meta->setAttribute("content", "30");

// Get the head element and append the meta tag
$head = $doc->getElementsByTagName("head")->item(0);
if ($head) {
    $head->appendChild($refresh_meta);
} else {
    die("No head element found in the page.");
}

// Output the modified HTML
header("Content-Type: text/html");
echo $doc->saveHTML();
