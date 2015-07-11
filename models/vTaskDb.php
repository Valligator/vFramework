<?php

require_once("vTask.php");

/**
 * This class is for information about a task to be executed.  It contains all the data needed
 * for when to execute the task and where to find the data needed for input parameters
 */
class vTaskDb {

	const TABLE_NAME = "task";
	const TASK_ID = "task_id";
	const TASK_STATUS = "task_status";
	const MODULE_REF = "module_ref";
	const TIME_CREATED = "time_created";
	const TIME_TO_EXECUTE = "time_to_execute";
	const TIME_START = "time_start";
	const TIME_COMPLETED = "time_completed";
	const RESULT_MODULE_CODE = "result_module_code";
	const RESULT_MODULE_MSG = "result_module_msg";
	const RESULT_EXECUTION_LEVEL = "result_execution_level";
	const RESULT_EXECUTION_CODE = "result_execution_code";
	const RESULT_EXECUTION_MSG = "result_execution_msg";
	const MODULE_STARTFILE = "module_startfile";

	const MODULE_STARTFILE_LENGTH = 30;
	


	public static function getCreateTableHelper() {
		$str = "CREATE TABLE `".self::TABLE_NAME."` (
			  `".self::TASK_ID."` int(11) NOT NULL AUTO_INCREMENT,
			  `".self::TASK_STATUS."` int DEFAULT 0,
			  `".self::MODULE_REF."` int(11) NOT NULL DEFAULT '0',
			  `".self::TIME_CREATED."` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			  `".self::TIME_TO_EXECUTE."` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			  `".self::TIME_START."` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			  `".self::TIME_COMPLETED."` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			  `".self::RESULT_MODULE_CODE."` int(11) NOT NULL DEFAULT '0',
			  `".self::RESULT_MODULE_MSG."` text NOT NULL,
			  `".self::RESULT_EXECUTION_LEVEL."` int(11) NOT NULL DEFAULT '0',
			  `".self::RESULT_EXECUTION_CODE."` int(11) NOT NULL DEFAULT '0',
			  `".self::RESULT_EXECUTION_MSG."` text NOT NULL,
			  `".self::MODULE_STARTFILE."` varchar(".self::MODULE_STARTFILE_LENGTH.") NOT NULL DEFAULT '',
			  PRIMARY KEY (`".self::TASK_ID."`),
			  KEY `".self::TASK_STATUS."` (`".self::TASK_STATUS."`),
			  KEY `".self::MODULE_REF."` (`".self::MODULE_REF."`),
			  KEY `".self::TIME_COMPLETED."` (`".self::TIME_COMPLETED."`)
			) ENGINE=".VAL_DB_ENGINE." DEFAULT CHARSET=".VAL_DB_CHARSET." COLLATE=".VAL_DB_COLLATE.";";
		return $str;
	}

	/**
	 * This function saves the passed object to the database
	 * @param vTask $object
	 * @return false if failed, or last_insert_id if success
	 */
	public static function insert(vTask $object) {
		$pdo = vDb::getDb();
		$mrSql = 'INSERT INTO '.self::TABLE_NAME.' ( '.
				self::TASK_STATUS.', '.
				self::MODULE_REF.', '.
				self::TIME_CREATED.', '.
				self::TIME_TO_EXECUTE.', '.
				self::TIME_START.', '.
				self::TIME_COMPLETED.', '.
				self::RESULT_MODULE_CODE.', '.
				self::RESULT_MODULE_MSG.', '.
				self::RESULT_EXECUTION_LEVEL.', '.
				self::RESULT_EXECUTION_CODE.', '.
				self::RESULT_EXECUTION_MSG.', '.
				self::MODULE_STARTFILE.''.
				') VALUES (  '.
				' :'.self::TASK_STATUS.', '.
				' :'.self::MODULE_REF.', '.
				' :'.self::TIME_CREATED.', '.
				' :'.self::TIME_TO_EXECUTE.', '.
				' :'.self::TIME_START.', '.
				' :'.self::TIME_COMPLETED.', '.
				' :'.self::RESULT_MODULE_CODE.', '.
				' :'.self::RESULT_MODULE_MSG.', '.
				' :'.self::RESULT_EXECUTION_LEVEL.', '.
				' :'.self::RESULT_EXECUTION_CODE.', '.
				' :'.self::RESULT_EXECUTION_MSG.', '.
				' :'.self::MODULE_STARTFILE.')';
		$stmt = $pdo->prepare($mrSql);

		$stmt->bindParam(":".self::TASK_STATUS, $object->task_status, PDO::PARAM_INT);
		$stmt->bindParam(":".self::MODULE_REF, $object->module_ref, PDO::PARAM_INT);
		$stmt->bindParam(":".self::TIME_CREATED, $object->time_created, PDO::PARAM_STR);
		$stmt->bindParam(":".self::TIME_TO_EXECUTE, $object->time_to_execute, PDO::PARAM_STR);
		$stmt->bindParam(":".self::TIME_START, $object->time_start, PDO::PARAM_STR);
		$stmt->bindParam(":".self::TIME_COMPLETED, $object->time_completed, PDO::PARAM_STR);
		$stmt->bindParam(":".self::RESULT_MODULE_CODE, $object->result_module_code, PDO::PARAM_INT);
		$stmt->bindParam(":".self::RESULT_MODULE_MSG, $object->result_module_msg, PDO::PARAM_STR);
		$stmt->bindParam(":".self::RESULT_EXECUTION_LEVEL, $object->result_execution_level, PDO::PARAM_INT);
		$stmt->bindParam(":".self::RESULT_EXECUTION_CODE, $object->result_execution_code, PDO::PARAM_INT);
		$stmt->bindParam(":".self::RESULT_EXECUTION_MSG, $object->result_execution_msg, PDO::PARAM_STR);
		$stmt->bindParam(":".self::MODULE_STARTFILE, $object->module_startfile, PDO::PARAM_STR);

		$sqlResult = $stmt->execute();
		if ($sqlResult) {
			vDebug::debugLog("Last insert id is {$pdo->lastInsertId()}");
			return $pdo->lastInsertId();
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
	 * @param vTask $config
	 * @return false if failed, or last_insert_id if success
	 */
	public static function update(vTask $object) {
		$pdo = vDb::getDb();
		$mrSql = 'UPDATE '.self::TABLE_NAME.' SET '.
				self::TASK_STATUS.' :'.self::TASK_STATUS.', '.
				self::MODULE_REF.' :'.self::MODULE_REF.', '.
				self::TIME_CREATED.' :'.self::TIME_CREATED.', '.
				self::TIME_TO_EXECUTE.' :'.self::TIME_TO_EXECUTE.', '.
				self::TIME_START.' :'.self::TIME_START.', '.
				self::TIME_COMPLETED.' :'.self::TIME_COMPLETED.', '.
				self::RESULT_MODULE_CODE.' :'.self::RESULT_MODULE_CODE.', '.
				self::RESULT_MODULE_MSG.' :'.self::RESULT_MODULE_MSG.', '.
				self::RESULT_EXECUTION_LEVEL.' :'.self::RESULT_EXECUTION_LEVEL.', '.
				self::RESULT_EXECUTION_CODE.' :'.self::RESULT_EXECUTION_CODE.', '.
				self::RESULT_EXECUTION_MSG.' :'.self::RESULT_EXECUTION_MSG.', '.
				self::MODULE_STARTFILE.' :'.self::MODULE_STARTFILE.
				' WHERE '.self::TASK_ID.' = :'.self::TASK_ID;
		$stmt = $pdo->prepare($mrSql);
		
		$stmt->bindParam(":".self::TASK_ID, $object->task_id, PDO::PARAM_INT);
		$stmt->bindParam(":".self::TASK_STATUS, $object->task_status, PDO::PARAM_INT);
		$stmt->bindParam(":".self::MODULE_REF, $object->module_ref, PDO::PARAM_INT);
		$stmt->bindParam(":".self::TIME_CREATED, $object->time_created, PDO::PARAM_STR);
		$stmt->bindParam(":".self::TIME_TO_EXECUTE, $object->time_to_execute, PDO::PARAM_STR);
		$stmt->bindParam(":".self::TIME_START, $object->time_start, PDO::PARAM_STR);
		$stmt->bindParam(":".self::TIME_COMPLETED, $object->time_completed, PDO::PARAM_STR);
		$stmt->bindParam(":".self::RESULT_MODULE_CODE, $object->result_module_code, PDO::PARAM_INT);
		$stmt->bindParam(":".self::RESULT_MODULE_MSG, $object->result_module_msg, PDO::PARAM_STR);
		$stmt->bindParam(":".self::RESULT_EXECUTION_LEVEL, $object->result_execution_level, PDO::PARAM_INT);
		$stmt->bindParam(":".self::RESULT_EXECUTION_CODE, $object->result_execution_code, PDO::PARAM_INT);
		$stmt->bindParam(":".self::RESULT_EXECUTION_MSG, $object->result_execution_msg, PDO::PARAM_STR);
		$stmt->bindParam(":".self::MODULE_STARTFILE, $object->module_startfile, PDO::PARAM_STR);

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
	 * This function retrieves an individual task for the given id
	 * @param type $task_id - the unique key for that task
	 * @return vTask
	 */
	public static function getTask($task_id) {
		$pdo = vDb::getDb();
		$stmt = $pdo->prepare('SELECT * FROM '.self::TABLE_NAME.' WHERE '.self::TASK_ID.' = :'.self::TASK_ID);
		$stmt->bindParam(':'.self::TASK_ID, $task_id, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return self::createTask($result);
	}
	

	
	/**
	 * This function retrieves all the task objects in the system
	 * Intended to be used for testing purposes
	 * @return vTask array
	 */
	public static function getTasks() {
		$pdo = vDb::getDb();
		$stmt = $pdo->prepare('SELECT * FROM '.self::TABLE_NAME);
		$stmt->execute();
		$arr = array();
		while( $result = $stmt->fetch(PDO::FETCH_ASSOC) ) {
			$arr[] = self::createTask($result);
		}
		return $arr;
	}

	/**
	 * This function removes the specified object from the database by id
	 * @param type $task_id
	 * @return type
	 */
	public static function delete($task_id) {
		$pdo = vDb::getDb();
		$stmt = $pdo->prepare('DELETE FROM '.self::TABLE_NAME.' WHERE '.self::TASK_ID.' = :'.self::TASK_ID);
		$stmt->bindParam(':'.self::TASK_ID, $task_id, PDO::PARAM_INT);
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
	 * This function removes all the tasks in the system
	 * to be used for testing purposes
	 * @param type $mod_ref
	 * @return type
	 */
	public static function wipeTasks() {
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
	 * @return \vTask
	 */
	public static function createTask($fetch_arr = null) {
		$object = new vTask();
		//with vUtils::setEntryIfExists
		vUtils::setEntryIfExists($fetch_arr, self::TASK_ID, $object->task_id);
		vUtils::setEntryIfExists($fetch_arr, self::TASK_STATUS, $object->task_status);
		vUtils::setEntryIfExists($fetch_arr, self::MODULE_REF, $object->module_ref);
		vUtils::setEntryIfExists($fetch_arr, self::TIME_CREATED, $object->time_created);
		vUtils::setEntryIfExists($fetch_arr, self::TIME_TO_EXECUTE, $object->time_to_execute);
		vUtils::setEntryIfExists($fetch_arr, self::TIME_START, $object->time_start);
		vUtils::setEntryIfExists($fetch_arr, self::TIME_COMPLETED, $object->time_completed);
		vUtils::setEntryIfExists($fetch_arr, self::RESULT_MODULE_CODE, $object->result_module_code);
		vUtils::setEntryIfExists($fetch_arr, self::RESULT_MODULE_MSG, $object->result_module_msg);
		vUtils::setEntryIfExists($fetch_arr, self::RESULT_EXECUTION_LEVEL, $object->result_execution_level);
		vUtils::setEntryIfExists($fetch_arr, self::RESULT_EXECUTION_CODE, $object->result_execution_code);
		vUtils::setEntryIfExists($fetch_arr, self::RESULT_EXECUTION_MSG, $object->result_execution_msg);
		vUtils::setEntryIfExists($fetch_arr, self::MODULE_STARTFILE, $object->module_startfile);

		return $object;
	}

}
