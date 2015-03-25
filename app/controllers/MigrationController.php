<?php

class MigrationController extends BaseController {


public function migrate()
{
	
	ini_set('max_execution_time', 12000);


        foreach(Input::file('file') as $file)
        {


        	$name = time() . '-' . $file->getClientOriginalName();
        	$path = public_path() . '/csv';
        	$file->move($path, $name);
 			$csvFile = $path.'/'.$name;
		
			$csvData = file_get_contents($csvFile);
			$lines = explode(PHP_EOL, $csvData);
			$array = array();

			if (File::extension($csvFile) != 'csv')
			{
			unlink($csvFile);
			Session::put('error_message', 'Invalid file upload.');
			return Redirect::back();
			}

			foreach ($lines as $line) 
			{

    		$array[] = str_getcsv($line);		
			}
		
			$array_max = count($array)-1;

			$failed_row = 0;
			$success_row = 0;
			$failed_array = array();


			$template = array('symbol', 'as_of', 'open', 'high', 'low', 'close', 'volume', 'nfbs');

			foreach ($array as $array_index => $arrays) 
			{

			if($array_index == $array_max)
				break;
			
			$history = new StockRecords;	

			$symbol_check = StockInfo::where('symbol', $arrays[0])->count();

			if($symbol_check==0)
			{
				$symbol_new = new StockInfo;
				$symbol_new->symbol = $arrays[0];
				$symbol_new->save();
			}

			$history->symbol = $arrays[0];

			$as_of = date( 'Y-m-d', strtotime($arrays[1]));

			$history->date = $as_of;

			$history->open = $arrays[2];

			$history->high = $arrays[3];

			$history->low = $arrays[4];

			$history->close = $arrays[5];

			$history->volume = $arrays[6];

			$history->nfbs = $arrays[7];

			$trend = "STABLE";

			if($arrays[2]>$arrays[5])
			{	
				$trend = "DOWNWARD";
			}
			elseif($arrays[2]<$arrays[5])
			{	
				$trend = "UPWARD";
			}
			$history->trend = $trend;

			$history->save();

			}

			unlink($csvFile);

			Session::put('success_message', 'Successfully imported  csv.');
						
		}

		return Redirect::back();
	
	}


}
