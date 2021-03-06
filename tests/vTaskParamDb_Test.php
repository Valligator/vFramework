<?php

include_once("../models/vRoot.php");
include_once("vTestsHelpers.php");
 
define("HAPPY_STR", '\'"\')(][\'"\'&Ā,ĐĘĶŃÓŚŰāđęķńóśűĂĎĚĹŅŒŠŲăěĺņœšųĄĒĻŇŕŢŸąēĢļňŖţŹĆĕģĽdŌŗźćĖĪľōŘťŻČėīŐřżčĮŁőŞŽįłőŞŽįłőŞŽįłşž\'"\')(][\'"\'&ĀĐĘĶŃÓŚŰāđęķńóśűĂĎĚĹŅŒŠŲăěĺņœšųĄĒĻŇŕŢŸąēĢļňŖţŹĆĕģĽdŌŗźćĖĪľōŘťŻČėīŐřżčĮŁőŞŽįłőŞŽįłőŞŽįłşž');
/**
 * Generated by PHPUnit_SkeletonGenerator on 2013-05-10 at 16:02:30.
 */
class vTaskParamDb_Test extends PHPUnit_Framework_TestCase {

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before each test is executed.
	 * 
	 * Assumes the test database was already created by installHelper::install($un, $pw, vDb::getTestDbName());
	 */
	protected function setUp() {
		//use test db - otherwise tests will run on the frameworks normal database
		vDb::loadTestDb();
		//$this->object = new ctg_run_checklist_item;
		print_ln(__METHOD__." starting.");
		$this->clean_up_db();
		print_ln(__METHOD__." complete.");
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after each test is executed.
	 */
	protected function tearDown() {
		
	}


	public function test_no_items_test() {
		print_ln(__METHOD__." starting.");
		$arr = vTaskParamDb::getAllTaskParams();
		$this->assertEquals(0, count($arr), "Everything should be deleted and table should be empty, but was not.");
		print_ln(__METHOD__." complete.");
	}


	/**
	 * Verify the sample generator is operating as we expect
	 */
	public function test_sample_9_test() {
		print_ln(__METHOD__." starting.");
		$test_item = new vTaskParam();
		$index = 9;
		$test_item->task_ref = $index;
		$test_item->param_keyname = substr("key:$index".HAPPY_STR, 0, vTaskParamDb::PARAM_KEYNAME_LENGTH);
		$test_item->module_ref  = $index+10;
		$test_item->param_displayname  = substr("dn:$index".HAPPY_STR, 0, vTaskParamDb::PARAM_DISPLAYNAME_LENGTH);
		$test_item->param_type  = substr("pt:$index".HAPPY_STR, 0, vTaskParamDb::PARAM_TYPE_LENGTH);
		$test_item->param_value  = "pv:$index".HAPPY_STR;

		$compare_item = self::create_sample_item(9);
		$not_same = $this->compare_item($test_item, $compare_item);
		$this->assertTrue(empty($not_same), $not_same);
		print_ln(__METHOD__." complete.");
	}

	
	/**
	 * Verify we can add a sample to the database and get back the same data
	 */
	public function test_add_sample_test() {
		print_ln(__METHOD__." starting.");
		//first make sure it is empty
		$item_arr = vTaskParamDb::getAllTaskParams();
		$this->assertEquals(0, count($item_arr), "Expecting only no items here.");
		
		//add item
		$item1 = self::create_sample_item(4);
		$result = vTaskParamDb::insert($item1);
		$this->assertTrue(strlen($result) > 0, "Expected a last insert id number, but got '$result' on insert");
		$last_insert_id = $result;
		
		$item_arr = vTaskParamDb::getAllTaskParams();
		$this->assertEquals(1, count($item_arr), "Expecting only 1 sample here.");

		//verify created sample matches what is recovered from database
		$item_read = vTaskParamDb::getTaskParam($item1->task_ref, $item1->param_keyname);
		//make sure they match
		$not_same = $this->compare_item($item_read, $item1);
		if ($not_same) {
			print_ln("Sample:");
			var_export($item1);
			print_ln("As read:");
			var_export($item_read);
		}
		$this->assertTrue(empty($not_same), $not_same);
		
		print_ln(__METHOD__." complete.");
	}

	public function test_delete_sample_test() {
		print_ln(__METHOD__." starting.");
		$item1 = self::create_sample_item(1);
		$result = vTaskParamDb::insert($item1);
		$this->assertTrue(strlen($result) > 0, "Expected a last insert id number, but got '$result' on insert");
		
		$item_arr = vTaskParamDb::getAllTaskParams();
		$this->assertEquals(1, count($item_arr), "Expecting only 1 sample here.");

		//remove created item
		$del_result = vTaskParamDb::delete($item1->task_ref, $item1->param_keyname);
		$this->assertTrue($del_result, "Expected item to be deleted.");
		
		
		//check after deleting item
		$item_arr2 = vTaskParamDb::getAllTaskParams();
		$this->assertEquals(0, count($item_arr2), "Expecting no samples here.");

		print_ln(__METHOD__." complete.");
	}
	
		public function test_wipe_module_test() {
		print_ln(__METHOD__." starting.");
		$item1 = self::create_sample_item(1);
		$result = vTaskParamDb::insert($item1);
		$this->assertTrue(strlen($result) > 0, "Expected a last insert id number, but got '$result' on insert");
		
		$item_arr = vTaskParamDb::getAllTaskParams();
		$this->assertEquals(1, count($item_arr), "Expecting only 1 sample here.");

		//remove created item
		$del_result = vTaskParamDb::wipeTaskParams($item1->task_ref);
		$this->assertTrue($del_result, "Expected item to be deleted.");
		
		
		//check after deleting item
		$item_arr2 = vTaskParamDb::getAllTaskParams();
		$this->assertEquals(0, count($item_arr2), "Expecting no samples here.");

		print_ln(__METHOD__." complete.");
	}
	
			
	//============-----------> Helper Functions <----------------==============\\

		
	protected function clean_up_db() {
		$result = vTaskParamDb::wipeAllParams();
		$this->assertTrue($result, "Could not clear database, check error log for details.");
	}

	protected static function create_sample_item($index=0) {
		$item = new vTaskParam();
		
		//trim out items longer than are allowed (for comparison purposes)
		//ie. even though PDO will filter out the characters beyond what can be stored,
		//we want to compare what is inserted in the db to what is read from the db
		$item->task_ref = $index;
		$item->param_keyname = substr("key:$index".HAPPY_STR, 0, vTaskParamDb::PARAM_KEYNAME_LENGTH);
		$item->module_ref  = $index+10;
		$item->param_displayname  = substr("dn:$index".HAPPY_STR, 0, vTaskParamDb::PARAM_DISPLAYNAME_LENGTH);
		$item->param_type  = substr("pt:$index".HAPPY_STR, 0, vTaskParamDb::PARAM_TYPE_LENGTH);
		$item->param_value  = "pv:$index".HAPPY_STR;

		return $item;
	}

		
	/**
	 * Returns true if they do not match, empty if they do match
	 * @param type $er
	 */
	private function compare_to_sample($ct, $index = 0) {
		$ct_samp = self::create_sample_item($index);
		return self::compare_item($ct, $ct_samp);
	}

	/**
	 * Returns true if they do not match, empty if they do
	 * @param type $er
	 */
	protected static function compare_item($actual, $desired) {
		$not_same = "";
		//compare passed item to sample
		$not_same .= self::compare_helper($desired->task_ref, $actual->task_ref, vTaskParamDb::TASK_REF);
		$not_same .= self::compare_helper(substr($desired->param_keyname, 0, vTaskParamDb::PARAM_KEYNAME_LENGTH), substr($actual->param_keyname, 0, vTaskParamDb::PARAM_KEYNAME_LENGTH), vTaskParamDb::PARAM_KEYNAME);
		$not_same .= self::compare_helper($desired->module_ref, $actual->module_ref, vTaskParamDb::MODULE_REF);
		$not_same .= self::compare_helper(substr($desired->param_displayname, 0, vTaskParamDb::PARAM_DISPLAYNAME_LENGTH), substr($actual->param_displayname, 0, vTaskParamDb::PARAM_DISPLAYNAME_LENGTH), vTaskParamDb::PARAM_DISPLAYNAME);
		$not_same .= self::compare_helper(substr($desired->param_type, 0, vTaskParamDb::PARAM_TYPE_LENGTH), substr($actual->param_type, 0, vTaskParamDb::PARAM_TYPE_LENGTH), vTaskParamDb::PARAM_TYPE);
		$not_same .= self::compare_helper($desired->param_value, $actual->param_value, vTaskParamDb::PARAM_VALUE);
		return $not_same;
	}

	
	private static function compare_helper($desired, $actual, $field_name, $prefix = "") {
		$not_same = "";
		if ($desired.$prefix == $actual) {
			//value matches, do nothing
		} else {
			$not_same = "$field_name actual value [$actual] doesn't desired match [$desired$prefix]\n";
		}
		return $not_same;
	}

}
