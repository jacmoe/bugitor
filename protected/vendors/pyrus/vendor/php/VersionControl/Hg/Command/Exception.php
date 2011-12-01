<?php
/**
 * Exception for Hg commands
 *
 * PHP version 5
 *
 * @category   VersionControl
 * @package    Hg
 * @subpackage Command
 * @author     Michael Gatto <mgatto@lisantra.com>
 * @copyright  2011 Lisantra Technologies, LLC
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       http://pear.php.net/package/VersionControl_Hg
 */

/**
 * Exception for Hg commands
 *
 * PHP version 5
 *
 * @category   VersionControl
 * @package    Hg
 * @subpackage Command
 * @author     Michael Gatto <mgatto@lisantra.com>
 * @copyright  2011 Lisantra Technologies, LLC
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       http://pear.php.net/package/VersionControl_Hg
 */
class VersionControl_Hg_Command_Exception extends Exception
{
    /**
     * Error constant
     */
    const COMMANDLINE_ERROR = 'commandLineError';

    /**
     * Error Constant
     */
    const MISMATCHED_FIELDS = 'mismatchedOutputFields';

    /**
     * Error Constant
     */
    const BAD_ARGUMENT = 'badArgument';

    /**
     * Error messages for humans
     *
     * @var array
     */
    protected $messages = array(
        'commandLineError' => "The command line returned an error status.
                               Please examine the output of
                               \$object->getCommandString() to see the actual
                               shell command issued. ",
        'mismatchedOutputFields' =>  "Fields do not match the output. ",
        'badArgument' => "The passed argument is not valid. ",
    );

    /**
     * Override constructor so we can make exception messages more structured
     * like Zend Framework's.
     *
     * @param string $message        is equivalent to the error constants
     * @param string $custom_message is a message appended to the default for
     *                               an error constant.
     */
    public function __construct($message, $custom_message = null)
    {
        /* does the class constant invoked in the constructor exist here?
         * If not, just let through the message as defined in the caller */
        $message = $this->messages[$message] . $custom_message;

        parent::__construct($message);
    }
}
