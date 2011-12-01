<?php
/**
 * Contains definition for repository exceptions
 *
 * PHP version 5
 *
 * @category   VersionControl
 * @package    Hg
 * @subpackage Bundle
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
 * @subpackage Bundle
 * @author     Michael Gatto <mgatto@lisantra.com>
 * @copyright  2011 Lisantra Technologies, LLC
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       http://pear.php.net/package/VersionControl_Hg
 */
class VersionControl_Hg_Container_Bundle_Exception extends Exception
{
    /**
     * Error constant
     */
    const NO_BUNDLE = 'noBundle';

    /**
     * Error constant
     */
    const INVALID_BUNDLE = 'invalidBundle';

    /**
     * Error constant
     */
    const INVALID_TYPE = 'invalidType';

    /**
     * Error messages for humans
     *
     * @var array
     */
    protected $messages = array(
        'noBundle' => 'No repository was passed',
        'invalidBundle' => 'Invalid repository was passed: Mercurial could
                                not open the repository. Is there an .hg
                                directory there? Is it corrupted?',
        'invalidBundle' => 'Repository types must be one of: none, gzip or bzip2. ',
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
