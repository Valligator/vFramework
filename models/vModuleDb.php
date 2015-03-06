<?php

require_once("vModule.php");

/**
 * This class access the db to retrieve information about the Valligator modules
 * 
 * INSERT:
 * Insert should return last_insert_id when successful and false when it fails
 * error can be retrieved using $pdo->errorInfo()
 * 
 * DELETE:
 * Delete should return true when successful and false when it fails
 * error can be retrieved using $pdo->errorInfo()
 * 
 * UPDATE:
 * Delete should return true when successful and false when it fails
 * error can be retrieved using $pdo->errorInfo()
 * 
 * READ:
 * Read returns an object filled in with data from the db - or a new instance if not found
 * error can be retrieved using $pdo->errorInfo()
 */
class vModuleDb {

	const TABLE_NAME = "module";
	const MOD_ID = "mod_id";
	const MOD_NAME = "mod_name";
	const MOD_STARTFILE = "mod_startfile";
	const MOD_ENABLED = "mod_enabled";
	const MOD_TIME_CREATED = "mod_time_created";
	const MOD_TIME_LASTRUN = "mod_time_lastrun";

	const MOD_NAME_LENGTH = 128;
	const MOD_STARTFILE_LENGTH = 30;

	public static function getCreateTableHelper() {
		$str = "CREATE TABLE `".self::TABLE_NAME."` (
			  `".self::MOD_ID."` int(11) NOT NULL AUTO_INCREMENT,
			  `".self::MOD_NAME."` varchar(".self::MOD_NAME_LENGTH.") NOT NULL DEFAULT '',
			  `".self::MOD_STARTFILE."` varchar(".self::MOD_STARTFILE_LENGTH.") NOT NULL DEFAULT '',
			  `".self::MOD_ENABLED."` tinyint(1) NOT NULL DEFAULT '0',
			  `".self::MOD_TIME_CREATED."` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			  `".self::MOD_TIME_LASTRUN."` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			  PRIMARY KEY (`".self::MOD_ID."`),
			  KEY `".self::MOD_ENABLED."` (`".self::MOD_ENABLED."`)
			) ENGINE=".VAL_DB_ENGINE." DEFAULT CHARSET=".VAL_DB_CHARSET." COLLATE=".VAL_DB_COLLATE.";";
		return $str;
	}

	/**
	 * This function saves the passed object to the database
	 * @param vModule $mod
	 * @return false if failed, or last_insert_id if success
	 */
	public static function insert(vModule $mod) {
		$pdo = vDb::getDb();
		$mrSql = 'INSERT INTO '.self::TABLE_NAME.' ( '.
				self::MOD_NAME.', '.
				self::MOD_STARTFILE.', '.
				self::MOD_ENABLED.', '.
				self::MOD_TIME_CREATED.', '.
				self::MOD_TIME_LASTRUN.')'.
				' VALUES (  '.
				' :'.self::MOD_NAME.', '.
				' :'.self::MOD_STARTFILE.', '.
				' :'.self::MOD_ENABLED.', '.
				' :'.self::MOD_TIME_CREATED.', '.
				' :'.self::MOD_TIME_LASTRUN.')';
		$stmt = $pdo->prepare($mrSql);

		$stmt->bindParam(":".self::MOD_NAME, $mod->name, PDO::PARAM_STR);
		$stmt->bindParam(":".self::MOD_STARTFILE, $mod->startfile, PDO::PARAM_STR);
		$stmt->bindParam(":".self::MOD_ENABLED, $mod->enabled, PDO::PARAM_INT);
		$stmt->bindParam(":".self::MOD_TIME_CREATED, $mod->time_created, PDO::PARAM_STR);
		$stmt->bindParam(":".self::MOD_TIME_LASTRUN, $mod->time_lastrun, PDO::PARAM_STR);

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
	 * @param vModule $mod
	 * @return false if failed, or last_insert_id if success
	 */
	public static function update(vModule $mod) {
		$pdo = vDb::getDb();
		$mrSql = 'UPDATE '.self::TABLE_NAME.' SET '.
				self::MOD_NAME.' :'.self::MOD_NAME.', '.
				self::MOD_STARTFILE.' :'.self::MOD_STARTFILE.', '.
				self::MOD_ENABLED.' :'.self::MOD_ENABLED.', '.
				self::MOD_TIME_LASTRUN.' :'.self::MOD_TIME_LASTRUN.
				' WHERE '.self::MOD_ID.' = :'.self::MOD_ID;
		$stmt = $pdo->prepare($mrSql);

		$stmt->bindParam(":".self::MOD_ID, $mod->id, PDO::PARAM_INT);
		$stmt->bindParam(":".self::MOD_NAME, $mod->name, PDO::PARAM_STR);
		$stmt->bindParam(":".self::MOD_STARTFILE, $mod->startfile, PDO::PARAM_STR);
		$stmt->bindParam(":".self::MOD_ENABLED, $mod->enabled, PDO::PARAM_INT);
		$stmt->bindParam(":".self::MOD_TIME_LASTRUN, $mod->time_lastrun, PDO::PARAM_STR);

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
	 * This function retrieves the specified object from the database by id
	 * @param type $id
	 * @return type
	 */
	public static function getModule($id) {
		$pdo = vDb::getDb();
		$stmt = $pdo->prepare('SELECT * FROM '.self::TABLE_NAME.' WHERE '.self::MOD_ID.' = :'.self::MOD_ID);
		$stmt->bindParam(':'.self::MOD_ID, $id, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return self::createModule($result);
	}
	
	/**
	 * This function retrieves the modules from the database
	 * @param type $id
	 * @return type
	 */
	public static function getModules() {
		$pdo = vDb::getDb();
		$stmt = $pdo->prepare('SELECT * FROM '.self::TABLE_NAME);
		$stmt->execute();
		$arr = array();
		while( $result = $stmt->fetch(PDO::FETCH_ASSOC) ) {
			$arr[] = self::createModule($result);
		}
		return $arr;
	}
	
	
	/**
	 * This function removes the specified object from the database by id
	 * @param type $id
	 * @return type
	 */
	public static function delete($id) {
		$pdo = vDb::getDb();
		$stmt = $pdo->prepare('DELETE FROM '.self::TABLE_NAME.' WHERE '.self::MOD_ID.' = :'.self::MOD_ID);
		$stmt->bindParam(':'.self::MOD_ID, $id, PDO::PARAM_INT);
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
	 * @return \vModule
	 */
	public static function createModule($fetch_arr = null) {
		$mod = new vModule();
		//with vUtils::setEntryIfExists
		vUtils::setEntryIfExists($fetch_arr, self::MOD_ID, $mod->id);
		vUtils::setEntryIfExists($fetch_arr, self::MOD_NAME, $mod->name);
		vUtils::setEntryIfExists($fetch_arr, self::MOD_STARTFILE, $mod->startfile);
		vUtils::setEntryIfExists($fetch_arr, self::MOD_ENABLED, $mod->enabled);
		vUtils::setEntryIfExists($fetch_arr, self::MOD_TIME_CREATED, $mod->time_created);
		vUtils::setEntryIfExists($fetch_arr, self::MOD_TIME_LASTRUN, $mod->time_lastrun);
		/* //Without vUtils::setEntryIfExists
		  if (!empty($fetch_arr)) {
		  if (array_key_exists(self::MOD_ID, $fetch_arr)) {
		  $mod->id = $fetch_arr[self::MOD_ID];
		  }
		  if (array_key_exists(self::MOD_NAME, $fetch_arr)) {
		  $mod->name = $fetch_arr[self::MOD_NAME];
		  }
		  if (array_key_exists(self::MOD_STARTFILE, $fetch_arr)) {
		  $mod->startfile = $fetch_arr[self::MOD_STARTFILE];
		  }
		  if (array_key_exists(self::MOD_ENABLED, $fetch_arr)) {
		  $mod->enabled = $fetch_arr[self::MOD_ENABLED];
		  }
		  if (array_key_exists(self::MOD_TIME_CREATED, $fetch_arr)) {
		  $mod->time_created = $fetch_arr[self::MOD_TIME_CREATED];
		  }
		  if (array_key_exists(self::MOD_TIME_LASTRUN, $fetch_arr)) {
		  $mod->time_lastrun = $fetch_arr[self::MOD_TIME_LASTRUN];
		  }
		  }
		 */
		return $mod;
	}
	
	/**
	 * Cleans the database table
	 */
	public static function wipeModules() {
		$pdo = vDb::getDb();
		$stmt = $pdo->prepare($mrSql = 'DELETE FROM '.self::TABLE_NAME);
		$sqlResult = $stmt->execute();

		if ($sqlResult) {
			return true;
		} else {
			//log error
			vDebug::errorLog(var_export($stmt, true));
			vDebug::errorLog(__CLASS__." could not delete the table in database getting result=".var_export($sqlResult, true).". Error: ".var_export($pdo->errorInfo(), true)."\r\nSQL: $mrSql");
		}
		return false;
	}

}

/*

CREATE TABLE `module` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(128) NOT NULL DEFAULT '',
  `module_startfile` varchar(30) NOT NULL DEFAULT '',
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `time_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_lastrun` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`module_id`),
  KEY `enabled` (`enabled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `module_meta` (
  `module_ref` int(11) NOT NULL DEFAULT '0',
  `module_version` varchar(10) NOT NULL DEFAULT '',
  `module_desc` text NOT NULL,
  `author_name` varchar(80) NOT NULL DEFAULT '',
  `author_id` int(11) NOT NULL DEFAULT '0',
  `author_desc` text NOT NULL,
  `author_website` varchar(255) NOT NULL DEFAULT '',
  `author_email` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`module_ref`),
  KEY `module_version` (`module_version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 |


CREATE TABLE `module_param` (
  `module_ref` int(11) NOT NULL DEFAULT '0',
  `param_keyname` varchar(60) NOT NULL DEFAULT '',
  `param_displayname` varchar(100) NOT NULL DEFAULT '',
  `param_type` varchar(16) NOT NULL DEFAULT '',
  `param_default_value` text NOT NULL,
  `param_desc` text NOT NULL,
  `range_high` int(11) NOT NULL DEFAULT '0',
  `range_low` int(11) NOT NULL DEFAULT '0',
  `enum_strings` text NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `is_array` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`module_ref`,`param_keyname`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 |

CREATE TABLE `task` (
  `task_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_status` int DEFAULT 0, 
  `module_ref` int(11) NOT NULL DEFAULT '0',
  `time_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_to_execute` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_completed` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `result_module_code` int(11) NOT NULL DEFAULT '0',
  `result_module_msg` text NOT NULL,
  `result_execution_level` int(11) NOT NULL DEFAULT '0',
  `result_execution_code` int(11) NOT NULL DEFAULT '0',
  `result_execution_msg` text NOT NULL,
  `module_startfile` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`task_id`),
  KEY `task_status` (`task_status`),
  KEY `module_ref` (`module_ref`),
  KEY `time_completed` (`time_completed`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 |


CREATE TABLE `task_param` (
  `task_ref` int(11) NOT NULL DEFAULT '0',
  `param_keyname` varchar(60) NOT NULL DEFAULT '',
  `module_ref` int(11) NOT NULL DEFAULT '0',
  `param_displayname` varchar(100) NOT NULL DEFAULT '',
  `param_type` varchar(16) NOT NULL DEFAULT '',
  `param_value` text NOT NULL,
  PRIMARY KEY (`task_ref`,`param_keyname`),
  KEY `module_ref` (`module_ref`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 

 CREATE TABLE `val_user` (
  `val_user_id` int(11) NOT NULL AUTO_INCREMENT,
  `un` varchar(20) NOT NULL DEFAULT '',
  `pw_hash` char(60) NOT NULL DEFAULT '',
  `pw_salt` char(56) NOT NULL DEFAULT '',
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `user_name` varchar(80) NOT NULL DEFAULT '',
  `time_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_lastpw` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`val_user_id`),
  UNIQUE KEY `un` (`un`),
  KEY `enabled` (`enabled`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1

CREATE TABLE `val_user_feature` (
  `val_user_ref` int(11) NOT NULL DEFAULT '0',
  `feature_key` varchar(16) NOT NULL DEFAULT '',
  PRIMARY KEY (`val_user_ref`,`feature_key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 |

CREATE TABLE `val_user_log` (
  `val_user_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `val_user_ref` int(11) NOT NULL DEFAULT '0',
  `log_cat` varchar(16) NOT NULL DEFAULT '',
  `log_entry` text NOT NULL,
  `time_entry` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`val_user_log_id`),
  KEY `val_user_ref` (`val_user_ref`),
  KEY `log_cat` (`log_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 

CREATE TABLE `val_user_sess` (
  `val_user_sess_id` char(56) NOT NULL DEFAULT '',
  `val_user_id` int(11) NOT NULL DEFAULT '0',
  `time_start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ip` char(16) NOT NULL DEFAULT '',
  `browser` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`val_user_sess_id`),
  KEY `val_user_id` (`val_user_id`),
  KEY `time_last` (`time_last`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 |

*/
