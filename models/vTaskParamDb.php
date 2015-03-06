<?php

require_once("vTaskParam.php");

/**
 * This class is for information about a task to be executed.  It contains all the data needed
 * for when to execute the task and where to find the data needed for input parameters
 */
class vTaskParamDb {

	const TABLE_NAME = "task_param";
	const TASK_REF = "task_ref";
	const PARAM_KEYNAME = "param_keyname";
	const MODULE_REF = "module_ref";
	const PARAM_DISPLAYNAME = "param_displayname";
	const PARAM_TYPE = "param_type";
	const PARAM_VALUE = "param_value";

	const PARAM_KEYNAME_LENGTH = 60;
	const PARAM_DISPLAYNAME_LENGTH = 100;
	const PARAM_TYPE_LENGTH = 16;

	public static function getCreateTableHelper() {
		$str = "CREATE TABLE `".self::TABLE_NAME."` (
			  `".self::TASK_REF."` int(11) NOT NULL DEFAULT '0',
			  `".self::PARAM_KEYNAME."` varchar(".self::PARAM_KEYNAME_LENGTH.") NOT NULL DEFAULT '',
			  `".self::MODULE_REF."` int(11) NOT NULL DEFAULT '0',
			  `".self::PARAM_DISPLAYNAME."` varchar(".self::PARAM_DISPLAYNAME_LENGTH.") NOT NULL DEFAULT '',
			  `".self::PARAM_TYPE."` varchar(".self::PARAM_TYPE_LENGTH.") NOT NULL DEFAULT '',
			  `".self::PARAM_VALUE."` text NOT NULL,
			  PRIMARY KEY (`".self::TASK_REF."`, `".self::PARAM_KEYNAME."`),
			  KEY `".self::MODULE_REF."` (`".self::MODULE_REF."`)
			) ENGINE=".VAL_DB_ENGINE." DEFAULT CHARSET=".VAL_DB_CHARSET." COLLATE=".VAL_DB_COLLATE.";";
		return $str;
	}

	/**
	 * This function saves the passed object to the database
	 * @param vTaskParam $object
	 * @return false if failed, or last_insert_id if success
	 */
	public static function insert(vTaskParam $object) {
		$pdo = vDb::getDb();
		$mrSql = 'INSERT INTO '.self::TABLE_NAME.' ( '.
				self::TASK_REF.', '.
				self::PARAM_KEYNAME.', '.
				self::MODULE_REF.', '.
				self::PARAM_DISPLAYNAME.', '.
				self::PARAM_TYPE.', '.
				self::PARAM_VALUE.''.
				') VALUES (  '.
				' :'.self::TASK_REF.', '.
				' :'.self::PARAM_KEYNAME.', '.
				' :'.self::MODULE_REF.', '.
				' :'.self::PARAM_DISPLAYNAME.', '.
				' :'.self::PARAM_TYPE.', '.
				' :'.self::PARAM_VALUE.')';
		$stmt = $pdo->prepare($mrSql);

		$stmt->bindParam(":".self::TASK_REF, $object->task_ref, PDO::PARAM_INT);
		$stmt->bindParam(":".self::PARAM_KEYNAME, $object->param_keyname, PDO::PARAM_STR);
		$stmt->bindParam(":".self::MODULE_REF, $object->module_ref, PDO::PARAM_INT);
		$stmt->bindParam(":".self::PARAM_DISPLAYNAME, $object->param_displayname, PDO::PARAM_STR);
		$stmt->bindParam(":".self::PARAM_TYPE, $object->param_type, PDO::PARAM_STR);
		$stmt->bindParam(":".self::PARAM_VALUE, $object->param_value, PDO::PARAM_STR);

		$sqlResult = $stmt->execute();
		if ($sqlResult) {
			vDebug::debugLog("Last insert id is {$pdo->lastInsertId()}");
			//send true if we think this was successful (b/c last_insert_id will be "0" on success with no auto_increment key
			if (strlen($pdo->lastInsertId()) > 0){
				return true;
			} else {
				return $pdo->lastInsertId();
			}
		} else {
			//log error
			vDebug::errorLog(var_export($stmt, true));
			vDebug::errorLog("Could not save to database getting result=".var_export($sqlResult, true).". Error: ".var_export($pdo->errorInfo(), true)."\r\nSQL: $mrSql");
		}
		return false;
	}

	/**
	 * This function saves the passed object to the database
	 * NOTE: $module_ref & $param_keyname cannot be changed here since they are primary keys
	 * to change that, the module config item must be deleted and recreated
	 * @param vTaskParam $config
	 * @return false if failed, or last_insert_id if success
	 */
	public static function update(vTaskParam $object) {
		$pdo = vDb::getDb();
		$mrSql = 'UPDATE '.self::TABLE_NAME.' SET '.
				self::TASK_REF.' :'.self::TASK_REF.', '.
				self::PARAM_KEYNAME.' :'.self::PARAM_KEYNAME.', '.
				self::MODULE_REF.' :'.self::MODULE_REF.', '.
				self::PARAM_DISPLAYNAME.' :'.self::PARAM_DISPLAYNAME.', '.
				self::PARAM_TYPE.' :'.self::PARAM_TYPE.', '.
				self::PARAM_VALUE.' :'.self::PARAM_VALUE.
				' WHERE '.self::TASK_ID.' = :'.self::TASK_ID;
		$stmt = $pdo->prepare($mrSql);
		
		$stmt->bindParam(":".self::TASK_REF, $object->task_ref, PDO::PARAM_INT);
		$stmt->bindParam(":".self::PARAM_KEYNAME, $object->param_keyname, PDO::PARAM_STR);
		$stmt->bindParam(":".self::MODULE_REF, $object->module_ref, PDO::PARAM_INT);
		$stmt->bindParam(":".self::PARAM_DISPLAYNAME, $object->param_displayname, PDO::PARAM_STR);
		$stmt->bindParam(":".self::PARAM_TYPE, $object->param_type, PDO::PARAM_STR);
		$stmt->bindParam(":".self::PARAM_VALUE, $object->param_value, PDO::PARAM_STR);

		$sqlResult = $stmt->execute();
		if ($sqlResult) {
			return true;
		} else {
			//log error
			vDebug::errorLog(var_export($stmt, true));
			vDebug::errorLog(__CLASS__." could not update to database getting result=".var_export($sqlResult, true).". Error: ".var_export($pdo->errorInfo(), true)."\r\nSQL: $mrSql");
		}
		return false;
	}

	/**
	 * This function retrieves an individual task parameter for the given task id and keyname
	 * @param type $task_ref
	 * @param type $key
	 * @return vTaskParam
	 */
	public static function getTaskParam($task_ref, $key) {
		$pdo = vDb::getDb();
		$stmt = $pdo->prepare('SELECT * FROM '.self::TABLE_NAME.' WHERE '.self::TASK_REF.' = :'.self::TASK_REF.
				' AND '.self::PARAM_KEYNAME.' = :'.self::PARAM_KEYNAME);
		$stmt->bindParam(':'.self::TASK_REF, $task_ref, PDO::PARAM_INT);
		$stmt->bindParam(':'.self::PARAM_KEYNAME, $key, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return self::createTaskParam($result);
	}
	
	/**
	 * This function retrieves all the task parameters objects for the given task id
	 * @return vTaskParam array
	 */
	public static function getTaskParams($task_ref) {
		$pdo = vDb::getDb();
		$stmt = $pdo->prepare('SELECT * FROM '.self::TABLE_NAME.' WHERE '.self::TASK_REF.' = :'.self::TASK_REF);
		$stmt->bindParam(':'.self::TASK_REF, $task_ref, PDO::PARAM_INT);
		$stmt->execute();
		$arr = array();
		while( $result = $stmt->fetch(PDO::FETCH_ASSOC) ) {
			$arr[] = self::createTaskParam($result);
		}
		return $arr;
	}

	/**
	 * This function retrieves all the task parameters in the system
	 * @return vTaskParam array
	 */
	public static function getAllTaskParams() {
		$pdo = vDb::getDb();
		$stmt = $pdo->prepare('SELECT * FROM '.self::TABLE_NAME);
		$stmt->execute();
		$arr = array();
		while( $result = $stmt->fetch(PDO::FETCH_ASSOC) ) {
			$arr[] = self::createTaskParam($result);
		}
		return $arr;
	}

	/**
	 * This function removes the specified object from the database by id
	 * @param type $task_ref
	 * @param type $key
	 * @return type
	 */
	public static function delete($task_ref, $key) {
		$pdo = vDb::getDb();
		$stmt = $pdo->prepare('DELETE FROM '.self::TABLE_NAME.' WHERE '.self::TASK_REF.' = :'.self::TASK_REF.
				' AND '.self::PARAM_KEYNAME.' = :'.self::PARAM_KEYNAME);
		$stmt->bindParam(':'.self::TASK_REF, $task_ref, PDO::PARAM_INT);
		$stmt->bindParam(':'.self::PARAM_KEYNAME, $key, PDO::PARAM_STR);
		$sqlResult = $stmt->execute();

		if ($sqlResult) {
			return true;
		} else {
			//log error
			vDebug::errorLog(var_export($stmt, true));
			vDebug::errorLog(__CLASS__." could not update to database getting result=".var_export($sqlResult, true).". Error: ".var_export($pdo->errorInfo(), true)."\r\nSQL: $mrSql");
		}
		return false;
	}


	/**
	 * This function removes the parameters for a given task, in case it is removed for example
	 * @param type $task_ref
	 * @return type
	 */
	public static function wipeTaskParams($task_ref) {
		$pdo = vDb::getDb();
		$stmt = $pdo->prepare('DELETE FROM '.self::TABLE_NAME.' WHERE '.self::TASK_REF.' = :'.self::TASK_REF);
		$stmt->bindParam(':'.self::TASK_REF, $task_ref, PDO::PARAM_INT);
		$sqlResult = $stmt->execute();

		if ($sqlResult) {
			return true;
		} else {
			//log error
			vDebug::errorLog(var_export($stmt, true));
			vDebug::errorLog(__CLASS__." could not update to database getting result=".var_export($sqlResult, true).". Error: ".var_export($pdo->errorInfo(), true)."\r\nSQL: $mrSql");
		}
		return false;
	}

	/**
	 * This function removes all the task parameters in the system
	 * to be used for testing purposes
	 * @return type
	 */
	public static function wipeAllParams() {
		$pdo = vDb::getDb();
		$mrSql = 'DELETE FROM '.self::TABLE_NAME;
		$stmt = $pdo->prepare($mrSql);
		$sqlResult = $stmt->execute();

		if ($sqlResult) {
			return true;
		} else {
			//log error
			vDebug::errorLog(var_export($stmt, true));
			vDebug::errorLog(__CLASS__." could not update to database getting result=".var_export($sqlResult, true).". Error: ".var_export($pdo->errorInfo(), true)."\r\nSQL: $mrSql");
		}
		return false;
	}

	/**
	 * Factory method for creating the object
	 * @param type $fetch_arr
	 * @return \vTaskParam
	 */
	public static function createTaskParam($fetch_arr = null) {
		$object = new vTaskParam();
		//with vUtils::setEntryIfExists
		vUtils::setEntryIfExists($fetch_arr, self::TASK_REF, $object->task_ref);
		vUtils::setEntryIfExists($fetch_arr, self::PARAM_KEYNAME, $object->param_keyname);
		vUtils::setEntryIfExists($fetch_arr, self::MODULE_REF, $object->module_ref);
		vUtils::setEntryIfExists($fetch_arr, self::PARAM_DISPLAYNAME, $object->param_displayname);
		vUtils::setEntryIfExists($fetch_arr, self::PARAM_TYPE, $object->param_type);
		vUtils::setEntryIfExists($fetch_arr, self::PARAM_VALUE, $object->param_value);

		return $object;
	}

}
