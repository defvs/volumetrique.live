<?php
	include "simple_html_dom.php";
	
	$html = file_get_html('https://support.worldwildlife.org/site/TR?px=3651051&fr_id=2313&pg=personal');
	if ($html === false) {
		http_response_code(500);
		echo("500 Internal Server Error");
		exit();
	}
	$current_donation = $html->find('.therm-amt .counter')[0];
	$goal = $html->find('#personal_fundraising_goal')[0];
	$percent = $html->find('.therm-pct .counter')[0];
	$donators = $html->find('.donor-list-indicator-container .team-honor-list-name');
	$donations = $html->find('.donor-list-indicator-container .team-honor-list-value');
	
	$dons = array();
	
	$need_update = true;
	if (($cachefile = @fopen("donation.cache", "r"))) {
		list ($cached_current_donation, $cached_goal) = @fscanf($cachefile, "%s,%s");
		
		$need_update = false;
		if ($cached_current_donation > $current_donation) {
			$current_donation = $cached_current_donation;
			$need_update = true;
		}
		
		if ($cached_goal > $goal) {
			$goal = $cached_goal;
			$need_update = true;
		}
		
		@fclose($cachefile);
	}
	
	if ($need_update) {
		if ($cachefile = @fopen("donation.cache", "w")) {
			@fprintf($cachefile, "%s,%s", $current_donation, $goal);
			@fclose($cachefile);
		}
	}
	
	
	for ($i = 0; $i < count($donators); $i++) {
		array_push($dons, array(
			"donator" => trim($donators[$i]->innertext),
			"amount" => trim($donations[$i]->innertext)
		));
	}
	
	header("Content-Type: application/json");
	header("Access-Control-Allow-Origin: *");
	$goal = $goal->innertext;
	echo json_encode((object)array(
		"current" => "$" . $current_donation->innertext,
		"goal" => substr($goal, strpos($goal, "$")),
		"percent" => $percent->innertext . "%",
		"donations" => $dons
	));