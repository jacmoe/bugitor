<?php
/**
 * Contains the definition of the Version class
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
 * Provides Exceptions for commands (VersionControl_Hg_Command_Exception)
 */
require_once 'Exception.php';

/**
 * Provides an output formatter
 */
require_once 'Output/Formatter.php';

/**
 * Print the version of the HG executable in use
 *
 * Usage:
 * <code>
 * $hg = new VersionControl_Hg();
 * $hg->version()->run();
 *
 * $hg->version()->format('raw')->run();
 * </code>
 *
 * You may also echo the property since this command implements _toString():
 * <code>
 * $hg = new VersionControl_Hg();
 * echo $hg->version;
 * </code>
 * or
 * <code>
 * $hg = new VersionControl_Hg();
 * echo $hg->executable->version;
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
class VersionControl_Hg_Command_Version
    extends VersionControl_Hg_Command_Abstract
        implements VersionControl_Hg_Command_Interface
{
    /**
     * The name of the mercurial command implemented here
     *
     * @var string
     */
    protected $command = 'version';

    /**
     * Required options for this specific command. These may not be required
     * by Mercurial itself, but are required for the proper functioning of
     * this package.
     *
     * @var mixed
     */
    protected $required_options = array(
        'noninteractive' => null,
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
        'output' => null,
    );

    /**
     * Constructor
     *
     * @param mixed             $params One or more parameters to modify the command
     * @param VersionControl_Hg $hg     The base Hg instance
     *
     * @return void
     */
    public function __construct($params = null, VersionControl_Hg $hg)
    {
        $this->hg = $hg;

        /* should always be called so we have a full array of valid options */
        $this->setOptions(array()); //$params
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
         * $hg->cat('/file/')->run('verbose'));
         * Although, 'verbose|quiet' probably have no effect...?
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
            )
        );

        /* Despite its being so not variable, we need to set the command string
         * only after manually setting options and other command-specific data */
        $this->setCommandString();

        //var_dump($this->command_string);

        /* no var assignment, since 2nd param holds output */
        exec($this->command_string, $this->output, $this->status);

        if ( $this->status !== 0 ) {
            throw new VersionControl_Hg_Command_Exception(
                VersionControl_Hg_Command_Exception::COMMANDLINE_ERROR
            );
        }

        /* parse this->output for version string parts */
        $version = array();

        /* Regex to get the version between parenthesis */
        preg_match('!\(([^\)]+)\)!', $this->output[0], $version); //'/\((.*?)\)/',

        $version['raw'] = trim(str_replace('version', '', $version[1]));
        //break up string into version components
        //does the version have a date after the version number?
        if ( strstr($version['raw'], '+') ) {
            /* handle if the text after '+' is a changeset, not a date */
            $ver_parts = explode('+', $version['raw']);

            //@todo replace date_parse() this to remove dependency on Php 5.2.x
            if ( date_parse($ver_parts[1]) ) {
                $version['date'] = $ver_parts[1];
            } else {
                $version['changeset'] = $ver_parts[1];
            }
        } else {
            $ver_parts[0] = $version['raw'];
        }

        $version_tmp = explode('.', $ver_parts[0]);

        $version['major'] = $version_tmp[0];
        $version['minor'] = $version_tmp[1];
        $version['maintenance'] = $version_tmp[2];

        /* clean up from intermediate data */
        unset($version[0], $version[1]);

        return $version;
    }
}
