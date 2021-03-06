<?php
include "../fastspring.php";

define("customer_data_dir", "tmpcustomers");
define("product_id", "subprd1");

$fastspring = new FastSpring("your_store_id", "your_api_username", "your_api_password");
$fastspring->test_mode = true;

session_start();

$_SESSION["customer_ref"] = 1;

function isSubscribed($customer_ref) {
	global $fastspring;
	
	if (isset($_SESSION["subscription_ref"])) {
		return true;
	}
	
	$file = customer_data_dir."/$customer_ref.txt";
	if (file_exists($file)) {
		$customer_data = fopen($file, "r");
	}
	if($customer_data != false) {
		$subscription_ref = fgets($customer_data);
		fclose($customer_data);

		try {
			$sub = $fastspring->getSubscription($subscription_ref);
		
			if ($sub->status == "active") {
				$_SESSION["subscription_ref"] = $sub->reference;
				return true;
			}
		} catch (FsprgException $e) {
			// Error can be handled here
		}
	}
	return false;
}

?>
