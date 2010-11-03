<?php
Yii::import('application.vendors.*');
require_once('mimeparser/mime_parser.php');
require_once('mimeparser/body_fetcher.php');

class TestCommand extends CConsoleCommand
{
    public function run($args)
    {
        $email = '';
        $fd = fopen("php://stdin", "r");
        while (!feof($fd)) {
            $email .= fread($fd, 1024);
        }
        fclose($fd);
        
        if($email !== '') {
            $fp=fopen("/home/stealth977/files.ogitor.org/email.txt","w+");
            fwrite($fp, $email);
            fclose($fp);
            }
//        foreach($pass_this as $key => $value){
//            fwrite($fp,$value."\t");
//        }
        mail("jacmoe@mail.dk", "Script was run", "The script was run succesfully", "admin@ogitor.org");
    }
}
