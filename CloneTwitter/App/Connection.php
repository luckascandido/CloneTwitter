<?php

namespace App;

class Connection {

	public static function getDb() {
		try {
			
			$conn = new \PDO(
				"mysql:host=localhost;dbname=id19640359_clonetwitter;charset=utf8",
				"id19640359_admin",
				"=&hZbe3!DYF2)(5y" 
			);

			return $conn;

		} catch (\PDOException $e) {
			//.. tratar de alguma forma ..//
		}
	}
}

?>