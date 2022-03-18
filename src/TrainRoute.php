<?php

namespace WellspringTest\src;

use Exception;
use mysqli;

class TrainRoute
{
	private string $id;
	private string $name;
	private TrainLine $line;
	private mysqli $dbConn;

	/**
	 * @throws Exception
	 */
	public function __construct(string $name, TrainLine $trainLine)
	{
		$this->name = $name;
		$this->line = $trainLine;
		$this->dbConn = DB::getConnection();

		if ($this->find()) {
			return $this;
		}
		// create new
		$this->save();
	}

	public function find(): TrainRoute|false
	{
		// see if route exists
		$result = $this->dbConn->query("SELECT * FROM routes WHERE name='" . $this->name . "' AND line_id=" . $this->line->getId());
		// if no result add route as new route
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
			$sql = $action . ' routes SET name=' . $this . $this->name . ' line_id=' . $this->line->getId() . ' WHERE id=' . $this->id;
		} else {
			$sql = $action . " INTO routes (name, line_id) VALUES ('$this->name','" . $this->line->getId() . "')";
		}
		//	run the query
		$this->dbConn->query($sql);
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