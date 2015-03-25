<?php

class ReportController extends BaseController {


public function buySearch()
{
	ini_set('max_execution_time', 12000);

	$buySymbols = array();

	$symbols = StockInfo::get();

	foreach ($symbols as $symbol) {
		$records = StockRecords::where('symbol', $symbol->symbol)->orderBy('date', 'DESC')->take(4)->get();
		$test = 0;
	
		foreach ($records as $key => $record) {
		
			if($key==0&&$record->trend=="UPWARD")
			{

				$test+=1;
				continue;
			}

			if($record->trend=="DOWNWARD")
			{
				$test+=1;
			}
		}

		if($test==4)
		{
			$buySymbols[] = $symbol->symbol;

		}
	}

	$buys = array();


	//pivot point

	foreach ($buySymbols as $key => $buySymbol ) 
	{

	$pivotrecord = StockRecords::where('symbol', $buySymbol)->orderBy('date', 'DESC')->first();
	
	$pivot = ($pivotrecord->high + $pivotrecord->low + $pivotrecord->close) / 3;
	
	$high_1 = $pivot + ($pivot - $pivotrecord->low);
	$low_1 = $pivot - ($pivotrecord->high - $pivot);
	$high_2 = $pivot + 2*($pivot - $pivotrecord->low);
	$low_2 = $pivot - 2*($pivotrecord->high - $pivot);
	$high_3 = $pivotrecord->high + 2*($pivot-$pivotrecord->low);
	$low_3 = $pivotrecord->low - 2*($pivotrecord->high - $pivot);

	$buys[] = array(
		"symbol" => $buySymbol,
		"resistance_1" => $high_1,
		"resistance_2" => $high_2,
		"resistance_3" => $high_3,
		"support_1" => $low_1,
		"support_2" => $low_2,
		"support_3" => $low_3,
		"diff_1"	=> $high_1 - $low_1,
		"diff_2"	=> $high_2 - $low_2,
		"diff_3"	=> $high_3 - $low_3,
		);
	}
	//end pivot point
	/*
	$connected = @fsockopen("www.google.com.ph", 443);  //website, port  (try 80 or 443)
    if (!$connected)
		return $buys;
		*/
	//trigger

		$endpoint = "stocks.json";
		$datax=null;
		$fails = 0;
		while($datax==null)
		{
			if($fails==100)
				return $buys;
			$fails+=1;

		$data = $this->get_json($endpoint);
		$datax=json_decode($data);
		}

		$currents = $datax->stock;

		$buysignals = array();

		foreach ($buys as $key => $buy) {
			foreach ($currents as $current) {
				if(strtoupper($current->symbol)==strtoupper($buy['symbol']))
				{
					$price = (String)$current->price->amount;
					$c_price = floatval($price);
					$level = 0;

					if($c_price<=$buy['support_1']&&$c_price>=$buy['support_3'])
					{
						$level = 1;

						if($c_price<=$buy['support_2'])
							$level = 2;						
					}

					$buysignals[] = array(
							"symbol" => $buy['symbol'], 
							"name"	=> $current->name,
							"price" => $c_price, 
							"level" => $level,
							"resistance_1" => $buy["resistance_1"],
							"resistance_2" => $buy["resistance_2"],
							"resistance_3" => $buy["resistance_3"],
							"support_1" => $buy["support_1"],
							"support_2" => $buy["support_2"],
							"support_3" => $buy["support_3"],
							"diff_1"	=> $buy["diff_1"],
							"diff_2"	=> $buy["diff_2"],
							"diff_3"	=> $buy["diff_3"],
							);
				}

			}
		}
	//end trigger

	return $buysignals;
}

public function sellSearch()
{
	
	ini_set('max_execution_time', 12000);

	$sellSymbols = array();

	$symbols = StockInfo::get();

	foreach ($symbols as $symbol) {
		$records = StockRecords::where('symbol', $symbol->symbol)->orderBy('date', 'DESC')->take(4)->get();
		$test = 0;
	
		foreach ($records as $key => $record) {
		
			if($key==0&&$record->trend=="DOWNWARD")
			{
			
				$test+=1;
				continue;
			}

			if($record->trend=="UPWARD")
			{
				$test+=1;
			}
		}

		if($test==4)
		{
			$sellSymbols[] = $symbol->symbol;

		}
	}

	$sells = array();
	//pivot point

	foreach ($sellSymbols as $key => $sellSymbol ) 
	{
		# code...
	$pivotrecord = StockRecords::where('symbol', $sellSymbol)->orderBy('date', 'DESC')->first();
	
	$pivot = ($pivotrecord->high + $pivotrecord->low + $pivotrecord->close) / 3;
	
	$high_1 = $pivot + ($pivot - $pivotrecord->low);
	$low_1 = $pivot - ($pivotrecord->high - $pivot);
	$high_2 = $pivot + 2*($pivot - $pivotrecord->low);
	$low_2 = $pivot - 2*($pivotrecord->high - $pivot);
	$high_3 = $pivotrecord->high + 2*($pivot-$pivotrecord->low);
	$low_3 = $pivotrecord->low - 2*($pivotrecord->high - $pivot);

	$sells[] = array(
		"symbol" => $sellSymbol,
		"resistance_1" => $high_1,
		"resistance_2" => $high_2,
		"resistance_3" => $high_3,
		"support_1" => $low_1,
		"support_2" => $low_2,
		"support_3" => $low_3,
		"diff_1"	=> $high_1 - $low_1,
		"diff_2"	=> $high_2 - $low_2,
		"diff_3"	=> $high_3 - $low_3,
		);
	}
	//end pivot point
	$connected = @fsockopen("www.google.com.ph", 443);  //website, port  (try 80 or 443)
    if (!$connected)
		return $sells;

	//trigger

		$endpoint = "stocks.json";
		$datax=null;
		$fails=0;
		while($datax==null)
		{
			if($fails==5)
				return $sells;
			$fails+=1;
		$data = $this->get_json($endpoint);
		$datax=json_decode($data);
		}

		$currents = $datax->stock;

		$sellsignals = array();

		foreach ($sells as $key => $sell) {
			foreach ($currents as $current) {
				if(strtoupper($current->symbol)==strtoupper($sell['symbol']))
				{
					$price = (String)$current->price->amount;
					$c_price = floatval($price);
					$level = 0;

					if($c_price<=$sell['resistance_1']&&$c_price>=$sell['resistance_3'])
					{
						$level = 1;

						if($c_price>=$sell['resistance_2'])
							$level = 2;						
					}

					$sellsignals[] = array(
							"symbol" => $sell['symbol'], 
							"name"	=> $current->name,
							"price" => $c_price, 
							"level" => $level,
							"resistance_1" => $sell["resistance_1"],
							"resistance_2" => $sell["resistance_2"],
							"resistance_3" => $sell["resistance_3"],
							"support_1" => $sell["support_1"],
							"support_2" => $sell["support_2"],
							"support_3" => $sell["support_3"],
							"diff_1"	=> $sell["diff_1"],
							"diff_2"	=> $sell["diff_2"],
							"diff_3"	=> $sell["diff_3"],
							);
				}

			}
		}
	//end trigger
	return $sellsignals;
}


	public function all_stocks_today(){
		$endpoint = "stocks.json";
		$result = $this->get_json($endpoint);
		return $result;
	}
	public function stock_status($symbol){
		$endpoint = "stocks/".$symbol.".json";
		$result = $this->get_json($endpoint);
		return $result;
	}


	public function get_json( $endpoint){
		$qry_str = $endpoint;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://phisix-api.appspot.com/' . $qry_str); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, '3');
		$content = trim(curl_exec($ch));
		curl_close($ch);
		return $content;
	}
	



}
