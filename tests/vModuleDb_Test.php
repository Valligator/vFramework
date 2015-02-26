<?php

include_once("../models/vRoot.php");

ctg_phpunit_helper::setup_test_db_connection();
 
define("HAPPY_STR", '\'"\')(][\'"\'&Ā,ĐĘĶŃÓŚŰāđęķńóśűĂĎĚĹŅŒŠŲăěĺņœšųĄĒĻŇŕŢŸąēĢļňŖţŹĆĕģĽdŌŗźćĖĪľōŘťŻČėīŐřżčĮŁőŞŽįłőŞŽįłőŞŽįłşž\'"\')(][\'"\'&ĀĐĘĶŃÓŚŰāđęķńóśűĂĎĚĹŅŒŠŲăěĺņœšųĄĒĻŇŕŢŸąēĢļňŖţŹĆĕģĽdŌŗźćĖĪľōŘťŻČėīŐřżčĮŁőŞŽįłőŞŽįłőŞŽįłşž');

/**
 * Generated by PHPUnit_SkeletonGenerator on 2013-05-10 at 16:02:30.
 */
class vModuleDb_Test extends PHPUnit_Framework_TestCase {

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


	public function verify_no_run_checklist_items_test() {
		print_ln(__METHOD__." starting.");
		$arr = vModuleDb::getModules();
		$this->assertEquals(0, count($arr), "Everything should be deleted and table should be empty, but was not.");
		print_ln(__METHOD__." complete.");
	}


	/**
	 * Verify the sample generator is operating as we expect
	 */
	public function verify_sample_9_test() {
		print_ln(__METHOD__." starting.");
		$test_item = new vModule();
		$index = 9;
		$test_item->name("name:$index".HAPPY_STR);
		$test_item->enabled($index);
		$test_item->startfile("startf:$index".HAPPY_STR);
		$test_item->time_lastrun("tl:$index".HAPPY_STR);
		
		$compare_item = self::create_sample_module(9);
		$not_same = $this->compare_item($test_item, $compare_item);
		$this->assertTrue(empty($not_same), $not_same);
		print_ln(__METHOD__." complete.");
	}

	/**
	 * Verify we can add a sample to the database and get back the same data
	 */
	public function verify_add_sample_test() {
		print_ln(__METHOD__." starting.");
		//first make sure it is empty
		$item_arr = vModuleDb::getModules();
		$this->assertEquals(0, count($item_arr), "Expecting only no items here.");
		
		//add item
		$item1 = self::create_sample_module(4);
		$result = vModuleDb::insert($item1);
		$this->assertTrue($result > 0, "Expected a last insert id number, but got '$result' on insert");
		$last_insert_id = $result;
		
		$item_arr = vModuleDb::getModules();
		$this->assertEquals(1, count($item_arr), "Expecting only 1 sample here.");

		//verify created sample matches what is recovered from database
		$item_read = vModuleDb::getModule($last_insert_id);
		//make $item1 the same
		$item1->id = $last_insert_id;
		//make sure they match
		$not_same = $this->compare_item($item_read, $item1);
		$this->assertTrue(empty($not_same), $not_same);
		
		print_ln(__METHOD__." complete.");
	}

	public function verify_delete_sample_test() {
		print_ln(__METHOD__." starting.");
		$item1 = self::create_sample_module(1);
		$result = vModuleDb::insert($item1);
		$this->assertTrue($result > 0, "Expected a last insert id number, but got '$result' on insert");
		
		$item_arr = vModuleDb::getModules();
		$this->assertEquals(1, count($item_arr), "Expecting only 1 sample here.");

		//remove created item
		$del_result = vModuleDb::delete($result);
		$this->assertTrue($del_result, "Expected item to be deleted.");
		
		
		//check after deleting item
		$item_arr2 = vModuleDb::getModules();
		$this->assertEquals(0, count($item_arr2), "Expecting no samples here.");

		print_ln(__METHOD__." complete.");
	}
	
	
			
	//============-----------> Helper Functions <----------------==============\\
	
	protected function build_module_post(vModule $item) {
		unset($_POST);
		$_POST = array(
			vModuleDb::MOD_ID => $item->id,
			vModuleDb::MOD_ENABLED => $item->enabled,
			vModuleDb::MOD_NAME => $item->name,
			vModuleDb::MOD_STARTFILE => $item->startfile,
			vModuleDb::MOD_TIME_LASTRUN => $item->time_lastrun
		);
	}
		
	protected function clean_up_db() {
		$result = vModuleDb::wipeModules();
		$this->assertTrue($result, "Could not clear database, check error log for details.");
	}

	protected static function create_sample_module($index=0) {
		$item = new vModule();
		
		$item->enabled = $index;
		$item->name = "name:$index".HAPPY_STR;
		$item->startfile  = "startf:$index".HAPPY_STR;
		$item->time_created  = "tc:$index".HAPPY_STR;
		$item->time_lastrun  = "tl:$index".HAPPY_STR;

		return $item;
	}

		
	/**
	 * Returns true if they do not match, empty if they do match
	 * @param type $er
	 */
	private function compare_to_sample($ct, $index = 0) {
		$ct_samp = self::create_sample_module($index);
		return self::compare_item($ct, $ct_samp);
	}

	/**
	 * Returns true if they do not match, empty if they do
	 * @param type $er
	 */
	protected static function compare_item($actual, $desired) {
		$not_same = "";
		//compare passed item to sample
		$not_same .= self::compare_helper($desired->id, $actual->id, vModuleDb::MOD_ID);
		$not_same .= self::compare_helper($desired->enabled, $actual->enabled, vModuleDb::MOD_ENABLED);
		$not_same .= self::compare_helper($desired->name, $actual->name, vModuleDb::MOD_NAME);
		$not_same .= self::compare_helper($desired->startfile, $actual->startfile, vModuleDb::MOD_STARTFILE);
		//can't really test time created, since each object will be created at a different time
		$not_same .= self::compare_helper($desired->time_lastrun, $actual->time_lastrun, vModuleDb::MOD_TIME_LASTRUN);
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
