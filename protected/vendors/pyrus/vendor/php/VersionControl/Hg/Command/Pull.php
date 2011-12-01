<?php
/**
 * Contains the definition of the VersionControl_Hg_Repository_Command_Pull
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
 * Pull changesets from a remote repository
 *
 * Usage:
 *
 * Basic usage, assuming .hgrc has a default pull url:
 * <code>
 * $hg = new VersionControl_Hg('/path/to/repo');
 * $array_of_pulled_changesets = $hg->pull()->run('verbose');
 * </code>
 *
 * With credentials:
 * <code>
 * $hg->pull()->from('/remote/repo')->username('A')->password('B')->run();
 * </code>
 * or
 * <code>
 * $hg->pull()->from()->using(array('username' => 'A', 'password' => 'B'))->run();
 * </code>
 *
 * Explcititly pull 'all' changesets (Default):
 * <code>$hg->pull('all')->from('/remote/repo')->run();</code>
 * or
 * <code>$hg->pull()->all()->from('/remote/repo')->run();</code>
 *
 * Pull a specific revision:
 * <code>$hg->pull(23)->from('/remote/repo')->run();</code>
 * or
 * <code>$hg->pull()->revision(23)->from('/remote/repo')->run();</code>
 * or, a multiple revisions:
 *
 * or, a range of contiguous revisions:
 *
 *
 * Update the working copy after a pull:
 * <code>$hg->pull()->from('/remote/repo')->update('working copy')->run();</code>
 * Also, you may use 'fetch' to pull and update at the same time.
 *
 * Pull a specific branch:
 * <code>$hg->pull()->branch('mine')->from('/remote/repo')->run();</code>
 *
 * Pull a specific bookmark:
 * <code>$hg->pull()->bookmark('earlier')->from('/remote/repo')->run();</code>
 *
 * Force a pull:
 * <code>$hg->pull()->force()->from('/remote/repo')->run();</code>
 *
 * Results: Should return a list of changes pulled. See cognate: incoming().
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
class VersionControl_Hg_Command_Pull
    extends VersionControl_Hg_Command_Abstract
        implements VersionControl_Hg_Command_Interface
{
    /**
     * The name of the mercurial command implemented here
     *
     * @var string
     */
    protected $command = 'pull';

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
        'bookmark' => null,
        'branch' => null,
        'revision' => null,
        'from' => null,
        'username' => null,
        'password' => null,
        'all' => null,
        'update' => null,
        'force' => null,
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
        $this->hg = $hg;
        /* a repository must have been set already */
        $repository = $this->hg->repository->getPath();
        if ( empty($repository) ) {
            throw new VersionControl_Hg_Command_Exception(
                VersionControl_Hg_Command_Exception::BAD_ARGUMENT,
                "The repository must be defined either at
                 instantiation, as a string path arugment to clone()
                 or as the 'repository' key in an array of options."
            );
        }

        /* there may be no arguments passed at all */
        if ( (array_key_exists(0, $params)) && (! empty($params[0])) ) {
            /* $params could be a string: 'all' or a revision number/hash */
            if ( is_scalar($params[0]) ) {
                /* check for the 'all' key */
                if ( 'all' === $params[0] ) {
                    $this->all();
                } else {
                    $this->revision($params[0]);
                }
            } elseif ( is_array($params[0]) ) {
                /* should always be called so we have a full array of valid
                 * options */
                $this->setOptions($params);
            }
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
        /* take care of options passed into run() as such:
         * $hg->clone('/path/')->run('verbose'));
         */
        if ( ! empty($params) ) {
            $this->setOptions($params);
        }

        /* --noninteractive is required since issuing the command is
         * unattended by nature of using this package.
         *
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

        /*return $this->parseOutput(
            $this->output,
            array('rev', 'branch', 'files', 'datetime', 'author', 'description'),
            '##'
        );*/

        return $this->output;
    }

    /**
     * Specified the revision to restrict the log operation to
     *
     * Usage:
     * <code>$hg->pull('all')->revision(7)->run();</code>
     * or
     * <code>$hg->log(array('revision' => 7 ))->all()->run();</code>
     *
     * @param string $revision The revision for which to show the log for
     *
     * @return void
     */
    public function revision($revision = 'tip')
    {
        $this->addOption('rev', $revision);

        /* For the fluent API */
        return $this;
    }

    /**
     * Ensures all changesets are pulled.
     *
     * This is just DSL syntactic sugar. Mercurial's default is to pull all
     * changesets and does not even have an option to specifiy 'all'. It just
     * sounds good in a DSL since we also have the revision() modifier.
     *
     * Usage:
     * <code>$hg->pull()->all()->run();</code>
     * or
     * <code>$hg->pull('all')->run();</code>
     *
     * @return VersionControl_Hg_Command_Pull
     */
    public function all()
    {
        /* for the fluent API */
        return $this;
    }

    /**
     * Pull only code labled by the bookmark
     *
     * @param string $name The name of the bookmark
     *
     * @return VersionControl_Hg_Command_Abstract
     */
    public function bookmark($name)
    {
        /* Some basic validation */
        if ( empty($name) ) {
            throw new VersionControl_Hg_Command_Exception(
                VersionControl_Hg_Command_Exception::BAD_ARGUMENT,
                "The branch's name cannot be empty. "
            );
        }

        $this->addOption('bookmark', $name);

        /* For the fluent API */
        return $this;
    }

    /**
     * Pull revisions only in this branch
     *
     * @param string $name The name of the branch
     *
     * @return VersionControl_Hg_Command_Abstract
     */
    public function branch($name = 'default')
    {
        /* no validation needed since the 'default' branch always exists */
        $this->addOption('branch', $name);

        /* For the fluent API */
        return $this;
    }

    /**
     * Designate where to pull from
     *
     * @param string $source The repository url from which to pull
     *
     * @return VersionControl_Hg_Command_Abstract
     */
    public function from($source)
    {
        /* Some basic validation */
        if ( empty($source) ) {
            throw new VersionControl_Hg_Command_Exception(
                VersionControl_Hg_Command_Exception::BAD_ARGUMENT,
                "The source to pull from cannot be empty. "
            );
        }
        $this->addOption('files', $source);

        /* For the fluent API */
        return $this;
    }

    /**
     * Set a username for a remote repository
     *
     * @param string $username The username to use for authentication
     *
     * @return VersionControl_Hg_Command_Abstract
     * @todo Implement this!
     */
    public function username($username)
    {

        /* For the fluent API */
        return $this;
    }

    /**
     * Set a password for a remote repository
     *
     * @param string $password The password to use for authentication
     *
     * @return VersionControl_Hg_Command_Abstract
     * @todo Implement this!
     */
    public function password($password)
    {

        /* For the fluent API */
        return $this;
    }

    /**
     * Perform an update after a pull
     *
     * @param string $what What should be updated? Default is always 'Working
     *                     Copy' anyways.
     *
     * @return VersionControl_Hg_Command_Abstract
     *
     * @todo Implement this!
     */
    public function update($what = 'working copy')
    {


        /* For the fluent API */
        return $this;
    }

    /**
     * Force a pull no matter what
     *
     * @return VersionControl_Hg_Command_Abstract
     *
     * @todo Implement this!
     */
    public function force()
    {
        $this->addOption('force', null);

        /* For the fluent API */
        return $this;
    }
}
