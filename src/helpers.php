<?php

namespace WellspringTest;

class Helpers
{
	// php function to convert csv to json format
	public static function csvToJson($fileName)
	{
		// open csv file
		if (!($fh = fopen($fileName, 'r'))) {
			throw new \Exception('invalid csv file provided');
		}

		//read csv headers
		$keys = fgetcsv($fh);

		// parse csv rows into array
		$json = [];
		while ($row = fgetcsv($fh)) {
			$json[] = array_combine($keys, $row);
		}

		// release file handle
		fclose($fh);

		// encode array to json
		return json_encode($json);
	}
}
