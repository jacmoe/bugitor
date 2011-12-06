<?php
abstract class SCMLocalBackend extends SCMBackend
{
    public $executable = '';
    
    // function courtesy of the mtrack project
    protected function run_tool($toolname, $mode, $args = null) {
        global $FORKS;

        $tool = "\"" . $this->executable . "\"";
        if (!strlen($tool)) {
            $tool = $toolname;
        }
        if (PHP_OS == 'Windows' && strpos($tool, ' ') !== false) {
            $tool = '"' . $tool . '"';
        }
        $cmd = $tool;
        if (is_array($args)) {
            foreach ($args as $arg) {
                if (is_array($arg)) {
                    foreach ($arg as $a) {
                        $cmd .= ' ' . escapeshellarg($a);
                    }
                } else {
                    $cmd .= ' ' . escapeshellarg($arg);
                }
            }
        }
        if (!isset($FORKS[$cmd])) {
            $FORKS[$cmd] = 0;
        }
        $FORKS[$cmd]++;
        if (false) {
            if (php_sapi_name() == 'cli') {
                echo $cmd, "\n";
            } else {
                //error_log($cmd);
                //echo htmlentities($cmd) . "<br>\n";
            }
        }

        switch ($mode) {
            case 'read': return popen($cmd, 'r');
            case 'write': return popen($cmd, 'w');
            case 'string': return stream_get_contents(popen($cmd, 'r'));
            case 'proc':
                $pipedef = array(
                    0 => array('pipe', 'r'),
                    1 => array('pipe', 'w'),
                    2 => array('pipe', 'w'),
                );
                $proc = proc_open($cmd, $pipedef, $pipes);
                return array($proc, $pipes);
        }
    }
    
    public abstract function cloneRepository();
    
    public abstract function pullRepository();
   
    
    public function setExecutable($executable)
    {
        $this->executable = $executable;
    }

}