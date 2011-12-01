--TEST--
XML Parser: error class (PHP5 behavior)
--SKIPIF--
<?php
if (version_compare(PHP_VERSION, '5.0.0', 'lt')) {
    print 'skip - test only applies to PHP5';
}
if (!extension_loaded('xml')) {
    print 'skip - xml extension not available';
}
?>
--FILE--
<?php
require_once 'XML/Parser.php';
print 'New XML_Parser:  ';
var_dump(strtolower(get_class($p = new XML_Parser())));
$e = $p->parseString("<?xml version='1.0' ?>\n<foo></bar>", true);
if (PEAR::isError($e)) {
    printf("Error message: %s" . PHP_EOL, $e->getMessage());
} else {
    print "No error" . PHP_EOL;
}
?>
--EXPECT--
New XML_Parser:  string(10) "xml_parser"
Error message: XML_Parser: Mismatched tag at XML input line 2:12
