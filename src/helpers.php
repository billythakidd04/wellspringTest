<?php

namespace WellspringTest\src;

use Exception;

class Helpers
{
	// php function to convert csv to json format
	/**
	 * @throws Exception
	 */
	public static function csvToJson($fileName)
	{
		// open csv file
		if (!($fh = fopen($fileName, 'r'))) {
			throw new \Exception('invalid csv file provided');
		}

		//read csv headers and strip white space
		$keys = array_map('trim', fgetcsv($fh));

		// parse csv rows into array
		$json = [];
		while ($row = fgetcsv($fh)) {
			$json[] = array_combine($keys, array_map('trim', $row));
		}

		fclose($fh);

		// encode array to json
		return json_encode($json);
	}
}
