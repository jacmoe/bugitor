<?php
/**
 * Contains the class which interacts with the system's `hg` program.
 *
 * PHP version 5
 *
 * @category  VersionControl
 * @package   Hg
 * @author    Michael Gatto <mgatto@lisantra.com>
 * @copyright 2011 Lisantra Technologies, LLC
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://pear.php.net/package/VersionControl_Hg
 * @filesource
 */

/**
 * Provides the base exception
 * @TODO Consider removing since we don't appear to actually use it (?)
 */
require_once 'Hg/Exception.php';

/**
 * Provides access to the Mercurial executable
 */
require_once 'Hg/Executable.php';

/**
 * Provides access to the SCM repository
 */
require_once 'Hg/Container/Repository.php';

/**
 * Interfaces with the classes which implement the commands
 */
require_once 'Hg/CommandProxy.php';

/**
 * Base class to begin the fluent API
 *
 * This package interfaces with the Mercurial command-line binary, which
 * must be installed on the same system as this package. The author of Mercurial
 * is on record preferring that all non-python programs interface with the
 * CLI binary.
 *
 * There are no C-bindings, so a PECL extension is unlikely, as is a pure PHP
 * implementation due to the tremendous workload involved in keeping up with
 * changes Mercurial.
 *
 * Usage:
 * <code>
 * require_once 'VersionControl/Hg.php';
 * $hg = new VersionControl_Hg('/path/to/repository');
 * </code>
 *
 * Setting the repository also automatically finds and sets the local
 * path of the Mercurial binary it will use. If multiple installations of
 * Mercurial are found, this package will employ only the first found on the
 * system path.
 *
 * You may also provide a location of a repository after instantiation:
 * <code>
 * require_once 'VersionControl/Hg.php';
 * $hg = new VersionControl_Hg();
 * $hg->setRepository('/path/to/repository');
 * </code>
 * or
 * <code>$hg->repository = '/path/to/repository';</code>
 *
 * Calling all commands other than 'version' without having already set a
 * valid repository will raise an exception.
 *
 * PHP version 5
 *
 * @category  VersionControl
 * @package   Hg
 * @author    Michael Gatto <mgatto@lisantra.com>
 * @copyright 2011 Lisantra Technologies, LLC
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://pear.php.net/package/VersionControl_Hg
 * @tutorial  Instantiating.html
 */
class VersionControl_Hg
{
    /**
     * Constructor
     *
     * Assumes it will be working on a local filesystem repository
     *
     * The repository path is optional in the constructor.
     * It can be set with an explicit setting of the repository's path.
     * You can only set the path once since Repository is a singleton;
     * All subsqeuent attempts will silently fail after one of the below
     * has been performed:
     * <code>$hg->setRepository('/path/to/repository');</code>
     * or
     * <code>$hg->repository->setPath('/path/to/repository');</code>
     * or
     * <code>$hg->repository = '/path/to/repository';</code>
     *
     * @param string $repository Optional path to a mercurial repository.
     *
     * @return void
     */
    public function __construct($repository = null)
    {
        /* invalid repository will trigger an exception in the
         * child class VersionControl_Hg_Container_Repository */
        $this->setRepository($repository);

        /* This is second, since why look for an executable if the passed
         * repository path is no good?
         *
         * Its triggers a magic __set directed towards
         * VersionControl/Hg/Executable.php
         */
        $this->setExecutable();
    }

