<?php
//@require_once("vDebug.php");
/**
 * Description of vAuthUser
 *
 * @author btoffel
 */
class vAuthUser {

	// Name:	genUserDetails
	// Descrip:	Returns array of session and user details associated with a specified session identifier
	//
	public static function getSessDetails($sessId) {
	 $val = array();
	 $db = vDb::getDb();

	 return $val;
	}


	// Name:	pingSession
	// Descrip:	Returns true if session is active and valid, and if specified will update the timestamp of last activity
	//
	public static function pingSession($sessId, $refreshDate=0) {
	 $val = 0;
	 $db = vDb::getDb();
	 try {
	  $val = 1;
	 } catch (Exception $e) {

	 }
	 return $val;
	}


	// Name:	createSession
	// Descrip:	Evaluates the authentication credentials, registers a new valid session, and returns the unique session identifier
	//
	public static function createSession($auth_un, $auth_pw) {
	 $val = array();
	 $val['val_user_id'] = 0;
	 $val['sessId'] = '';
	 $db = vDb::getDb();
	 try {

	  // Determine whether user is enabled, and retrieve hash and salt value for authentication processing
	  $query = "SELECT val_user_id, pw_hash, pw_salt FROM val_user WHERE enabled = 1 AND un = ?";
	  $param = array($auth_un);
	  $sel = $db->prepare($query);
	  $sel->execute($param);
	  if ($sel->rowCount() == 1) {
	   $fet = $sel->fetch();
	   $thisUserId = 0;
	   $thisPwHash = '';
	   $thisPwSalt = '';
	   if ((int)$fet['val_user_id'] > 0) $thisUserId = (int)$fet['val_user_id'];
	   if ($fet['pw_hash']) $thisPwHash = $fet['pw_hash'];
	   if ($fet['pw_salt']) $thisPwSalt = $fet['pw_salt'];
	   if ($thisPwHash != '' && $thisPwSalt != '') {

	    // Make sure that provided cleartext password matches with hash/salt
	    $ok = 0;
	    $ok = self::evalPw($thisPwHash, $thisPwSalt, $auth_pw);
//echo (int)$ok;
//exit();

	    if ((int)$ok === 1) {
	     $val['val_user_id'] = (int)$thisUserId;
	     $val['sessId'] = self::genNewToken();
	    }

	   }
	  }

	 } catch (Exception $e) {

	 }
	 return $val;
	}


	// Name:	destroySession
	// Descrip:	Revokes validity of a current session
	//
	public static function destroySession($sessId) {
	 $val = 0;
	 $db = vDb::getDb();

	 return $val;
	}


	// Name:	genNewToken
	// Descrip:	Generates a random new cryptographically strong string to be used as session identifier, password salt, OTP, or CSRF token
	//
	public static function genNewToken() {
	 $val = base64_encode(mcrypt_create_iv(40, MCRYPT_DEV_URANDOM));
	 return $val;
	}


	// Name: 	refreshSessDate
	// Descrip:	Updates the timestamp of last activity on a valid session
	//
	private static function refreshSessDate($sessId) {
	 $val = 0;

	 return $val;
	}


	// Name:	evalPw
	// Descrip:	Evaluates password against hash/salt and returns true if valid
	//
	private static function evalPw($pw_hash, $pw_salt, $pw_cleartext) {
	 $val = 0;
	 $thisPwClear = $pw_cleartext;
	 $thisPwCipher = $pw_salt . $pw_hash;
	 $t_hasher = New vAuthPasswordHash(10, FALSE);
	 $check = $t_hasher->CheckPassword($thisPwClear, $thisPwCipher);

//echo $thisPwClear . ' -- ' . $thisPwCipher . ' == ' . (int)$check;
//exit();

	 $val = (int)$check;
	 return $val;
	}


	// Name:	genPwHash
	// Descrip:	Generates and returns a password hash value based on specified password and salt
	//
	private static function genPwHash($pw_cleartext, $pw_salt) {
	 $val = '';

	 return $val;
	}
	
}
