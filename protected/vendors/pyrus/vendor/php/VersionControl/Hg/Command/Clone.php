<?php
/**
 * Contains the definition of the VersionControl_Hg_Repository_Command_Clone
 * class
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
 * Provides the required interface for all commands
 */
require_once 'Interface.php';

/**
 * Provides base functionality common to all commands
 */
require_once 'Abstract.php';

/**
 * Provides Exceptions for commands (VersionControl_Hg_Command_Exception)
 */
require_once 'Exception.php';

/**
 * Clone a repository to a destination
 *
 * Usage:
 * <code>
 * $hg = new VersionControl_Hg('/path/to/repo');
 * $hg->clone('http://url/to/repo')->to('/path/to/clone')->run();
 * </code>
 *
 * NOTES
 * Should return the object representing the cloned repository as
 * type: VersionControl_Hg_Container_Repository.
 *
 * Should check if new location for cloned repo exists or not, and/or is
 * empty same as Init.php does.
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
class VersionControl_Hg_Command_Clone
    extends VersionControl_Hg_Command_Abstract
        implements VersionControl_Hg_Command_Interface
{
    /**
     * The name of the mercurial command implemented here
     *
     * @var string
     */
    protected $command = 'clone';

    /**
     * Required options for this specific command. These may not be required
     * by Mercurial itself, but are required for the proper functioning of
     * this package.
     *
     * @var mixed
     */
    protected $required_options = array(
        'noninteractive' => null,
        //'repository' => null,
        'files' => null,
    );

    /**
     * Permissable options.
     *
     * The actual option must be the key, while 'null' is a value here to
     * accommodate the current implementation of setting options.
     *
     * @var mixed
     */
    protected $allowed_options = array(
        /* --pull is used for safety since HG automatically uses hardlinks for
         repo data, although on some fs's, its not safe (eg. AFS) */
        'pull' => null,
        'sparse' => null,
        'rev' => null,
        'branch' => null,
    );

    /**
     * The path in which Mercurial will create the new repository
     *
     * @var string
     */
    protected $cloned_path;

    /**
     * Constructor
     *
     * @param mixed             $params One or more parameters to the command
     * @param VersionControl_Hg $hg     Instance of the base object
     *
     * @return void
     */
    public function __construct($params = null, VersionControl_Hg $hg)
    {
        $this->hg = $hg;

        /* check if a repository has been designated already or not */
        $cloned_path = $this->hg->repository->getPath();

        if ( empty($cloned_path) ) {
            /* are the argument(s) correctly formed? */
            if ( (array_key_exists(0, $params)) && (! empty($params[0])) ) {
                /* if its an array, check for the 'repository' key */
                if ( (is_array($params[0]))
                    && (! array_key_exists('repository', $params[0]))
                ) {
                    throw new VersionControl_Hg_Command_Exception(
                        VersionControl_Hg_Command_Exception::BAD_ARGUMENT,
                        "The repository must be defined either at
                         instantiation, as a string path arugment to clone()
                         or as the 'repository' key in an array of options."
                    );

                    /* should always be called so we have a full array of
                     * valid options */
                    $this->setOptions($params);
                } elseif ( is_scalar($params[0])) {
                    /* if scalar, we have to assume its a path */
                    /* This is a psuedo-hack because init has no arugment prefix;
                     * our current inmplementation of 'files' doesn't give
                     * one = cool! */
                    $this->repository($params[0]);
                }
            }
        } else {
            /* should always be called so we have a full array of valid options */
            $this->setOptions($params);
        }
    }

    /**
     * Execute the command and return the results.
     *
     * @param mixed             $params Options passed to the Log command
     * @param VersionControl_Hg $hg     Instance of the base object
     *
     * @return string
     */
    public function execute(array $params = null, VersionControl_Hg $hg)
    {
        /* Validate */
        $files = $this->getOption('files');
        /* the destination of the cloned repo must be index 1 */
        $cloned_path = $files[1];

        if (! $this->directory_exists($cloned_path) ) {
            /* kill any umasks. */
            //@TODO remove since it causes problems on multithreaded servers
            //umask(0);

            //refuse bad fs names
            //$except = array('\\', '/', ':', '*', '?', '"', '<', '>', '|');

            //Please note that when specifying the recursive option the
            // function returns false anyway if the directory already exists.
            //Octal permissions are ignored on Windows
            if (! mkdir($cloned_path, 0755, true) ) {
                throw new VersionControl_Hg_Command_Exception(
                    VersionControl_Hg_Command_Exception::BAD_ARGUMENT,
                    "PHP encountered an error while trying to create the
                     directory '{$cloned_path}'. "
                );
            }

            /* This works around some umask issues */
            chmod($cloned_path, 0755);
        } //clearstatcache()

        if ( ! is_writable($cloned_path) ) {
            throw new VersionControl_Hg_Command_Exception(
                VersionControl_Hg_Command_Exception::BAD_ARGUMENT,
                "The directory '{$cloned_path}' is not writable, but must be. "
            );
        }

        if ( ! $this->directory_is_empty($cloned_path) ) {
            throw new VersionControl_Hg_Command_Exception(
                VersionControl_Hg_Command_Exception::BAD_ARGUMENT,
                "The directory '{$cloned_path}' is not empty, but must be. "
            );
        }

        /* take care of options passed into run() as such:
         * $hg->clone('/path/')->run('verbose'));
         */
        if ( ! empty($params) ) {
            $this->setOptions($params);
        }

        /* --noninteractive is required since issuing the command is
         * unattended by nature of using this package.
        */
        $this->addOptions(
            array(
                'noninteractive' => null,
            )
        );

        /* Despite its being so not variable, we need to set the command string
         * only after manually setting options and other command-specific data */
        $this->setCommandString();

        /* no var assignment, since 2nd param holds output */
        exec($this->command_string, $this->output, $this->status);

        if ( $this->status !== 0 ) {
            throw new VersionControl_Hg_Command_Exception(
                VersionControl_Hg_Command_Exception::COMMANDLINE_ERROR
            );
        }
        $repository = VersionControl_Hg_Container_Repository::getInstance();
        $repository->setPath($cloned_path);

        return $this->hg->repository;
    }

    /**
     * Tells Mercurial to use the pull functionality to copy metadata.
     *
     * @return VersionControl_Hg_Command_Abstract
     */
    public function pull()
    {
        $this->addOption('pull', null);

        /* For the fluent API */
        return $this;
    }

    /**
     * Tells Mercurial to not create a working copy.
     *
     * @return VersionControl_Hg_Command_Abstract
     */
    public function sparse()
    {
        $this->addOption('noupdate', null);

        /* For the fluent API */
        return $this;
    }

    /**
     * Tells Mercurial to not create a working copy.
     *
     * @param string $path Destination of the clone operation
     *
     * @return VersionControl_Hg_Command_Abstract
     */
    public function to($path)
    {
        $files = $this->getOption('files');

        /* the repository to clone MUST be the first files item */
        $files[1] = $path;
        $this->addOption('files', $files);

        /* For the fluent API */
        return $this;
    }

    /**
     * Tells Mercurial to not create a working copy.
     *
     * @param string $path Destination of the clone operation
     *
     * @return VersionControl_Hg_Command_Abstract
     */
    public function repository($path)
    {
        $files = $this->getOption('files');

        /* the repository to clone MUST be the first files item */
        $files[0] = $path;
        $this->addOption('files', $files);

        /* For the fluent API */
        return $this;
    }

    /**
     * Confirm that the path exists
     *
     * @param string $path The path the repository should be created at
     *
     * @return boolean
     */
    protected function directory_exists($path)
    {
        $directory_exists = true;

        if (! is_dir($path) ) {
            //So, is it a file? Do we care?

            $directory_exists = false;
        }

        /* for the fluent API */
        return $directory_exists;
    }

    /**
     * Confirm that the path exists
     *
     * @param string $path The path the repository should be created at
     *
     * @return boolean
     */
    protected function directory_is_empty($path)
    {
        $directory_is_empty = true;

        $files_in_dir = scandir($path);

        /* ccount for . and .. */
        if ( count($files_in_dir) > 2 ) {
            $directory_is_empty = false;
        }

        /* for the fluent API */
        return $directory_is_empty;
    }

    /**
     * Specifies the revision to restrict the clone operation to
      *
     * Usage:
     * <code>$hg->clone()->revision(7)->run();</code>
     * or
     * <code>$hg->clone()->revision('cde1256adc443a3')->run();</code>
     * or
     * <code>$hg->clone(array('revision' => 7 ))->to('/path/to)->run();</code>
     *
     * @param string $revision is the optional revision to archive
     *
     * @return void
     */
    public function revision($revision = 'tip')
    {
        /* Technically, this shouldn't occur since 'tip' is default */
        if ( empty($revision)) {
            throw new VersionControl_Hg_Command_Exception(
                VersionControl_Hg_Command_Exception::BAD_ARGUMENT
            );
        }

        $this->addOption('rev', $revision);

        /* for the fluent API */
        return $this;
    }

}
