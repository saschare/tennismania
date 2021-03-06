<?php


#
# This is a test program for the portable PHP password hashing framework.
#
# Written by Solar Designer and placed in the public domain.
# See PasswordHash.php for more information.
#

class Openwall_PasswordHash_Test {

	public static function test() {
		
		$out = '';

		$ok = 0;

		# Try to use stronger but system-specific hashes, with a possible fallback to
		# the weaker portable hashes.
		$t_hasher = new Openwall_PasswordHash(8, FALSE);

		$correct = 'test12345';
		$hash = $t_hasher->hashPassword($correct);

		$out .= 'Hash: ' . $hash . "\n";

		$check = $t_hasher->checkPassword($correct, $hash);
		if ($check)
			$ok++;
		print "Check correct: '" . $check . "' (should be '1')\n";

		$wrong = 'test12346';
		$check = $t_hasher->checkPassword($wrong, $hash);
		if (!$check)
			$ok++;
		$out .= "Check wrong: '" . $check . "' (should be '0' or '')\n";

		unset ($t_hasher);

		# Force the use of weaker portable hashes.
		$t_hasher = new Openwall_PasswordHash(8, TRUE);

		$hash = $t_hasher->hashPassword($correct);

		$out .= 'Hash: ' . $hash . "\n";

		$check = $t_hasher->checkPassword($correct, $hash);
		if ($check)
			$ok++;
		$out .= "Check correct: '" . $check . "' (should be '1')\n";

		$check = $t_hasher->checkPassword($wrong, $hash);
		if (!$check)
			$ok++;
		$out .= "Check wrong: '" . $check . "' (should be '0' or '')\n";

		# A correct portable hash for 'test12345'.
		# Please note the use of single quotes to ensure that the dollar signs will
		# be interpreted literally.  Of course, a real application making use of the
		# framework won't store password hashes within a PHP source file anyway.
		# We only do this for testing.
		$hash = '$P$9IQRaTwmfeRo7ud9Fh4E2PdI0S3r.L0';

		$out .= 'Hash: ' . $hash . "\n";

		$check = $t_hasher->checkPassword($correct, $hash);
		if ($check)
			$ok++;
		$out .= "Check correct: '" . $check . "' (should be '1')\n";

		$check = $t_hasher->checkPassword($wrong, $hash);
		if (!$check)
			$ok++;
		$out .= "Check wrong: '" . $check . "' (should be '0' or '')\n";

		if ($ok == 6)
			$out .= "All tests have PASSED\n";
		else
			$out .= "Some tests have FAILED\n";
			
		return $out;
	}
}