<?php
/**
 * Contains the definition of the Log command
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
 * @filesource
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
 * Provides Exceptions for commands
 */
require_once 'Exception.php';

/**
 * Implements the log command.
 *
 * The hg command-line client also uses 'history' as an alias.
 * * <code>
 * include_once 'VersionControl/Hg.php';
 * $hg = new VersionControl_Hg('/path/to/repository');
 * $hg->log()->run('verbose');
 * </code>
 *
 * Show only log entries for the following dates:
 * <code>
 * $hg->log()->on('Dec 27, 2010')->run();
 * $hg->log()->before('Dec 27, 2010')->run();
 * $hg->log()->after('Dec 27, 2010')->run();
 * $hg->log()->between('Dec 27, 2010', '2010-12-31')->run();
 * </code>
 *
 * Show only log entries for revision 2:
 * <code>
 * $hg->log()->revision('2')->format('raw')->run('verbose');
 * $hg->log()->revision('2')->run('verbose');
 * </code>
 *
 * Show only log entries for specific files:
 * <code>
 * $hg->log()->files(array('index.php'))->run();
 * </code>
 *
 * Exclude log entries for certain files:
 * <code>
 * $hg->log()->excluding('**.php')->run();
 * </code>
 *
 * This displays nothing; excluding takes precedence. Remove excluding() to
 * get only index.php:
 * <code>
 * $hg->log()->files(array('index.php'))->excluding('**.php')->run();
 * </code>
 *
 * Include specific files, which might not show up otherwise. Using this with
 * files can lead to unexpected results:
 * <code>
 * $hg->log()->including('**.php')->run();
 * </code>
 *
 * Patching and diffing options are not available in this class as they might
 * be when using the command line Mercurial client.
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
 * @example    test_Log.php Some examples to use
 */
class VersionControl_Hg_Command_Log
    extends VersionControl_Hg_Command_Abstract
        implements VersionControl_Hg_Command_Interface
{
    /**
     * The name of the mercurial command implemented here
     *
     * @var string
     */
    protected $command = 'log';

    /**
     * Template which Mercurial uses to ouput data
     *
     * @var string
     */
    protected $template = '{rev}##{branch}##{files}##{date}##{author}##{desc}\r\n';

    /**
     * Required options for this specific command
     *
     * These may not be required by Mercurial itself, but are required for the
     * proper functioning of VersionControl_Hg.
     *
     * @var mixed
     */
    protected $required_options = array(
        'noninteractive' => null,
        'repository' => null,
        'template' => null,
    );

    /**
     * Allowed options for this specific command
     *
     * The actual option must be the key, while 'null' is a value here to
     * accommodate the current implementation of setting options.
     *
     * @var mixed
     */
    protected $allowed_options = array(
        'follow' => null,
        'at' => null,
        'on' => null,
        'between' => null,
        'files' => null, // implement with search_for() / searchFor / search
        'keyword' => null,
        'limit' => null,
        'branch' => null, //or should we force a branch selection first?
        'copies' => null, //show copied files
        'removed' => null, //show removed files
        'date' => null,
        'rev' => null,
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

        $this->addOption('template', "$this->template");
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
            array('rev', 'branch', 'files', 'datetime', 'author', 'description'),
            '##'
        );
    }

    /**
     * Specified the revision to restrict the log operation to
     *
     * Usage:
     * <code>$hg->log('all')->revision(7)->run();</code>
     * or
     * <code>$hg->log(array('revision' => 7 ))->all()->run();</code>
     *
     * @param string $revision The revision for which to show the log for
     *
     * @return void
     */
    public function revision($revision = 'tip')
    {
        //@TODO Technically, the following shouldn't occur since 'tip' is default
        if ( empty($revision)) {
            throw new VersionControl_Hg_Command_Exception(
                VersionControl_Hg_Command_Exception::BAD_ARGUMENT
            );
        }

        //@TODO Not sure of the value of this here, instead of a raw string
        /*require_once realpath('../Container/Repository/Revision.php');
        $revision_instance = new VersionControl_Hg_Repository_Revision(
            $revision
        );*/

        $this->addOption('rev', $revision); //$revision_instance

        /* For the fluent API */
        return $this;
    }

    /**
     * Returns only files which have been removed from the working copy
     * and are no longer tracked by Mercurial.
     *
     * Usage:
     * <code>$hg->log()->removed()->run();</code>
     * or
     * <code>$hg->log('removed')->run();</code>
     *
     * @return null
     */
    public function removed()
    {
        $this->addOption('removed');
        return $this; //for the fluent API
    }

    /**
     * Returns only files copied within the working copy
     *
     * Usage:
     * <code>$hg->log()->copied()->run();</code>
     * or
     * <code>$hg->log('copied')->run();</code>
     *
     * @return null
     */
    public function copied()
    {
        $this->addOption('copied');
        return $this; //for the fluent API
    }

    /**
     * List status information for only the files specified.
     *
     * VersionControl_Hg_Command_Abstract::formatOptions will automatically
     * make this the last option since a files list must be the last item on
     * the command line.
     *
     * Usage:
     * <code>$hg->log()->files(array('index.php'))->run();</code>
     * or
     * <code>$hg->log(array('files' => array('index.php')))->run();</code>
     *
     * @param mixed $files the list of files as a simple array
     *
     * @return null
     */
    public function files(array $files)
    {
        $this->addOption('files', join(' ', $files));

        /* for the fluent API */
        return $this;
    }

    /**
     * Restricts the log to changesets only commited on $date
     *
     * Usage:
     * <code>$hg->log()->on('Dec 27, 2010')->run();</code>
     *
     * @param string $date Show log entries only for this exact date
     *
     * @return VersionControl_Hg_Command_Log
     */
    public function on($date)
    {
        $this->addOption(
            'date',
            "\"" . date('Y-m-d G:i:s', strtotime($date)) . "\""
        );

        return $this;
    }

    /**
     * Restricts the log to changesets only commited before $date
     *
     * Usage:
     * <code>
     * * $hg->log()->before('Dec 27, 2010')->run();</code>
     *
     * @param string $date Show log entries only before this date
     *
     * @return VersionControl_Hg_Command_Log
     */
    public function before($date)
    {
        $this->addOption(
            'date',
            "\"<" . date('Y-m-d G:i:s', strtotime($date)) . "\""
        );

        return $this;
    }

    /**
     * Restricts the log to changesets only commited after $date
     *
     * Usage:
     * <code>
     * $hg->log()->after('Dec 27, 2010')->run();</code>
     *
     * @param string $date Show log entries only after this date
     *
     * @return VersionControl_Hg_Command_Log
     */
    public function after($date)
    {
        $this->addOption(
            'date',
            "\">" . date('Y-m-d G:i:s', strtotime($date)) . "\""
        );

        return $this;
    }

    /**
     * Restricts log output to changesets between dates $from and $to
     *
     * Usage:
     * <code>$hg->log()->between('Dec 27, 2010', '2010-12-31')->run();</code>
     *
     * @param string $from Show log entries from this date forward
     * @param string $to   Show log entries only before this date
     *
     * @return VersionControl_Hg_Command_Log
     */
    public function between($from, $to)
    {
        /* validate inputs */
        //if ( empty() ) {

        $this->addOption(
            'date',
            "\"" . date('Y-m-d G:i:s', strtotime($from)) . " to " .
            date('Y-m-d G:i:s', strtotime($to)) . "\""
        );

        return $this;
    }
}
