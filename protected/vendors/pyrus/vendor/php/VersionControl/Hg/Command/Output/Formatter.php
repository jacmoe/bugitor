<?php
/**
 * Contains the definition of the Output Formatter class
 *
 * PHP version 5
 *
 * @category   VersionControl
 * @package    Hg
 * @subpackage Output
 * @author     Michael Gatto <mgatto@lisantra.com>
 * @copyright  2011 Lisantra Technologies, LLC
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       http://pear.php.net/package/VersionControl_Hg
 */

/**
 * Format output
 *
 * PHP version 5
 *
 * @category   VersionControl
 * @package    Hg
 * @subpackage Output
 * @author     Michael Gatto <mgatto@lisantra.com>
 * @copyright  2011 Lisantra Technologies, LLC
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       http://pear.php.net/package/VersionControl_Hg
 * @filesource
 */
class VersionControl_Hg_Command_Output_Formatter
{
    /**
     * Implemented output types.
     *
     * Array is the default, by nature of the code.
     *
     * @var mixed
     */
    public static $formats = array(
        'json', 'serialize', 'raw', 'yaml',
    );

    //Having run() return an VersionControl_Hg_Command_Output object
    //might just be useful, sort of how PDO / Doctrine can...
    //though we might be overengineering, just a wee tad bit.

    /**
     * Class constructor.
     */
    public function __construct()
    {

    }

    /**
     * Convert array to PHP JSON text format
     *
     * @param mixed $output The passed-in, parsed output from the cli
     *
     * @return string
     */
    public static function toJson(array $output)
    {
        /* output must be in UTF-8 */
        return json_encode($output);
    }

    /**
     * Convert array to YAML text format
     *
     * @param array $output The passed-in, parsed output from the cli
     *
     * @return string
     */
    public static function toYaml(array $output)
    {
        if ( ! extension_loaded('yaml') ) {
            throw new VersionControl_Hg_Command_Exception(
                VersionControl_Hg_Command_Exception::BAD_ARGUMENT,
                "The required PECL Yaml extension is not installed. "
            );
        }

        return yaml_emit($output);
    }

    /**
     * Convert array to raw text format
     *
     * @param array $output is the passed-in, parsed output from the cli
     *
     * @return string
     */
    public static function toRaw(array $output)
    {
        $raw = "";

        foreach ( $output as $line ) {
            $raw .= $line . PHP_EOL;
        }

        return $raw;
    }

    /**
     * Convert array to PHP serialized text format
     *
     * @param array $output is the passed-in, parsed output from the cli
     *
     * @return string
     */
    public static function toSerialize(array $output)
    {
        return serialize($output);
    }

}
