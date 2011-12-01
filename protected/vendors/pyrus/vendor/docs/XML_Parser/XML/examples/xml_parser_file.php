<?PHP
/**
 * example for XML_Parser_Simple
 *
 * @author      Stephan Schmidt <schst@php-tools.net>
 * @package     XML_Parser
 * @subpackage  Examples
 */

/**
 * require the parser
 */
require_once 'XML/Parser.php';

class myParser extends XML_Parser
{

    function myParser()
    {
        parent::XML_Parser();
    }

   /**
    * handle start element
    *
    * @access   private
    * @param    resource    xml parser resource
    * @param    string      name of the element
    * @param    array       attributes
    */
    function startHandler($xp, $name, $attribs)
    {
        printf('handle start tag: %s<br />', $name);
    }

   /**
    * handle start element
    *
    * @access   private
    * @param    resource    xml parser resource
    * @param    string      name of the element
    * @param    array       attributes
    */
    function endHandler($xp, $name)
    {
        printf('handle end tag: %s<br />', $name);
    }
}

$p = &new myParser();

$result = $p->setInputFile('xml_parser_file.xml');
$result = $p->parse();
?>