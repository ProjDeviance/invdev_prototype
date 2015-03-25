<?php

class SimulatorController extends BaseController {


public function buySearch($symbol)
{
	ini_set('max_execution_time', 12000);

	StockRecords::where('simulated', '!=', 0)->update(array('simulated' => 0,));

	$simulations = StockRecords::where('symbol', $symbol)->where('simulated', 0)->count();


	$buys = array();

	while($simulations!=0) 
	{

		$records = StockRecords::where('symbol', $symbol)->where('simulated', 0)->orderBy('date', 'DESC')->take(4)->get();
		$test = 0;
		
		$firstId = 0;
		foreach ($records as $key => $record) 
		{
			if($key==0)
			{
				$firstId = $record->id;
			}

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
		//pivot point

			
			$pivot = ($record->high + $record->low + $record->close) / 3;
	
			$high_1 = $pivot + ($pivot - $record->low);
			$low_1 = $pivot - ($record->high - $pivot);
			$high_2 = $pivot + 2*($pivot - $record->low);
			$low_2 = $pivot - 2*($record->high - $pivot);
			$high_3 = $record->high + 2*($pivot-$record->low);
			$low_3 = $record->low - 2*($record->high - $pivot);

			$buys[] = array(
				"symbol" => $symbol,
				"resistance_1" => $high_1,
				"resistance_2" => $high_2,
				"resistance_3" => $high_3,
				"support_1" => $low_1,
				"support_2" => $low_2,
				"support_3" => $low_3,
				"diff_1"	=> $high_1 - $low_1,
				"diff_2"	=> $high_2 - $low_2,
				"diff_3"	=> $high_3 - $low_3,
				"close"		=> $record->close,
				"date"		=> $record->date,
				);
		

		//end pivot point
		}

		$simulations = StockRecords::where('symbol', $symbol)->where('simulated', 0)->count();
		if($simulations==0)
		{
			break;
		}
		
		StockRecords::where('id', $firstId)->update(array('simulated' => 1,));
		
	}


	

	return $buys;
}

public function sellSearch($symbol)
{
	ini_set('max_execution_time', 12000);

	StockRecords::where('simulated', '!=', 0)->update(array('simulated' => 0,));

	$simulations = StockRecords::where('symbol', $symbol)->where('simulated', 0)->count();


	$sells = array();

	while($simulations!=0) 
	{

		$records = StockRecords::where('symbol', $symbol)->where('simulated', 0)->orderBy('date', 'DESC')->take(4)->get();
		$test = 0;
		
		$firstId = 0;
		foreach ($records as $key => $record) 
		{
			if($key==0)
			{
				$firstId = $record->id;
			}

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
		//pivot point

			
			$pivot = ($record->high + $record->low + $record->close) / 3;
	
			$high_1 = $pivot + ($pivot - $record->low);
			$low_1 = $pivot - ($record->high - $pivot);
			$high_2 = $pivot + 2*($pivot - $record->low);
			$low_2 = $pivot - 2*($record->high - $pivot);
			$high_3 = $record->high + 2*($pivot-$record->low);
			$low_3 = $record->low - 2*($record->high - $pivot);

			$sells[] = array(
				"symbol" => $symbol,
				"resistance_1" => $high_1,
				"resistance_2" => $high_2,
				"resistance_3" => $high_3,
				"support_1" => $low_1,
				"support_2" => $low_2,
				"support_3" => $low_3,
				"diff_1"	=> $high_1 - $low_1,
				"diff_2"	=> $high_2 - $low_2,
				"diff_3"	=> $high_3 - $low_3,
				"close"		=> $record->close,
				"date"		=> $record->date,
				);
		

		//end pivot point
		}

		$simulations = StockRecords::where('symbol', $symbol)->where('simulated', 0)->count();
		if($simulations==0)
		{
			break;
		}
		
		StockRecords::where('id', $firstId)->update(array('simulated' => 1,));
		
	}


	

	return $sells;
}



}
