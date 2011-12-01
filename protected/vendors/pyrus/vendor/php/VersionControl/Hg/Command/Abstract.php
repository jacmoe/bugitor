<?php
/**
 * Contains definition for the Abstract class
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
 *  Include custom exception object
 */
require_once 'Exception.php';

/**
 * Include optional formatting for XML, JSON, etc.
 */
require_once 'Output/Formatter.php';

/**
 * Gathers common code needed by all Command implementations
 *
 * implements the following global options:
 * -I --include    include names matching the given patterns
 * -X --exclude    exclude names matching the given patterns
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
abstract class VersionControl_Hg_Command_Abstract
{
    /**
     * Success or failure of the command.
     *
     * @var boolean
     */
    protected $status;

    /**
     * Holds returned data from the Mercurial shell command.
     *
     * @var array
     */
    protected $output;

    /**
     * Stores the output format for the returned data from the cli
     *
     * @var string
     */
    protected $output_format;

    /**
     * Object representing the base hg object command operates on behalf of
     *
     * @var VersionControl_Hg
     */
    protected $hg;

    /**
     * The string of the full command executed by Mercurial
     *
     * @var string
     */
    protected $command_string;

    /**
     * All possible options the command may receive.
     *
     * Its constructed by merging $valid_options, $allowed_options,
     * and $required_options
     *
     * @var mixed
     */
    protected $valid_options = array();

    /**
     * Options which all commands may have.
     *
     * Child classes should not override this property, nor add elements to it.
     * I wish the final keyword could be applied to properties. I could,
     * actually make it private with a public accessor, but the 'final'
     * keyword would be so much cleaner.
     *
     * @var array
     */
    protected $global_options = array(
        'encoding' => null,
        'quiet' => null,
        'verbose' => null,
        'include' => null,
        'exclude' => null,
        'cwd' => null,
        'format' => null,
    );

    /**
     * Non-required options this command may receive
     *
     * @var mixed
     */
    protected $optional_options = array();

    /**
     * Required options this command needs
     *
     * @var mixed
     */
    protected $required_options = array();

    /**
     * The current options applied to the Hg executable.
     *
     * @var mixed
     */
    protected $options = array();

    /**
     * Class constructors must be redefined in each Command parent class,
     * since it must have its dependencies for $hg injected.
     *
     * @param mixed             $params Options passed to the command
     * @param VersionControl_Hg $hg     Instance of the base object
     *
     * @return void
     */
    abstract function __construct($params, VersionControl_Hg $hg);

    /**
     * Executes the actual mercurial command
     *
     * For example, the programmer writes <code>$hg->archive('tip');</code>.
     * 'archive' and its parameter 'tip' are passed by a series of __call()
     * invocations. 'archive' is used to identify the class which implements
     * the command, while its parameter will be used in the constructor. We
     * have to be a little rigid and say that archive()'s only parameter can
     * be the revision we want to archive.
     *
     * `run()` is used to trigger execution. But, it is a virtual function:
     * i.e. it will always be intercepted by __call in
     * VersionControl_Hg_Repository_Command.
     *
     * @param string $method    The function called in the fluent API after the
     *                          base command Class is called/instantiated.
     * @param mixed  $arguments The function's arguments, if any.
     *
     * @return mixed
     * @throws VersionControl_Hg_Command_Exception
     */
    public function __call($method, $arguments)
    {
        /* $arguments is an array which may be empty if $hg->command() [->run()]
           has no parameters */
        switch ($method) {
            case 'run': //the special method ending the fluent chain
                /* run the command class' execute method
                 * interface demands all command classes define this method
                 */
                return $this->execute($arguments);

                break;
            default:
                /* must be the command or one of its fluent api functions */
                if ( method_exists($this, $method) ) {
                    /* is it a method of the currently instantiated command? */
                    return call_user_func_array(array($this, $method), $arguments);
                } else {
                    /* an optional method is not defined in the command class */
                    throw new VersionControl_Hg_Command_Exception(
                        "This method '{$method}' does not exist in this class"
                    );
                }
        }
    }

    /**
     * Exclude files and / or directories from consideration.
     *
     * This option is available for commands which operate on both working
     * copies and repositories, thus its abstraction.
     *
     * Mercurial expects the pattern to start with 'glob: ' or 're: '.
     *
     * @param string $filter The pattern of filenames to exlude
     *
     * @return VersionControl_Hg_Command
     *
     * @todo refactor out to Hg/Command/Filter/Excluding.php
     */
    public function excluding($filter)
    {
        $this->addOption(
            'exclude', "{$filter}"
            // $this->hg->repository . DIRECTORY_SEPARATOR . $filter
        );

        /* let me be chainable! */
        return $this;
    }

    /**
     * Include files and / or directories from consideration
     *
     * This option is available for commands which operate on both working
     * copies and repositories, thus its abstraction.
     *
     * Mercurial expects the pattern to start with 'glob: ' or 're: '.
     *
     * @param string $filter The pattern of filenames to include
     *
     * @return VersionControl_Hg_Command
     *
     * @todo refactor out to Hg/Command/Filter/Including.php
     */
    public function including($filter)
    {
        //@todo escapeshellarg()
        /* Must have full path to repository;
         * @TODO is the root only needed? Is this recursive */
        $this->addOption(
            'include', "{$filter}"
            //$this->hg->repository . DIRECTORY_SEPARATOR . $filter
        );

        /* let me be chainable! */
        return $this;
    }

    /**
     * Format the output
     *
     * Usual output formats are Raw, (PHP) Array, and JSON.
     *
     * @param string $format The format identifier: 'array', 'raw', 'json'.
     *
     * @return VersionControl_Hg_Command_Abstract
     */
    public function format($format = null)
    {
        /* make 'array' the default */
        if ( empty($format) ) {
            $format = 'array';
        } else {
            /* give programmer some slack and just accept any case! */
            $format = strtolower($format);
        }

        if ( ! in_array($format, VersionControl_Hg_Command_Output_Formatter::$formats) ) {
            $formats = join(', ', VersionControl_Hg_Command_Output_Formatter::$formats);

            throw new VersionControl_Hg_Command_Exception(
                VersionControl_Hg_Command_Exception::BAD_ARGUMENT,
                "The format must be either blank or be one of the following:
                 {$formats} You passed the value '{$format}', instead. "
            );
        }

        $this->addOption('format', $format);

        /* let me be chainable! */
        return $this;
    }

    /**
     * Processes the options specified in client code and populates
     * $valid_options class member if it wasn't already set.
     *
     * @param array $options the options to set
     *
     * @return void
     * @throws void
     */
    protected function setOptions(array $options)
    {
        if ( empty($this->valid_options) ) {
            $this->valid_options = array_merge(
                $this->allowed_options,
                $this->global_options,
                $this->required_options
            );
        }

        /* $param[0] causes a Php Notice when its an empty array without this
         * topmost check
         */
        if ( count($options) > 0 ) {
            /* redefine $options; 0th index because __call shunts all args
             * into an array.
             */
            $options = $options[0];

            if ( is_array($options) ) {
                //@todo how to handle $hg->status(array('removed', 'deleted'))
                //      recurse?
                $keys = array_keys($options);
                /* reassign params so the values become string keys and
                 * replace the numeric values with nulls for options
                 */
                if ( is_numeric($keys) ) {
                    $options = array_flip($options);
                    foreach ( $options as $key => $value ) {
                        $options[$key] = null;
                    }
                }
                $this->addOptions($options);
            } elseif ( is_string($options) ) {
                //addOption() checks for validity
                $this->addOption($options, null);
            }
        }
    }

    /**
     * Formats the options to a string in CLI style: ' --option [ = value]'
     *
     * @param mixed $options the options in an array to format
     *
     * @return string the formatted options
     */
    protected function formatOptions(array $options)
    {
        $modifiers = null;

        /* Ensure all required options are defined */
        $missing_required_options
            = array_diff_key($this->required_options, $options);
        if ( count($missing_required_options) > 0 ) {
            throw new VersionControl_Hg_Command_Exception(
                VersionControl_Hg_Command_Exception::BAD_ARGUMENT,
                "Required option(s) are missing: " .
                implode(', ', $missing_required_options)
            );
        }

        /* add a --cwd to avoid multitides of "XYZ not under root" errors
         * emanating from hg's cli */
        if ( ! empty($this->hg->repository) ) {
            /* Init doesn't need this  */
            $options['cwd'] = $this->hg->repository;
        }

        /* This option is internal, and must not be printed to the command line */
        if ( array_key_exists('format', $options) ) {
            $this->output_format = $options['format'];
            unset($options['format']);
        }

        /* Sort options so a files list is the last
         * array_key_exists() is the fastest; 300 X better than a uksort() */
        if ( array_key_exists('files', $options) ) {
            /*  */
            $files = $options['files'];
            unset($options['files']);

            /* handle files as an array; useful mostly for 'clone' */
            if ( is_array($files)) {
                $files = join(' ', $files);
            }

            /* Null key, since we want just a list without a --files argument.
             * A blank key like [] will give a cli option of --0 */
            $options[null] = $files;
        } //must be last option modification

        /* good, we have all required options, so let's format them */
        foreach ($options as $option => $argument) {
            /* Some options _might_ have a null key, like 'files' */
            $modifiers .= (! empty($option)) ? "--{$option}" : '';

            /* This is why we have nulls as values for options which do not
             * have arguments. A better way later may be checking is_null()
             * and remove the extra space, but the Hg executable does not seem
             * to mind extra spaces in the command line. */
            $modifiers .= ' ' . $argument . ' '; //always want a space before it
        }

        return $modifiers;
    }

    /**
     * Add an option
     *
     * @param string $name  The name of the option which Mercurial recognizes
     * @param string $value Optional since not all Hg options need a value
     *
     * @return  boolean
     * @throws  VersionControl_Hg_Command_Exception
     */
    protected function addOption($name, $value = null)
    {
        /* Construct all options */
        if ( empty($this->valid_options) ) {
            $this->valid_options = array_merge(
                $this->allowed_options,
                $this->global_options,
                $this->required_options
            );
        }

        if ( ! array_key_exists($name, $this->valid_options) ) {
            throw new VersionControl_Hg_Command_Exception(
                VersionControl_Hg_Command_Exception::BAD_ARGUMENT,
                "The option '{$name}' is not an valid option"
            );
        }

        if ( is_object($value) ) {
            $type = get_class($value);

            $value = print_r($type, true);
        }

        $this->options[$name] = $value;

        return true;
    }

    /**
     * Add a set of options all at once
     *
     * @param mixed $options The collections of options to pass to the command
     *
     * @return boolean
     */
    protected function addOptions(array $options)
    {
        //@todo is this necessary since array is hinted in the signature?
        if ( ! is_array($options)) {
            throw new VersionControl_Hg_Command_Exception(
                'Options is not an array'
            );
        }

        foreach ($options as $name => $value) {
            $this->addOption($name, $value);
        }

        return true;
    }

    /**
     * Return all the options currently defined for the command
     *
     * @return mixed
     */
    public function getOptions()
    {
        //@todo check that defined options satisfy $required_options
        return $this->options;
    }

    /**
     * Get a named options from the current list of options
     *
     * @param string $name The name of the option we are getting
     *
     * @return string
     */
    public function getOption($name)
    {
        $value = null;

        /* with 'files', sometimes it may not exist when called for */
        if ( isset($this->options[$name]) ) {
            $value = $this->options[$name];
        }

        return $value;
    }

    /**
     * Remove an option from the command by its name
     *
     * @param string $option The single option to pass to the command
     *
     * @return boolean is false when $option does not exist in the options array
     */
    public function unsetOption($option)
    {
        $unset = false;

        if ( array_key_exists($this->getOptions(), $option) ) {
            /* unset is a language construct and returns void, so no shortcuts
             * like if( unset(...) = array_key_exists(...) ) */
            unset( $this->_options[$option] );
            $unset = true;
        }

        return $unset;
    }

    /**
     * Parses the result of the Mercurial CLI operation into a semantic
     * associative array
     *
     * @param mixed  $output    The output to parse into an array of arrays
     * @param mixed  $fields    The labels of columns;
     * @param string $delimiter Delimits the fields returned by Mercurial.
     *                          Defaults to whitespace.
     *
     * @return mixed
     */
    protected function parseOutput(array $output, $fields = null, $delimiter = '\s')
    {
        if ( ! empty($this->output_format) ) {
            $formatter = new VersionControl_Hg_Command_Output_Formatter();
            $method =  'to' . ucfirst($this->output_format);

            /* if 'raw', we shortcircuit processing right here, since now,
             * $output is merely an array of one element per line of output
             * from the Hg cli, which is much easier to put back together as
             * raw text rather than splitting it below and THEN reassembling
             * it! */
            if ( $this->output_format === 'raw' ) {
                //@TODO I like only a single return per function.
                return $formatter->$method($output);
            }
        }

        /* Begin parsing each column of data into an array element in an
         * array of arrays. */
        $parsed_output = array();

        foreach ( $output as $line ) {
            /* split each line into an array of columns by any type of
             * white space character repeated any number of times. */
            //@TODO '/[\s]+/' is better?
            $bundle = preg_split("/{$delimiter}/", $line);

            /* replace the numeric key with a field label
             * a list() idiom might be best here */
            if ( ! empty($fields) ) {
                /* counts of field and output lengths must match. */
                // @TODO meh, this comparison is done on each loop!
                if ( count($fields) !== count($bundle) ) {
                    throw new VersionControl_Hg_Command_Exception(
                        VersionControl_Hg_Command_Exception::MISMATCHED_FIELDS
                    );
                }

                /* loop through the array of columns for this specific line
                 * and format! */
                foreach ( $bundle as $key => $value ) {
                    /* substitute the key for that in the mapping, if any */
                    if ( is_array($fields[$key]) ) {
                        unset($bundle[$key]);
                        /* This is one helluva array "syntax" abuse */
                        $bundle[key($fields[$key])]
                            = $fields[$key][key($fields[$key])][$value];

                    } else {
                        /* no mapping, so continue */
                        unset($bundle[$key]);
                        $bundle[$fields[$key]] = $value;
                        if ( 'datetime' === $fields[$key] ) {
                            $split_datetime
                                = preg_split('/\./', $bundle[$fields[$key]]);
                            $timestamp = $split_datetime[0];
                            $offset = $split_datetime[1];

                            /* format to something human freindly */
                            $bundle[$fields[$key]] = date(
                                "F j, Y, g:i a",
                                $timestamp
                            );
                        } elseif ( 'files' === $fields[$key] ) {
                            /* Do we have multiple files in the field? */
                            if ( preg_match('/\s/', $bundle[$fields[$key]]) ) {
                                /*@TODO This will totally bork file nams with
                                  spaces!! */
                                $bundle[$fields[$key]]
                                    = preg_split('/\s/', $bundle[$fields[$key]]);
                            } else {
                                /* put even a single file into an array */
                                $bundle[$fields[$key]] = array($value);
                            }
                        }
                    }
                }
            } else {
                /* Well, buddy, $fields was empty, so now what? */
                /* It would seem that the raw array (i.e. $bundle) is simply
                 * passed through */
            }

            /* [] is needed to prevent overwriting of output lines */
            $parsed_output[] = $bundle;
        }

        if ( ! empty($this->output_format )) {
            $formatted_output = $formatter->$method($parsed_output);
        } else {
            $formatted_output = $parsed_output;
        }

        return $formatted_output;
    }

    /**
     * Sets the command string
     *
     * @return void
     *
     * @TODO should return something! Now, it merely manipulates command_string
     * @todo use this: $command_string = escapeshellcmd() but it causes
     * problems on windows...I think
     */
    public function setCommandString()
    {
        $this->command_string = '"' .
            $this->hg->executable . '" ' .
            $this->command . ' ' .
            rtrim($this->formatOptions($this->getOptions()));
    }

    /**
     * Returns the command string
     *
     * @return string
     */
    public function getCommandString()
    {
        return $this->command_string;
    }

    /**
     * Specify which revisions to operate upon in the repository
     *
     * Revisions are considered inclusive: r1 to r3 includes data
     * from r1,r2,r3.
     *
     * @param string $first The first revision for a command to act on
     * @param string $last  The last revision for a command to act on
     *
     * @return  VersionControl_Hg_Command
     *
     * @TODO Deprecated: mark for deletion
     *
     * @TODO Handle $last as well!
     * @TODO Consider whether this is a good idea, since between() handles
     * this for some commands...
     */
    public function changeset($first, $last)
    {
        $this->addOption('rev', $first);

        /* let me be chainable! */
        return $this;
    }
}
