<?php

/**
 * Container for the values to represent a module object
 * Validation code should go here
 */
class vModConfig {

	public $module_ref = 0; //Unique identifier of module,
	public $param_keyname = ""; //The name of the parameter
	public $param_displayname = ""; //The text to display to the user
	public $param_type = ""; //The expected value type (i.e. "int", "csv", "text", "decimal", etc.)
	public $param_default_value = ""; //A default value for this parameter, if applicable
	public $param_desc = ""; //Extended description about the parameter, to be used as extra help
	public $range_high = 0; //If a numeric type, this would define the upper limit of an acceptable value
	public $range_low = 0; //If a numeric type, this would define the lower limit of an acceptable value
	public $enum_strings = ""; //Serialized array of accepted strings, only if "module_param.param_type" = "enum"
	public $is_required = ""; //Designates whether this parameter is required for module to execute
	public $is_array = ""; //Designates whether multiple values are allowed

}

?>