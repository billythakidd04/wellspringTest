<?php

namespace WellspringTest\src;

use Exception;
use mysqli;

class TrainLine
{
	private string $name;
	private string $id;
	private mysqli $dbConn;

	/**
	 * @throws Exception
	 */
	public function __construct(string $name)
	{
		$this->name = $name;
		$this->dbConn = DB::getConnection();
		if ($this->find()) {
			return $this;
		}
		// create new
		$this->save();
	}

	public function find(): TrainLine|false
	{
		// see if line exists
		$result = $this->dbConn->query("SELECT * FROM line WHERE name='$this->name'");
		// if no result add line as new line
		if ($result->num_rows == 0) {
			return false;
		}

		$this->id = $result->fetch_assoc()['id'];
		return $this;
	}

	public function save(int $id = null)
	{
		$action = !empty($id) ? 'UPDATE' : 'INSERT';
		if ($action == 'UPDATE') {
			$sql = $action . " line SET name='$this->name' WHERE id='" . $this->id;
		} else {
			$sql = $action . " INTO line (name) VALUES ('$this->name')";
		}
		//	run the query
		$result = $this->dbConn->query($sql);
		if ($action == 'INSERT') {
			$this->id = $this->dbConn->insert_id;
		}
	}

	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}
}