    /**
     * Proxy down to the command class
     *
     * This also allows programmers to use both
     * <code>$executables_object = $hg->executable;</code>
     * and
     * <code>$executables_object = $hg->getExecutable();</code>
     * to both return an instance of VersionControl_Hg_Executable, for example.
     *
     * @param string $method    The function being called
     * @param mixed  $arguments The parameters passed to that function
     *
     * @return VersionControl_Hg_Command_Abstract
     */
    public function __call($method, $arguments)
    {
        $value = ( empty($arguments) ) ? null : $arguments[0];

        $possible_prefix = strtolower(substr($method, 0, 3));
        $possible_object = strtolower(substr($method, 3));

        /* Very limited use:
         * $hg->getExecutable()->getPath() = $hg->executable->getPath()
         */
        switch ( $possible_prefix ) {
            case 'set':
                if ( $possible_object === 'repository' ) {
                    return VersionControl_Hg_Container_Repository::getInstance(
                        $this, $value
                    );
                } elseif ( $possible_object === 'executable' ) {
                    return VersionControl_Hg_Executable::getInstance($this, $value);
                } else {
                    throw new ErrorException(
                        "set{$possible_object} is not implemented"
                    );
                }
                break;
            case 'get':
                if ( $possible_object === 'repository' ) {
                    return VersionControl_Hg_Container_Repository::getInstance();
                } elseif ( $possible_object === 'executable' ) {
                    return VersionControl_Hg_Executable::getInstance();
                } else {
                    throw new ErrorException(
                        "get{$possible_object} is not implemented"
                    );
                }
                break;
            /* proxy to Hg/Command.php */
            default:
                /* must pass an instance of VersionControl_Hg to provide it with
                 * the executable and repository */
                $command = new VersionControl_Hg_CommandProxy($this);
                return call_user_func_array(array($command, $method), $arguments);
                break;
        }
    }

    /**
     * Returns an object, usually handled by a command
     *
     * A $name is a lowercase, short name of the object:
     * $hg->executable is an instance of VersionControl_Hg_Executable and can
     * be echoed to invoke __toString() to get a pertinent piece of metadata.
     *
     * Instead of calling:
     * <code>$hg->getVersion();</code>,
     * we simplify:
     * <code>$version = $hg->version</code>.
     *
     * @param string $name The object to get
     *
     * @return mixed
     */
    public function __get($name)
    {
        /* Instantiate the object corresponding to the short name
         * most are commands, some are top-level objects */
        switch ($name) {
            case 'repository':
                /* Singleton let's us use an instance or create a new one if
                 * not instantiated
                 *
                 * We're ok with a null argument here since to even use
                 * this, $hg would already have to be instantitated
                 * successfully with a repo argument.
                 */
                return VersionControl_Hg_Container_Repository::getInstance();
                break;
            case 'executable':
                /* Singleton let's us use an instance or create a new one if
                 * not instantiated */
                return VersionControl_Hg_Executable::getInstance();//null, $this
                break;
            default:
                /* its a command */
                $command = new VersionControl_Hg_CommandProxy($this);
                return call_user_func_array(array($command, $name), array());
                break;
        }
    }

    /**
     * Magic setter for properties of commands and more.
     *
     * @param string $name  The property's name
     * @param string $value The property's value
     *
     * @return mixed
     */
    public function __set($name, $value)
    {
        /* Instantiate the object corresponding to the short name
         * most are commands, some are top-level objects */
        switch ($name) {
            case 'repository':
                /* Singleton let's us use an instance or create a new one if
                 * not instantiated */
                return VersionControl_Hg_Container_Repository::getInstance(
                    $this, $value
                );
                break;
            case 'executable':
                /* Singleton let's us use an instance or create a new one if
                 * not instantiated */
                return VersionControl_Hg_Executable::getInstance($this, $value);
                break;
            default:
                /* its a command */
                $command = new VersionControl_Hg_CommandProxy($this);
                return call_user_func_array(array($command, $method), array());
                break;
        }
    }

    /**
     * Print out the class' properties
     *
     * @return string
     */
    public function __toString()
    {
        /* This automagically calls $this::__get() and then automagically
         * invokes VersionControl_Hg_Executable::__toString() */
        return 	'Executable: ' . $this->executable . "\r\n" .
                'Repository: ' . $this->repository . "\r\n" .
                'Version: ' . $this->version . "\r\n";
    }
}
