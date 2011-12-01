<?php
/**
 * Contains definition for repository exceptions
 *
 * PHP version 5
 *
 * @category   VersionControl
 * @package    Hg
 * @subpackage WorkingCopy
 * @author     Michael Gatto <mgatto@lisantra.com>
 * @copyright  2011 Lisantra Technologies, LLC
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       http://pear.php.net/package/VersionControl_Hg
 */

/**
 * Exception for Mercurial repositories
 *
 * PHP version 5
 *
 * @category   VersionControl
 * @package    Hg
 * @subpackage WorkingCopy
 * @author     Michael Gatto <mgatto@lisantra.com>
 * @copyright  2011 Lisantra Technologies, LLC
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       http://pear.php.net/package/VersionControl_Hg
 */
class VersionControl_Hg_Container_WorkingCopy_Exception extends Exception
{
    /**
     * Error constant
     */
    const NO_REPOSITORY = 'noWorkingCopy';

    /**
     * Error constant
     */
    const INVALID_REPOSITORY = 'invalidWorkingCopy';

    /**
     * Error messages for humans
     *
     * @var array
     */
    protected $messages = array(
        'noWorkingCopy' => 'No working copy was passed',
        'invalidWorkingCopy' => 'Invalid working copy was passed.',
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
