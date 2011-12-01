--TEST--
XML Parser: mixing character encodings
--SKIPIF--
<?php
if (true) {
    print 'skip - incomplete test!';
}
if (!extension_loaded('xml')) {
    print 'skip - xml extension not available';
}
if (!extension_loaded('mbstring')) {
    print 'skip - mbstring extension not available';
}
?>
--FILE--
<?php

/* Test for: XML/Parser.php
 * Parts tested: - mixing character encodings
 *
 * This is what we test:
 * 1 UTF-8      -> ISO-8859-1
 * 2 UTF-8      -> US-ASCII
 * 3 ISO-8859-1 -> UTF-8
 * 4 ISO-8859-1 -> US-ASCII
 * 5 US-ASCII   -> UTF-8
 * 6 US-ASCII   -> ISO-8859-1
 */

require_once 'XML/Parser.php';

class TestEncodings1 extends XML_Parser {
    var $output = '';

    function TestEncodings1($to, $mode, $from) {
        $this->XML_Parser($from, $mode, $to);
    }
    function startHandler($xp, $elem, $attribs) {
        $this->output .= "<$elem>";
    }
    function endHandler($xp, $elem) {
        $this->output .= "</$elem>";
    }
    function cdataHandler($xp, $data) {
        $this->output .= $data;
    }
    function test($data) {
        $result = $this->parseString($data, true);
        if (PEAR::isError($result)) {
            return $result;
        }
    }
}

$xml = "<?xml version='1.0' ?>";
$input = array(
    "UTF-8"      => "<a>abcæøå</a>",

    /* are these special chars allowed in ISO-8859-1 context??? */
    "ISO-8859-1" => "<a>abc���</a>", //    "ISO-8859-1" => "<a>abc�<a>",

    "US-ASCII"   => "<a>abcaoa</a>"
);

$encodings = array_keys($input);
foreach ($input as $srcenc => $string) {
    foreach ($encodings as $tgtenc) {
        if ($srcenc == $tgtenc) {
            continue;
        }
        print "Testing $srcenc -> $tgtenc: ";
        $p =& new TestEncodings1($tgtenc, 'event', $srcenc);
        $e = $p->test($input[$srcenc]);
        if (PEAR::isError($e)) {
            printf("OOPS: %s\n", $e->getMessage());
        } else {
            var_dump($p->output);
        }
    }
}

?>
--EXPECT--
Testing UTF-8 -> ISO-8859-1: string(13) "<A>abc���</A>"
Testing UTF-8 -> US-ASCII: string(13) "<A>abc???</A>"
Testing ISO-8859-1 -> UTF-8: string(16) "<A>abcæøå</A>"
Testing ISO-8859-1 -> US-ASCII: string(13) "<A>abc???</A>"
Testing US-ASCII -> UTF-8: string(13) "<A>abcaoa</A>"
Testing US-ASCII -> ISO-8859-1: string(13) "<A>abcaoa</A>"
