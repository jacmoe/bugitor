<?php
/**
 * Contains definition for repository exceptions
 *
 * PHP version 5
 *
 * @category   VersionControl
 * @package    Hg
 * @subpackage Repository
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
 * @subpackage Repository
 * @author     Michael Gatto <mgatto@lisantra.com>
 * @copyright  2011 Lisantra Technologies, LLC
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       http://pear.php.net/package/VersionControl_Hg
 */
class VersionControl_Hg_Container_Repository_Exception extends Exception
{
    /**
     * Error constant
     */
    const NO_REPOSITORY = 'noRepository';

    /**
     * Error constant
     */
    const INVALID_REPOSITORY = 'invalidRepository';

    /**
     * Error constant
     */
    const DOES_NOT_EXIST = 'doesNotExist';


    /**
     * Error messages for humans
     *
     * @var array
     */
    protected $messages = array(
        'noRepository' => 'No repository was passed in.
                           Use $hg->setRepository(\'/path/to/repository\') to
                           create a repository.',
        'invalidRepository' => 'Invalid repository was passed: Mercurial could
                                not open the repository. Is there an .hg
                                directory there? Is it corrupted?',
        'doesNotExist' => 'The path does not exist on this system',
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
