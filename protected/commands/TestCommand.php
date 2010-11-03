<?php
class TestCommand extends CConsoleCommand
{
    public function run($args)
    {
        echo 'testing console script';
        echo "\n";
        echo $args[0];
        echo "\n";
        // send email to $receiver
    }
}