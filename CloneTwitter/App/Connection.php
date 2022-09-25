<?php

namespace App;

class Connection {

	public static function getDb() {
		try {
			
			$conn = new \PDO(
				"mysql:host=localhost;dbname=Twitter;charset=utf8",
				"admin",
				"admin" 
			);

			return $conn;

		} catch (\PDOException $e) {
			//.. tratar de alguma forma ..//
		}
	}
}

?>