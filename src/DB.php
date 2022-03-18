<?php

namespace WellspringTest\src;

use Exception;
use mysqli;

class DB
{
	private static string $servername = "localhost";
	private static string $username = "wellspring";
	private static string $password = "interview";
	private static string $database = "trains";
	private static mysqli $conn;

	/**
	 * @throws Exception
	 */
	public static function getConnection(): mysqli
	{
		if (empty($conn)) {
			// Create connection
			self::$conn = new mysqli(self::$servername, self::$username, self::$password, self::$database);

			// Check connection
			if (self::$conn->connect_error || !self::$conn) {
				throw new Exception("Connection failed: " . self::$conn->connect_error ?? 'unknown');
			}
		}
		return self::$conn;
	}
}