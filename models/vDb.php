<?php

/**
 * This class provides convenient database access
 *
 * @author sbossen, btoffel
 */
class vDb {

	protected static $dbHandle = null;

	/**
	 * This function loads the test database into the system and is intended to be run before any function
	 * in the testing classes
	 * @return type
	 */
	public static function loadTestDb() {
		self::$dbHandle = self::getDbHelper(self::getTestDbName(), VAL_DB_USER, VAL_DB_PASS);
		return self::$dbHandle;
	}

	public static function getTestDbName() {
		return VAL_DB_NAME."_test";
	}

	/**
	 * This function is only needed if the connection to the database needs to be released
	 */
	public static function unloadDb() {
		self::$dbHandle = null;
	}

	/**
	 * Gets the PDO connection to the Valligator database
	 * @return type
	 */
	public static function getDb() {
		if (self::$dbHandle != null)
			return self::$dbHandle;

		self::$dbHandle = self::getDbHelper(VAL_DB_NAME, VAL_DB_USER, VAL_DB_PASS);
		return self::$dbHandle;
	}

	//===================-------------> Supervisory Functions <-----------===================\\
	/**
	 * This function is used to load the root connection to the database.
	 * It is to be used by the installing program only
	 * @param type $un
	 * @param type $pw
	 * @return type PDO database connection
	 */
	public static function getRootDb($un, $pw, $dbname="") {
		return self::getDbHelper($dbname, $un, $pw);
	}

	/**
	 * Note: This does not work if the database does not exist yet.
	 * @param type $dbname
	 * @param type $un
	 * @param type $pw
	 * @return type
	 */
	private static function getDbHelper($dbname, $un, $pw) {
		$dbname_str = "";
		if (!empty($dbname)) {
			$dbname_str = ';dbname='.$dbname;
		}
		$mr_PDO_string = VAL_DB_SERVER_TYPE.':host='.VAL_DB_SERVER.$dbname_str;
		self::unloadDb();
		try {
			self::$dbHandle = new PDO($mr_PDO_string, $un, $pw);
		} catch (PDOException $e) {
			vDebug::errorLog("Could not create database connection, Error: ".$e->getMessage());
		}
		return self::$dbHandle;
	}

	public static function databaseExists($dbname) {
		$pdo = self::getDbHelper("information_schema", VAL_DB_USER, VAL_DB_PASS);
		$mrSql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = :dbname;";
		$mrStmt = $pdo->prepare($mrSql);
		//protect from injection attacks
		$mrStmt->bindParam(":dbname", $dbname, PDO::PARAM_STR);

		$sqlResult = $mrStmt->execute();
		if ($sqlResult) {
			$row = $mrStmt->fetch(PDO::FETCH_NUM);
			if ($row[0]) {
				//db was found
				return true;
			} else {
				//db was not found
				return false;
			}
		} else {
			//error occured
			vDebug::errorLog("Could not check if database exists, Error: ".var_export($pdo->errorInfo(), true));
			return false;
		}
	}

	public static function createDatabase(PDO $pdo, $dbname) {
		try {
			$result = $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
			return ($result == true);
		} catch (PDOException $e) {
			vDebug::errorLog("Could not create database '$dbname', Error: ".$e->getMessage());
			return false;
		}
	}

	public static function addUserHelperSql($db, $un, $pw) {
		$mrSql = //"CREATE USER '$un'@'localhost' IDENTIFIED BY '$pw';".
				"GRANT SELECT, INSERT, UPDATE, DELETE ON `$db`.* TO '$un'@'localhost';\r\n".
				"FLUSH PRIVILEGES;";
		return $mrSql;
	}
	
	public static function addUser(PDO $pdo, $db, $un, $pw) {
		$mrSql = self::addUserHelperSql($db, $un, $pw);
		try {
			$result = $pdo->exec($mrSql);
			if (!$result) {
				vDebug::errorLog("Sql:$mrSql \r\nError: ".var_export($pdo->errorInfo(), true));
				return false;
			}
			return true;
		} catch (PDOException $e) {
			vDebug::errorLog("Could not create user '$un' in database '$db', Error: ".$e->getMessage());
			return false;
		}
	}

	/**
	 * This function checks if the table exists in the passed PDO database connection
	 * @param PDO $pdo - connection to PDO database table
	 * @param type $tableName 
	 * @return boolean - true if table was found, false if not
	 */
	public static function tableExists(PDO $pdo, $tableName) {
		$mrSql = "SHOW TABLES LIKE :table_name";
		$mrStmt = $pdo->prepare($mrSql);
		//protect from injection attacks
		$mrStmt->bindParam(":table_name", $tableName, PDO::PARAM_STR);

		$sqlResult = $mrStmt->execute();
		if ($sqlResult) {
			$row = $mrStmt->fetch(PDO::FETCH_NUM);
			if ($row[0]) {
				//table was found
				return true;
			} else {
				//table was not found
				return false;
			}
		} else {
			//table was not found
			vDebug::errorLog("Could not check if table exists, Error: ".var_export($pdo->errorInfo(), true));
			return false;
		}
	}

}

?>