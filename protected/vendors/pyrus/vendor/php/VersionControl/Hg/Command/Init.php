<?php
/**
 * Contains the definition of the VersionControl_Hg_Repository_Command_Init
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
 * Exports repository to a (optionally compressed) archive file.
 *
 * Usage:
 * <code>
 * $hg->init('path/to/new/repo')->run();
 * </code>
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
class VersionControl_Hg_Command_Init
    extends VersionControl_Hg_Command_Abstract
        implements VersionControl_Hg_Command_Interface
{
    /**
     * The name of the mercurial command implemented here
     *
     * @var string
     */
    protected $command = 'init';

    /**
     * The path in which Mercurial will create the new repository
     *
     * @var string
     */
    protected $path;

    /**
     * Required options this command needs
     *
     * destination is always variable
     *
     * @var mixed
     */
    protected $required_options = array(
        'noninteractive' => null,
        'files' => null,
    );

    /**
     * Possible options this command may have
     *
     * None, so far.
     *
     * @var mixed
     */
    protected $allowed_options = array();

    /**
     * Constructor
     *
     * @param mixed             $params Data passed to the command
     * @param VersionControl_Hg $hg     Instance of the base object
     *
     * @return void
     */
    public function __construct($params = null, VersionControl_Hg $hg)
    {
        $this->hg = $hg;

        /* check if a repository has been designated already or not */
        $path = $this->hg->repository->getPath();

        if ( empty($path) ) {
            /* are the argument(s) correctly formed? */
            if ( (array_key_exists(0, $params))
                && (! empty($params[0]))
            ) {
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
                    $this->addOption('files', $params[0]);
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
        $path = $this->getOption('files');

        if (! $this->directory_exists($path) ) {
            /* kill any umasks. */
            //@TODO remove since it causes problems on multithreaded servers
            //umask(0);

            //refuse bad fs names
            //$except = array('\\', '/', ':', '*', '?', '"', '<', '>', '|');

            //Please note that when specifying the recursive option the
            // function returns false anyway if the directory already exists.
            //Octal permissions are ignored on Windows
            if (! mkdir($path, 0755, true) ) {
                throw new VersionControl_Hg_Command_Exception(
                    VersionControl_Hg_Command_Exception::BAD_ARGUMENT,
                    "PHP encountered an error while trying to create the
                     directory '{$path}'. "
                );
            }

            /* This works around some umask issues */
            chmod($path, 0755);
        } //clearstatcache()

        if ( ! is_writable($path) ) {
            throw new VersionControl_Hg_Command_Exception(
                VersionControl_Hg_Command_Exception::BAD_ARGUMENT,
                "The directory '{$path}' is not writable, but must be. "
            );
        }

        if ( ! $this->directory_is_empty($path) ) {
            throw new VersionControl_Hg_Command_Exception(
                VersionControl_Hg_Command_Exception::BAD_ARGUMENT,
                "The directory '{$path}' is not empty, but must be. "
            );
        }

        /* take care of options passed in as such:
         * $hg->init('/path/')->run('verbose'));
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
                //'repository' => $this->path,
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
        $repository->setPath($path);

        return $this->hg->repository;
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

}
