<?php

class ESCM extends CApplicationComponent {

    private $_hg_executable = null;
    private $_python_path = null;

    public function init() {
        $this->_hg_executable = Yii::app()->config->get('hg_executable');
        $this->_python_path = Yii::app()->config->get('python_path');
        parent::init();
    }

    function mtrack_run_tool($toolname, $mode, $args = null) {
        global $FORKS;

        //$tool = "/home/stealth977/bin/hg";//MTrackConfig::get('tools', $toolname);
        $tool = $toolname;//Yii::app()->config->get('hg_executable', $toolname);
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
                error_log($cmd);
                echo htmlentities($cmd) . "<br>\n";
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

    function hg() {
        if(Yii::app()->config->get('python_path'))
            putenv('PYTHONPATH=/home/stealth977/.packages/lib/python');
        $args = func_get_args();
        //$a = array("-y", "-R", $this->repopath, "--cwd", $this->repopath);
        //$a = array("-R", '/home/stealth977/files.ogitor.org/', "--cwd", '/home/stealth977/files.ogitor.org/');
        $a = array("-R", 'C:/wamp/www/', "--cwd", 'C:/wamp/www/');
        foreach ($args as $arg) {
            $a[] = $arg;
        }

        return mtrack_run_tool('hg', 'read', $a);
    }
}

?>