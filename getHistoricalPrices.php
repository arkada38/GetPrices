<?php
	if(!isset($_GET["symbol"]) || !isset($_GET["exchange"])) {
		echo("You must select symbol and exchange");
		exit(1);
	}

	$symbol = $_GET["symbol"];
	$exchange = $_GET["exchange"];
	
	header("Content-type: text/txt");
	header("Content-Disposition: attachment; filename={$symbol}.csv");
	# header("Content-Disposition: inline; filename={$symbol}.csv");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	if(isset($_GET["interval"]) && strlen($_GET["interval"]) > 0) {
		$interval = $_GET["interval"];
	} else {
		$interval = 86400;
	}
	
	if(isset($_GET["period"]) && strlen($_GET["period"]) > 0) {
		$period = $_GET["period"];
	} else {
		$period = "10Y";
	}
	
	$url = "https://www.google.com/finance/getprices?q=" .
		$symbol . "&x=" . $exchange . "&i=" .
		$interval . "&p=" . $period . "&f=d,c,v,k,o,h,l";
	# echo nl2br ($url . "\r\n");
	
	$content = file_get_contents($url);
	$rows = explode("\n", $content);
	# echo nl2br ($rows[0] . "\r\n");
	
	if(count($rows) <= 7){
		echo("Incorrect parameters");
		exit(1);
	}
	
	$timezone_offset = 0;
	$absolute_date = 0;
	
	echo "Date,Market,Open,High,Low,Close,Volume\r\n";
	
	for ($i = 6; $i < count($rows) - 1; $i++) {
		if(substr($rows[$i], 0, strlen("TIMEZONE_OFFSET=")) == "TIMEZONE_OFFSET="){
			$timezone_offset = intval(substr($rows[$i], strlen("TIMEZONE_OFFSET=")));
		}else{
			$temp = explode(",", $rows[$i]);
			
			if(substr($temp[0], 0, 1) == "a"){
				$absolute_date = intval(substr($temp[0], 1));
				$Date = $absolute_date;
			}else{
				$Date = $absolute_date + intval($temp[0]) * $interval;
			}
			
			$Market = date("Y.m.d H:i:s", $Date + $timezone_offset * 60);
			
			$Open = floatval($temp[4]);
			$High = floatval($temp[2]);
			$Low = floatval($temp[3]);
			$Close = floatval($temp[1]);
			$Volume = intval($temp[5]);
			
			echo "{$Date}, {$Market}, {$Open}, {$High}, {$Low}, {$Close}, {$Volume}\r\n";
		}
	}
?>