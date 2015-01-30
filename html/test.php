<?php
#
# This is a test program for the portable PHP password hashing framework.
#
# Written by Solar Designer and placed in the public domain.
# See PasswordHash.php for more information.
#
phpinfo();
exit();

@require 'classes/vPasswordHash.class.php';

header('Content-type: text/plain');

$ok = 0;

# Try to use stronger but system-specific hashes, with a possible fallback to
# the weaker portable hashes.
$t_hasher = new PasswordHash(10, FALSE);


$salt = base64_encode(mcrypt_create_iv(42, MCRYPT_DEV_URANDOM));
$correct = $salt.'lotus234';
$hash = $t_hasher->HashPassword($correct);

print 'Salt: ' . $salt . " (".strlen($salt).")\n";
print 'Hash: ' . $hash . " (".strlen($hash).")\n";

exit();

$check = $t_hasher->CheckPassword($correct, $hash);
if ($check) $ok++;
print "Check correct: '" . $check . "' (should be '1')\n";

$wrong = 'test12346';
$check = $t_hasher->CheckPassword($wrong, $hash);
if (!$check) $ok++;
print "Check wrong: '" . $check . "' (should be '0' or '')\n";

unset($t_hasher);

# Force the use of weaker portable hashes.
$t_hasher = new PasswordHash(10, TRUE);

$hash = $t_hasher->HashPassword($correct);

print 'Hash: ' . $hash . "\n";

$check = $t_hasher->CheckPassword($correct, $hash);
if ($check) $ok++;
print "Check correct: '" . $check . "' (should be '1')\n";

$check = $t_hasher->CheckPassword($wrong, $hash);
if (!$check) $ok++;
print "Check wrong: '" . $check . "' (should be '0' or '')\n";

# A correct portable hash for 'test12345'.
# Please note the use of single quotes to ensure that the dollar signs will
# be interpreted literally.  Of course, a real application making use of the
# framework won't store password hashes within a PHP source file anyway.
# We only do this for testing.
$hash = '$P$9IQRaTwmfeRo7ud9Fh4E2PdI0S3r.L0';

print 'Hash: ' . $hash . "\n";

$check = $t_hasher->CheckPassword($correct, $hash);
if ($check) $ok++;
print "Check correct: '" . $check . "' (should be '1')\n";

$check = $t_hasher->CheckPassword($wrong, $hash);
if (!$check) $ok++;
print "Check wrong: '" . $check . "' (should be '0' or '')\n";

if ($ok == 6)
	print "All tests have PASSED\n";
else
	print "Some tests have FAILED\n";

?>
