<?php
/**
 * Contains definition of the Repository class
 *
 * PHP version 5
 *
 * @category   VersionControl
 * @package    Hg
 * @subpackage Container
 * @author     Michael Gatto <mgatto@lisantra.com>
 * @copyright  2011 Lisantra Technologies, LLC
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       http://pear.php.net/package/VersionControl_Hg
 */

/**
 * Provides the abstraction for containers
 */
require_once 'Abstract.php';

/**
 * Provides the container interface
 */
require_once 'Interface.php';

/**
 * Provides the container exception
 */
require_once 'Repository/Exception.php';

/**
 * The Mercurial repository
 *
 * Usage:
 * All calls are proxied from Hg
 * <code>
 * $hg = new VersionControl_Hg('/path/to/repo');
 * $repository = $hg->getRepository()->getPath();
 * </code>
 * or
 * <code>$repository = $hg->repository->delete();</code>
 *
 * PHP version 5
 *
 * @category   VersionControl
 * @package    Hg
 * @subpackage Container
 * @author     Michael Gatto <mgatto@lisantra.com>
 * @copyright  2011 Lisantra Technologies, LLC
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       http://pear.php.net/package/VersionControl_Hg
 */
class VersionControl_Hg_Container_Repository
    extends VersionControl_Hg_Container_Abstract
        implements VersionControl_Hg_Container_Interface
{
    /**
     * The name of all Mercurial repository roots.
     *
     * Leading backslash is needed since path may not have a trailing slash.
     *
     * @const string
     */
    const ROOT_NAME = '.hg';

    /**
     * Base class in this package
     *
     * Provides ability to call commands
     *
     * @var VersionControl_Hg
     */
    protected $hg;

    /*
     * Hold an instance of the class
     */
    private static $_instance;

    /**
     * Path to a local Mercurial repository
     *
     * @var string
     */
    protected $path;

    /**
     * Repository constructor which currently does nothing.
     *
     * @param VersionControl_Hg $hg   is the root object and as a singleton
     *                                will always be an instance of
     *                                VersionControl_Hg
     * @param string            $path is the full path to the user defined
     *                                executable
     *
     * @return void
     */
    private function __construct(VersionControl_Hg $hg, $path)
    {
        $this->setPath($path);
        $this->hg = $hg;
    }

    /**
     * The singleton method
     *
     * @param object $hg   Instance of VersionControl_Hg
     * @param string $path The path to the executable to use
     *
     * @return VersionControl_Hg_Repository
     */
    public static function getInstance($hg = null, $path = null)
    {
        if ( ! isset(self::$_instance) ) {
            $singleton_class = __CLASS__;
            self::$_instance = new $singleton_class($hg, $path);
        }

        return self::$_instance;
    }

    /**
     * FOR UNIT TESTING OF THIS SINGLETON, ONLY!
     *
     * @return null
     */
    public static function reset()
    {
        self::$_instance = null;
    }

    /**
     * Sets the path of a Mercurial repository after validating it as a Hg
     * repository
     *
     * @param string $path The path to the hg executable
     *
     * @see self::$path
     *
     * @return VersionControl_Hg
     */
    public function setPath($path = null)
    {
        /* not passing in a path is OK, especially since the programmer may
         * want to call create() */
        if ( is_null($path) ) {
            return;
        }

        if (is_array($path)) {
            $path = $path[0];
        }

        //is it even a real path?
        if ( ! realpath($path)) {
            throw new VersionControl_Hg_Container_Repository_Exception(
                VersionControl_Hg_Container_Repository_Exception::DOES_NOT_EXIST
            );
        }

        /*
         * Let's not guess that the user wants to create a repo if none exists;
         * Throw and exception and let them decide what to do next.
         * Maybe they just gave the wrong path.
         *
         * Line breaks are transmitted to CLI apps; concat the strings to
         * ignore them in output.
         */
        if ( ! $this->isRepository($path)) {
            throw new VersionControl_Hg_Container_Repository_Exception(
                VersionControl_Hg_Container_Repository_Exception::NO_REPOSITORY
            );
        }

        $this->path = $path;

        /* For fluid API */
        return $this;
    }

    /**
     * Checks if $this is in fact a valid
     *
     * @param string $path The full repository path.
     *
     * @return boolean
     */
    protected function isRepository($path)
    {
        /*
         * @todo a valid repo has this structure, so test for this:
         * .hg
         *  |---store/
         *        |---data/ (directory)
         *  |---dirstate (file)
         */

        $is_repository = false;

        $repository = $path . DIRECTORY_SEPARATOR . self::ROOT_NAME;

        /* both conditions must be satisfied. */
        if (is_dir($repository) && (! empty($repository))) {
            $is_repository = true;
        }

        return $is_repository;
    }

    /**
     * Create a repository
     *
     * This would make sense in only a very few isntances. For example, if a
     * programmer instantiated $hg without a path, and did this:
     * <code>
     * $hg = new VersionControl_Hg();
     * $repository = $hg->repository()->create('/path/to/repo');
     * </code>
     *
     * However, its probably better to directly use the Init command, since
     * this create() function merely proxies it.
     *
     * @param string $path The path at which to create a new Mercurial
     *                     repository.
     *
     * @return VersionControl_Hg_Container_Repository
     */
    public function create($path)
    {
        /* Init assumes it will have an array as an argument since the args
         * are usually passed into it by call_user_func_array() */
        $command = new VersionControl_Hg_Command_Init(array($path));
        $repository = $command->run('verbose');

        //@TODO It needs $hg to be passed in...
        /* return it so we can chain it */
        return $repository;
    }

    /**
     * Deletes the repository, but not the working copy
     *
     * On Windows, this will fail if any process has a lock on the repository's
     * directory; for example, if the repository is open in Windows Explorer.
     *
     * @return boolean
     */
    public function delete()
    {
        //validate first with is_dir(); ? It may have been deleted by another
        //process, you know.

        //is_writable($this->path)
        // or is_writable($this->path . DIRECTORY_SEPARATOR . self::ROOT_NAME)

        //chdir to outside the repo to avoid unlink error if the user is
        //running this inside the dir or something...but when/how would this
        //actually occur?
        /*	$old = getcwd(); // Save the current directory
            chdir($path_to_file);
            unlink($filename);
            chdir($old); // Restore the old working directory
        */

        //This may be necessary sometimes:
        //chown($TempDirectory."/".$FileName,666);
        //Insert an Invalid UserId to set to Nobody Owner;
        //666 is my standard for "Nobody"

        /* Destroy the physical filesystem */
        if ( unlink($this->path . DIRECTORY_SEPARATOR . self::ROOT_NAME) ) {
            /* Remove the path from the object */
            self::reset();

            return true;
        } else {
            /* Its hard to see how this 'else' code would ever execute */
            throw new VersionControl_Hg_Container_Repository_Exception(
                'The repository could not be deleted.'
            );
            /*@TODO or, return false */
        }
    }

    /**
     * Prevent users to clone the instance
     *
     * @return Exception
     */
    public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }
}
