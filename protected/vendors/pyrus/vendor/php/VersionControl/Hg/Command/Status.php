<?php
/**
 * Contains the definition of the Status command
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
 * Implements the status command.
 *
 * The codes used to show the status of files are:
 *  M = modified
 *  A = added
 *  R = removed
 *  C = clean
 *  ! = missing (deleted by non-hg command, but still tracked)
 *  ? = not tracked
 *  I = ignored
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
class VersionControl_Hg_Command_Status
    extends VersionControl_Hg_Command_Abstract
        implements VersionControl_Hg_Command_Interface
{
    /**
     * The name of the mercurial command implemented here
     *
     * @var string
     */
    protected $command = 'status';

    /**
     * Required options for this specific command. These may not be required
     * by Mercurial itself, but are required for the proper functioning of
     * this package.
     *
     * @var mixed
     */
    protected $required_options = array(
        'noninteractive' => null,
        'repository' => null,
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
        'all' => null,
        'modified' => null,
        'added' => null,
        'removed' => null,
        'deleted' => null,
        'clean' => null,
        //could be 'not tracked' or unversioned? but we need one word
        'unknown' => null,
        'ignored' => null,
        'files' => null,
        'rev' => null,
    );

    /**
     * Mapping between native Hg output codes and human readable outputs.
     *
     * @var mixed
     * @TODO add optional functionality for this to parent::parseOutput()
     */
    protected $output_map = array(
        'M' => 'modified',
        'A' => 'added',
        'R' => 'removed',
        'C' => 'clean',
        '!' => 'missing',
        //should be unknown here to match above, but HG docs use 'not tracked'
        '?' => 'not tracked', //or 'unversioned'
        'I' => 'ignored',
    );

    /**
     * Constructor
     *
     * @param mixed             $params One or more parameters for the command
     * @param VersionControl_Hg $hg     Instance of the base object
     *
     * @return void
     */
    public function __construct($params = null, VersionControl_Hg $hg)
    {
        /* should always be called so we have a full array of valid options */
        $this->setOptions($params);
    }

    /**
     * Execute the command and return the results.
     *
     * Running this with no arguments (eg. $hg->status()->run(); ) will only
     * show what Hg would show: only changes and not all files. Specify
     * status('all') or add all() to the method chain to get all files.
     *
     * @param mixed             $params Options passed to the Log command
     * @param VersionControl_Hg $hg     Instance of the base object
     *
     * @return string
     */
    public function execute(array $params = null, VersionControl_Hg $hg)
    {
        /* take care of options passed in as such:
         * $hg->status(array('revision' => 3, 'all' => null));
         * We need 'all' to be the key, and not have it interpreted as
         * 	revision => 3, 0 => all  */
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

        return $this->parseOutput(
            $this->output,
            array(array('status' => $this->output_map), 'file')
        );
    }

    /**
     * Adds 'all' to the stack of command line options
     *
     * Returns all files in the repository no matter their status.
     *
     * Usage:
     * <code>$hg->status()->all()->run();</code>
     * or
     * <code>$hg->status('all')->run();</code>
     *
     * @return VersionControl_Hg_Command_Status
     */
    public function all()
    {
        $this->addOption('all');

        /* for the fluent API */
        return $this;
    }

    /**
     * Adds 'modified' to the stack of command line options
     *
     * Returns only files which have been modified in the working copy.
     *
     * Usage:
     * <code>$hg->status()->modified();</code>
     * or
     * <code>$hg->status('modified')->run();</code>
     *
     * @return VersionControl_Hg_Command_Status
     */
    public function modified()
    {
        $this->addOption('modified');

        /* For the fluent API */
        return $this;
    }

    /**
     * Adds 'added' to the stack of command line options
     *
     * Returns only files newly added to the repository.
     *
     * Usage:
     * <code>$hg->status()->all()->run();</code>
     * or
     * <code>$hg->status('all')->run();</code>
     *
     * @return VersionControl_Hg_Command_Status
     */
    public function added()
    {
        $this->addOption('added');

        /* For the fluent API */
        return $this;
    }

    /**
     * Adds 'removed' to the stack of command line options
     *
     * Returns only files which have been removed from the working copy
     * and are no longer tracked by Mercurial.
     *
     * Usage:
     * <code>$hg->status()->removed()->run();</code>
     * or
     * <code>$hg->status('removed')->run();</code>
     *
     * @return VersionControl_Hg_Command_Status
     */
    public function removed()
    {
        $this->addOption('removed');

        /* For the fluent API */
        return $this;
    }

    /**
     * Adds 'deleted' to the stack of command line options
     *
     * Returns all files which have been deleted from the working copy.
     *
     * Usage:
     * <code>$hg->status()->deleted()->run();</code>
     * or
     * <code>$hg->status('deleted')->run();</code>
     *
     * @return VersionControl_Hg_Command_Status
     */
    public function deleted()
    {
        $this->addOption('deleted');

        /* For the fluent API */
        return $this;
    }

    /**
     * Adds 'clean' to the stack of command line options
     *
     * Returns files which have no changes; i.e. they are identical in both
     * the repository and working copy.
     *
     * Usage:
     * <code>$hg->status()->clean()->run();</code>
     * or
     * <code>$hg->status('clean')->run();</code>
     *
     * @return VersionControl_Hg_Command_Status
     */
    public function clean()
    {
        $this->addOption('clean');

        /* For the fluent API */
        return $this;
    }

    /**
     * Adds 'unknown' to the stack of command line options
     *
     * Returns all files not being tracked by Mercurial (i.e. not added).
     *
     * Usage:
     * <code>$hg->status()->unknown()->run();</code>
     * or
     * <code>$hg->status('unknown')->run();</code>
     *
     * @return VersionControl_Hg_Command_Status
     */
    public function unknown()
    {
        $this->addOption('unknown');

        /* For the fluent API */
        return $this;
    }

    /**
     * Adds'ignored' to the stack of command line options
     *
     * Returns only files ignored on purpose by Mercurial (in .hgignore)
     *
     * Usage:
     * <code>$hg->status()->ignored()->run();</code>
     * or
     * <code>$hg->status('ignored')->run();</code>
     *
     * @return VersionControl_Hg_Command_Status
     */
    public function ignored()
    {
        $this->addOption('ignored');

        /* For the fluent API */
        return $this;
    }

    /**
     * Adds a list of files to the stack of command line options
     *
     * List status information for only the files specified. Abstract::formatOptions
     * will automatically place this as the last option since a files list
     * must be the last item on the command line.
     *
     * Usage:
     * <code>$hg->status()->files(array('index.php'))->run();</code>
     * or
     * <code>$hg->status(array('files' => array('index.php')))->run();</code>
     *
     * @param mixed $files Only show the status for these files
     *
     * @return VersionControl_Hg_Command_Status
     *
     * @TODO how to ensure this is the final option??
     */
    public function files(array $files)
    {
        $this->addOption('files', join(' ', $files));

        /* For the fluent API */
        return $this;
    }

    /**
     * Adds 'copied' to the stack of command line options
     *
     * Returns only files copied within the working copy
     *
     * Usage:
     * <code>$hg->status()->copied()->run();</code>
     * or
     * <code>$hg->status('copied')->run();</code>
     *
     * @return VersionControl_Hg_Command_Status
     */
    public function copied()
    {
        $this->addOption('copied');

        /* For the fluent API */
        return $this;
    }

    /**
     * Adds 'rev' to the stack of command line options
     *
     * Specified the revision to restrict the status operation to

     * Usage:
     * <code>$hg->status('all')->revision(7)->run();</code>
     * or
     * <code>$hg->status(array('revision' => 7 ))->all()->run();</code>
     *
     * @param string $revision The revision for which to get the status
     *
     * @return VersionControl_Hg_Command_Status
     */
    public function revision($revision = 'tip')
    {
        //@TODO Technically, the following shouldn't occur since 'tip' is default
        if ( empty($revision)) {
            throw new VersionControl_Hg_Command_Exception(
                VersionControl_Hg_Command_Exception::BAD_ARGUMENT
            );
        }

        $this->addOption('rev', $revision);

        /* For the fluent API */
        return $this;
    }
}
