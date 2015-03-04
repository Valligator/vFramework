<?php

/**
 * Description of installHelper
 *
 * @author sbossen
 */
class installHelper {
	
	public static function install($root_un, $root_pw, $dbName=VAL_DB_NAME) {
		//select main database in case the db does not exist yet
		$mainRootPDO = vDb::getRootDb($root_un, $root_pw);
		self::installDatabase($mainRootPDO, $dbName);
		//now that db has been created, attach to it as root
		$dbRootPDO = vDb::getRootDb($root_un, $root_pw, $dbName);
		self::installTables($dbRootPDO, $dbName);
	}

	protected static function installTables(PDO $root_pdo) {
		$result = self::installChecker($root_pdo, vModuleDb::TABLE_NAME, vModuleDb::getCreateTableHelper());
		assert($result, "Could not create table: ".vModuleDb::TABLE_NAME);
	}
	
	protected static function installDatabase(PDO $root_pdo, $dbName=VAL_DB_NAME) {
		//create the database files in the regular database
		if (!vDb::databaseExists($dbName, $root_pdo)) {
			$result = vDb::createDatabase($root_pdo, $dbName);
			assert($result, "Could not create database.");
			$result = vDb::addUser($root_pdo, $dbName, VAL_DB_USER, VAL_DB_PASS);
			//this may not be an error, because user may already exist
			//assert($result, "Could not create user.");
		}
	}

	
	protected static function installChecker(PDO $pdo, $tableName, $mrSql) {
		//first check if table exists
		if (vDb::tableExists($pdo, $tableName)) {
			//table exists, so check if upgrade needed
			return true;
		} else {
			//table does not exist, create it
			$stmt = $pdo->prepare($mrSql);
			$sqlResult = $stmt->execute();
			if (!$sqlResult) {
				//log error
				vDebug::errorLog(var_export($stmt, true));
				vDebug::errorLog("Could not create table, Error: ".var_export($pdo->errorInfo(), true)."\r\nSQL: $mrSql");
				return false;
			}
			return true;
		}
	}
}
?>