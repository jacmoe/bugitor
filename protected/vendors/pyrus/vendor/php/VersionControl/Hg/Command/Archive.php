<?php
/**
 * Contains the definition of the VersionControl_Hg_Repository_Command_Archive
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
 * $hg->archive('tip')->to('/home/myself/releases/')->with('tgz')->run();
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
class VersionControl_Hg_Command_Archive
    extends VersionControl_Hg_Command_Abstract
        implements VersionControl_Hg_Command_Interface
{
    /**
     * The name of the mercurial command implemented here
     *
     * @var string
     */
    protected $command = 'archive';

    /**
     * The path where the archive will be saved
     *
     * @var string
     */
    protected $destination;

    /**
     * Required options this command needs
     *
     * destination is always variable
     *
     * @var mixed
     */
    protected $required_options = array(
        'files' => null, //AKA destination
        'noninteractive' => null,
        'repository' => null,
    );

    /**
     * Possible options this command may have
     *
     * --type defaults to 'files'
     * --prefix defaults to ''
     * --rev defaults to tip
     *
     * @var mixed
     */
    protected $allowed_options = array(
        'rev' => null,
        'prefix' => null,
        'type' => null,
    );

    /**
     * Valid formats for the archive
     *
     * Keys are the 'type', while values are the file extensions.
     * This is dictated by the formats which Mercurial natively supports.
     *
     * LZMA would be nice.
     *
     * @var mixed
     */
    protected $archive_types = array(
        'files' => '',
        'tar' => 'tar',
        'bzip2' => 'tbz2',
        'gzip' => 'tgz',
        'zip' => 'zip',
        'uzip' => 'zip',
    );

    /**
     * Constructor
     *
     * @param mixed             $params Data values passed to the command
     * @param VersionControl_Hg $hg     Instance of the base object
     *
     * @return void
     */
    public function __construct($params = null, VersionControl_Hg $hg)
    {
        /* should always be called so we have a full array of valid options */
        $this->setOptions(array()); //should be renamed as joinPossibleOptions()

        /* Remap the options to match the function names with valid hg options
         * Normally, this would be handled by setOptions($params), but the names
         * such as 'revision' are not valid HG options, but are just so darn
         * semantic and "fluidic", that I can't give them up. Thus: */
        if ( array_key_exists(0, $params) ) {
            /* sometimes $params is not an array! */
            foreach ( $params[0] as $key => $value ) {
                switch ($key) {
                    case 'revision':
                    case 'to':
                    case 'with':
                    case 'prefix':
                        $this->$key($value);
                        break;
                    default:
                    /* default is to just ignore unkown ones */
                        break;
                }
            }
        }
    }

    /**
     * Sets the directory path to which the archive will be saved
     *
     * Must be the last option, like files
     *
     * @param string $directory is directory to which archives are saved
     *
     * @return VersionControl_Hg_Command_Abstract
     * @throws VersionControl_Hg_Repository_Command_Exception
     */
    public function to($directory)
    {
        /* empty paths not valid */
        if ( empty($directory) ) {
            throw new VersionControl_Hg_Repository_Command_Exception(
                VersionControl_Hg_Command_Exception::BAD_ARGUMENT,
                'You must supply a directory to archive to.' .
                'Instead, its empty.'
            );
        }

        /* test path's validity */
        if ( $directory != realpath($directory) ) {
            throw new VersionControl_Hg_Repository_Command_Exception(
                VersionControl_Hg_Command_Exception::BAD_ARGUMENT,
                "The canonical path '{$directory}' does not seem to exist on
                this server. "
            );
        }

        $this->destination = $directory;

        /* for the fluent api */
        return $this;
    }

    /**
     * Sets type of archive format.
     *
     * Valid values are 'files', 'tar', 'bzip2', 'gzip', 'zip', 'uzip'
     *
     * @param string $type is the archive type
     *
     * @return VersionControl_Hg_Command_Archive
     * @throws VersionControl_Hg_Repository_Command_Exception
     */
    public function with($type = 'files')
    {
        if ( ! array_key_exists($type, $this->archive_types)) {
            throw new VersionControl_Hg_Repository_Command_Exception(
                VersionControl_Hg_Command_Exception::BAD_ARGUMENT,
                "The {$type} archive type is not supported. "
            );
        }

        $this->addOption('type', $this->archive_types[$type]);

        /* for the fluent api */
        return $this;
    }

    /**
     * Specified the revision to restrict the archive operation to
     *
     * Usage:
     * <code>$hg->archive()->revision(7)->run();</code>
     * or
     * <code>$hg->archive(array('revision' => 7 ))->to('path/to)->run();</code>
     *
     * @param string $revision is the optional revision to archive
     *
     * @return void
     */
    public function revision($revision = 'tip')
    {
        //@TODO Technically, this shouldn't occur since 'tip' is default
        if ( empty($revision)) {
            throw new VersionControl_Hg_Command_Exception(
                VersionControl_Hg_Command_Exception::BAD_ARGUMENT
            );
        }

        $this->addOption('rev', $revision);

        return $this; //for the fluent API
    }

    /**
     * Adds 'prefix' to the stack of command line options
     *
     * Specifies a directory prefix to wrap the exported files to within the archive

     * Usage:
     * <code>$hg->archive()->prefix('My Project')->to('/path/to')->run();</code>
     * or
     * <code>
     * $hg->archive(array('prefix' => 'My Project'))->to('path/to)->run();
     * </code>
     *
     * @param string $prefix is the string prefixed to the directory within
     *                       the archive.
     *
     * @return void
     */
    public function prefix($prefix)
    {
        if ( empty($prefix) ) {
            throw new VersionControl_Hg_Command_Exception(
                VersionControl_Hg_Command_Exception::BAD_ARGUMENT,
                "A prefix must not be empty if you use this function or
                 option. "
            );
        }

        $this->addOption('prefix', $prefix);

        return $this; //for the fluent API
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
        /* take care of options passed in as such:
         * $hg->archive(array('revision' => 'tip', 'to' => realpath('../')));
         */
        if ( ! empty($params) ) {
            $this->setOptions($params);
        }

        /* --noninteractive is required since issuing the command is
         * unattended by nature of using this package.
         * --repository PATH is required since the PWD on which hg is invoked
         * will not be within the working copy of the repo. */
        $this->addOptions(
            array(
                'noninteractive' => null,
                'repository' => $this->hg->getRepository()->getPath(),
            )
        );

        /* If files: destination must not already exist! Else, Hg will report
         * "Permission denied"
         * This seems not to be the case anymore...at least with hg cli 1.8.2.
         * */
        /*if (is_file($destination) || is_dir($destination) ) {
            throw new VersionControl_Hg_Repository_Command_Exception(
                null,
                'The destination directory already exists, but it should not'
            );
        }*/

        /* build the full path to the archive.
         * Default name, hardcoded is: Test_Repository-r4.zip */
        $saved_to = $this->destination .
                   DIRECTORY_SEPARATOR .
                   '%b-r%R.' .
                   $this->options['type'];

        $this->addOption('files', $saved_to);

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

        return $saved_to;
    }
}
