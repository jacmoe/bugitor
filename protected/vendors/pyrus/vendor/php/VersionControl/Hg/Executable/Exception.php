<?php
/**
 * Contains definition of Exception class for Hg executables
 *
 * PHP version 5
 *
 * @category   VersionControl
 * @package    Hg
 * @subpackage Executable
 * @author     Michael Gatto <mgatto@lisantra.com>
 * @copyright  2011 Lisantra Technologies, LLC
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       http://pear.php.net/package/VersionControl_Hg
 */

/**
 * Exception for Hg executables
 *
 * PHP version 5
 *
 * @category   VersionControl
 * @package    Hg
 * @subpackage Executable
 * @author     Michael Gatto <mgatto@lisantra.com>
 * @copyright  2011 Lisantra Technologies, LLC
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       http://pear.php.net/package/VersionControl_Hg
 */
class VersionControl_Hg_Executable_Exception extends Exception
{
    /**
     * Error constant for when the mercurial executable cannot be found
     */
    const ERROR_HG_NOT_FOUND = 'notFound';

    /**
     * Error constant for when operations are called before setting the Hg
     * executable.
     *
     * Should not normally happen since its set in the constructor and
     * throws an exception when an executable cannot be found.
     */
    const ERROR_HG_YET_UNSET = 'yetUnset';

    /**
     * Error constant for when the Mercurial executable's version cannot be
     * determined.
     *
     * Not sure if this type of error is even possible.
     */
    const ERROR_NO_VERSION = 'noVersion';

    /**
     * Error messages for humans
     *
     * @var array
     */
    protected $messages = array(
        'notFound' => 'Mercurial could not be found on this system',
        'yetUnset' => 'The Mercurial executable has not yet been set',
        'noVersion' => 'No Hg version has yet been set!',
    );

    /**
     * Override constructor so we can make exception messages more structured
     * like Zend Framework's.
     *
     * @param string $message is equivalent to the error constants
     */
    public function __construct($message)
    {
        parent::__construct($this->messages[$message]);
    }

}
