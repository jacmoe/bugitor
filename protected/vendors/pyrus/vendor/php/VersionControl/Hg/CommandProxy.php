<?php
/**
 * Contains the class definition for VersionControl_Hg_CommandProxy
 *
 * PHP version 5
 *
 * @category  VersionControl
 * @package   Hg
 * @author    Michael Gatto <mgatto@lisantra.com>
 * @copyright 2011 Lisantra Technologies, LLC
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://pear.php.net/package/VersionControl_Hg
 */

/**
 * Include the Exception class manually.
 */
require_once 'Exception.php';

/**
 * Instantiates the Mercurial command and passes an instance of
 * VersionControl_Hg into the constructor of each command.
 *
 * PHP version 5
 *
 * @category  VersionControl
 * @package   Hg
 * @author    Michael Gatto <mgatto@lisantra.com>
 * @copyright 2011 Lisantra Technologies, LLC
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://pear.php.net/package/VersionControl_Hg
 */
class VersionControl_Hg_CommandProxy
{
    /**
     * Implemented commands
     *
     * @var array
     */
    protected $allowed_commands = array(
        'version', 'archive', 'status', 'log', 'init', 'clone', 'pull', 'cat'
    );

    /**
     * The command class to be instantiated
     *
     * @var VersionControl_Hg_Command_Abstract
     */
    protected $command;

    /**
     * The parent, core object
     *
     * @var VersionControl_Hg
     */
    protected $hg;

    /**
     * Constructor
     *
     * @param VersionControl_Hg $hg is the root object
     */
    public function __construct(VersionControl_Hg $hg)
    {
        $this->hg = $hg;
    }

    /**
     * Sets the command property
     *
     * @param VersionControl_Hg_Command_Interface $command is the command
     * instance to execute
     *
     * @return null
     */
    public function setCommand(VersionControl_Hg_Command_Interface $command)
    {
        $this->command = $command;
    }

    /**
     * Returns the command property
     *
     * @return VersionControl_Hg_Command_Interface
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Proxies to the actual implementations of the commands
     *
     * @param string $method    The command to instantiate
     * @param array  $arguments Parameters for the command
     *
     * @return VersionControl_Hg_Command_Interface
     * @throws VersionControl_Hg_Exception
     */
    public function __call($method, $arguments = null)
    {
        //@TODO is_callable & method_exists

        if ( ! in_array($method, $this->allowed_commands) ) {
            return new VersionControl_Hg_Exception(
                'The command is unrecognized or unimplemented'
            );
        }

        $class = 'VersionControl_Hg_Command_' . ucfirst($method);

        /* We don't want relative paths because of Php's seemingly odd
         * handling of relative includes within includes */
        include_once dirname(__FILE__) . '/Command/' . ucfirst($method) . ".php";

        /* this tests only if the class exists in the included file */
        if ( ! class_exists($class, false) ) {
            throw new VersionControl_Hg_Exception(
                "Sorry, The command \'{$method}\' is not implemented, or
                 you called `run()` without first issuing a valid command"
            );
        }

        $this->command = new $class($arguments, $this->hg);

        /* for fluent API */
        return $this->command;
    }
}
