<?php
/**
 * A legacy password strategy based unsalted sha1.
 * You should NOT use this strategy in modern web applications, it is provided
 * to allow systems using this old, now broken standard to upgrade to a more
 * secure strategy.
 *
 * @author Charles Pick
 * @package packages.passwordStrategy
 */
class ALegacySha1PasswordStrategy extends APasswordStrategy {
	/**
	 * Encode a plain text password.
	 * @param string $password the plain text password to encode
	 * @return string the encoded password
	 */
	public function encode($password)
	{
		return sha1($password);
	}

}