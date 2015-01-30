<?php

require_once("vModParam.php");

/**
 * This class is for module parameter definitions.  Each item describes a piece of data used in a module,
 * but does not contain an actual value itself (other than the default value).  
 */
class vModParamDb {

	const TABLE_NAME = "module_param";
	const MODULE_REF = "module_ref";
	const PARAM_KEYNAME = "param_keyname";
	const PARAM_DISPLAYNAME = "param_displayname";
	const PARAM_TYPE = "param_type";
	const PARAM_DEFAULT_VALUE = "param_default_value";
	const PARAM_DESC = "param_desc";
	const RANGE_HIGH = "range_high";
	const RANGE_LOW = "range_low";
	const ENUM_STRINGS = "enum_strings";
	const IS_REQUIRED = "is_required";
	const IS_ARRAY = "is_array";

	
	public static function createTable(PDO $pdo) {
	
		$mrSql = self::getCreateTableHelper();
		$stmt = $pdo->prepare($mrSql);
		$sqlResult = $stmt->execute();
		if (!$sqlResult) {
			//log error
			vDebug::errorLog(var_export($stmt, true));
			vDebug::errorLog("Could not create database, Error: ".var_export($pdo->errorInfo(), true)."\r\nSQL: $mrSql");
		}
		return 1;
	}

	public static function getCreateTableHelper() {
		$str = "CREATE TABLE `".self::TABLE_NAME."` (
			  `".self::MODULE_REF."` int(11) NOT NULL DEFAULT '0',
			  `".self::PARAM_KEYNAME."` varchar(60) NOT NULL DEFAULT '',
			  `".self::PARAM_DISPLAYNAME."` varchar(100) NOT NULL DEFAULT '',
			  `".self::PARAM_TYPE."` varchar(16) NOT NULL DEFAULT '',
			  `".self::PARAM_DEFAULT_VALUE."` text NOT NULL,
			  `".self::PARAM_DESC."` text NOT NULL,
			  `".self::RANGE_HIGH."` int(11) NOT NULL DEFAULT '0',
			  `".self::RANGE_LOW."` int(11) NOT NULL DEFAULT '0',
			  `".self::ENUM_STRINGS."` text NOT NULL,
			  `".self::IS_REQUIRED."` tinyint(1) NOT NULL DEFAULT '0',
			  `".self::IS_ARRAY."` tinyint(1) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`".self::MODULE_REF."`,`".self::PARAM_KEYNAME."`),
			) ENGINE=".VAL_DB_ENGINE." DEFAULT CHARSET=".VAL_DB_CHARSET." COLLATE=".VAL_DB_COLLATE.";";
		return $str;
	}

	/**
	 * This function saves the passed object to the database
	 * @param vParameters $object
	 * @return int -1 if failed, or last_insert_id if success
	 */
	public static function insert(vParameters $object) {
		$pdo = vDb::getDb();
		$mrSql = 'INSERT INTO '.self::TABLE_NAME.' ( '.
				self::MODULE_REF.', '.
				self::PARAM_KEYNAME.', '.
				self::PARAM_DISPLAYNAME.', '.
				self::PARAM_TYPE.', '.
				self::PARAM_DEFAULT_VALUE.'), '.
				self::PARAM_DESC.'), '.
				self::RANGE_HIGH.'), '.
				self::RANGE_LOW.'), '.
				self::ENUM_STRINGS.'), '.
				self::IS_REQUIRED.'), '.
				self::IS_ARRAY.')'.
				' VALUES (  '.
				' :'.self::MODULE_REF.', '.
				' :'.self::PARAM_KEYNAME.', '.
				' :'.self::PARAM_DISPLAYNAME.', '.
				' :'.self::PARAM_TYPE.', '.
				' :'.self::PARAM_DEFAULT_VALUE.', '.
				' :'.self::PARAM_DESC.', '.
				' :'.self::RANGE_HIGH.', '.
				' :'.self::RANGE_LOW.', '.
				' :'.self::ENUM_STRINGS.', '.
				' :'.self::IS_REQUIRED.', '.
				' :'.self::IS_ARRAY.')';
		$stmt = $pdo->prepare($mrSql);

		$stmt->bindParam(":".self::MODULE_REF, $object->module_ref, PDO::PARAM_INT);
		$stmt->bindParam(":".self::PARAM_KEYNAME, $object->param_keyname, PDO::PARAM_STR);
		$stmt->bindParam(":".self::PARAM_DISPLAYNAME, $object->param_displayname, PDO::PARAM_STR);
		$stmt->bindParam(":".self::PARAM_TYPE, $object->param_type, PDO::PARAM_STR);
		$stmt->bindParam(":".self::PARAM_DEFAULT_VALUE, $object->param_default_value, PDO::PARAM_STR);
		$stmt->bindParam(":".self::PARAM_DESC, $object->param_desc, PDO::PARAM_STR);
		$stmt->bindParam(":".self::RANGE_HIGH, $object->range_high, PDO::PARAM_INT);
		$stmt->bindParam(":".self::RANGE_LOW, $object->range_low, PDO::PARAM_INT);
		$stmt->bindParam(":".self::ENUM_STRINGS, $object->enum_strings, PDO::PARAM_STR);
		$stmt->bindParam(":".self::IS_REQUIRED, $object->is_required, PDO::PARAM_INT);
		$stmt->bindParam(":".self::IS_ARRAY, $object->is_array, PDO::PARAM_INT);

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
	 * This function retrieves the specified object from the database by id
	 * @param type $id
	 * @return type
	 */
	public static function getModParam($mod_ref, $key) {
		$pdo = vDb::getDb();
		$stmt = $pdo->prepare('SELECT * FROM '.self::TABLE_NAME.' WHERE '.self::MODULE_REF.' = :'.self::MODULE_REF.
				' AND '.self::PARAM_KEYNAME.' = :'.self::PARAM_KEYNAME);
		$stmt->bindParam(':'.self::MODULE_REF, $mod_ref, PDO::PARAM_INT);
		$stmt->bindParam(':'.self::PARAM_KEYNAME, $key, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return self::create_module($result);
	}

	/**
	 * Factory method for creating the object
	 * @param type $fetch_arr
	 * @return \vParameters
	 */
	public static function createModule($fetch_arr = null) {
		$object = new vParameters();
		//with vUtils::setEntryIfExists
		vUtils::setEntryIfExists($fetch_arr, self::MODULE_REF, $object->module_ref);
		vUtils::setEntryIfExists($fetch_arr, self::PARAM_KEYNAME, $object->param_keyname);
		vUtils::setEntryIfExists($fetch_arr, self::PARAM_DISPLAYNAME, $object->param_displayname);
		vUtils::setEntryIfExists($fetch_arr, self::PARAM_TYPE, $object->param_type);
		vUtils::setEntryIfExists($fetch_arr, self::PARAM_DEFAULT_VALUE, $object->param_default_value);
		vUtils::setEntryIfExists($fetch_arr, self::PARAM_DESC, $object->param_desc);
		vUtils::setEntryIfExists($fetch_arr, self::RANGE_HIGH, $object->range_high);
		vUtils::setEntryIfExists($fetch_arr, self::RANGE_LOW, $object->range_low);
		vUtils::setEntryIfExists($fetch_arr, self::ENUM_STRINGS, $object->enum_strings);
		vUtils::setEntryIfExists($fetch_arr, self::IS_REQUIRED, $object->is_required);
		vUtils::setEntryIfExists($fetch_arr, self::IS_ARRAY, $object->is_array);

		return $object;
	}

}