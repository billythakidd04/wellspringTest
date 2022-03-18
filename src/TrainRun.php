<?php

namespace WellspringTest\src;

use Exception;
use mysqli;

class TrainRun
{
	private string $id;
	private string $name;
	private string $operator;
	private TrainRoute $route;
	private mysqli $dbConn;

	/**
	 * @throws Exception
	 */
	public function __construct(string $name, string $operator, TrainRoute $route)
	{
		$this->name = $name;
		$this->operator = $operator;
		$this->route = $route;
		$this->dbConn = DB::getConnection();

		if ($this->find()) {
			return $this;
		}
		// create new
		$this->save();
	}

	public function find(): TrainRun|false
	{
		// see if run exists
		$result = $this->dbConn->query("SELECT * FROM runs WHERE name='" . $this->name . "' AND route_id=" . $this->route->getId());
		// if no result add
		if ($result->num_rows == 0) {
			return false;
		}
		$this->id = $result->fetch_assoc()['id'];
		return $this;
	}

	public function save(int $id = null)
	{
		//Every update of a route needs to create a new insert into the route_operator table
		$action = !empty($id) ? 'UPDATE' : 'INSERT';
		if ($action == 'UPDATE') {
			$sql = $action . ' runs SET name=' . $this . $this->name . ' route_id=' . $this->route->getId() . ' WHERE id=' . $this->id;
		} else {
			$sql = $action . " INTO runs (name, route_id) VALUES ('$this->name','" . $this->route->getId() . "')";
		}
		$opSql = "INSERT INTO route_operator (route_id, operator_id) VALUES ('" . $this->route->getId() . "','" . $this->operator . "')";
		//	run the query
		$this->dbConn->query($sql);
		if ($action == 'INSERT') {
			$this->id = $this->dbConn->insert_id;
		}
	}

	/**
	 * @return string
	 */
	public function getOperator(): string
	{
		if (empty($this->operator)) {
			// Only get the most recent update to this route
			$sql = "SELECT operator_id FROM route_operator WHERE route_id='" . $this->id . "' ORDER BY updatedAt DESC LIMIT 1";
			$result = $this->dbConn->query($sql);
			if ($result->num_rows == 1) {
				$this->operator = $result->fetch_assoc()['operator_id'];
			}
		}
		return $this->operator;
	}

	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}

}