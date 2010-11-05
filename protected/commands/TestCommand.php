<?php

Yii::import('application.vendors.*');
require_once('mimeparser/mime_parser.php');
require_once('mimeparser/body_fetcher.php');
require_once('mimeparser/rfc822_addresses.php');

class TestCommand extends CConsoleCommand {

    public function run($args) {
        $email = '';
        $fd = fopen("php://stdin", "r");
        //$fd = fopen("C:/wamp/www/email.txt", "r");
        while (!feof($fd)) {
            $email .= fread($fd, 1024);
        }
        fclose($fd);

        if ($email !== '') {
            /* Create a new instance of MimeParser - just for the body in plain text */
            $parse = new MimeParser($email);
            /* Create a new instance of Parser */
            $mime = new mime_parser_class;
            $mime->mbox = 0;
            $mime->decode_bodies = 1;
            $mime->ignore_syntax_errors = 1;
            $mime->track_lines = 1;
            $parameters = array('Data' => $email, 'SkipBody' => 0,);

            $mime->Decode($parameters, $decoded);

            $pass_this = array();
            for ($message = 0; $message < count($decoded); $message++) {
                if ($mime->decode_bodies) {
                    if ($mime->Analyze($decoded[$message], $results)) {
                        foreach ($results['From'] as $senders) {
                            $pass_this['from'] = $senders['address'];
                        }
                        $pass_this['subject'] = $results['Subject'];

                        $incoming_message = $parse->message['plain'];

                        $incoming_message = utf8_encode($incoming_message);
                        // Clean out 'quoted-printable' rubbish
                        $quoted = strpos($incoming_message, 'quoted-printable');
                        if ($quoted !== false) {
                            $quoted_parts = explode('quoted-printable', $parse->message['plain']);
                            $incoming_message = $quoted_parts[1];
                        }
                        // Clean out 'Content-Transfer-Encoding: 8bit' rubbish
                        $quoted2 = strpos($incoming_message, 'Content-Transfer-Encoding: 8bit');
                        if ($quoted2 !== false) {
                            $quoted_parts2 = explode('Content-Transfer-Encoding: 8bit', $parse->message['plain']);
                            $incoming_message = $quoted_parts2[1];
                        }
                        // Clean out 'Content-Transfer-Encoding: 7bit' rubbish
                        $quoted3 = strpos($incoming_message, 'Content-Transfer-Encoding: 7bit');
                        if ($quoted3 !== false) {
                            $quoted_parts3 = explode('Content-Transfer-Encoding: 7bit', $parse->message['plain']);
                            $incoming_message = $quoted_parts3[1];
                        }
                        // Remove 'original message'
                        $pos = strpos($incoming_message, '----- Original Message -----');
                        $pos2 = strpos($incoming_message, '---------');
                        $pos3 = strpos($incoming_message, '-------');
                        if ($pos !== false) {
                            $orig_message_parts = explode('----- Original Message -----', $incoming_message);
                            $pass_this['message'] = trim($orig_message_parts[0]);
                        } elseif ($pos2 !== false) {
                            $orig_message_parts = explode('---------', $incoming_message);
                            $pass_this['message'] = trim($orig_message_parts[0]);
                        } elseif ($pos3 !== false) {
                            $orig_message_parts = explode('-------', $incoming_message);
                            $pass_this['message'] = trim($orig_message_parts[0]);
                        } else {
                            $pass_this['message'] = trim($incoming_message);
                        }
                        $exploding = explode('--',$pass_this['message']);
                        $pass_this['message'] = $exploding[0];

                        $the_attachments = array();
                        $count = 0;
//                    foreach($results['Attachments'] as $attachment) {
//                        $file_id = uniqid();
//                        $the_attachments[$count]['FileID'] = $file_id;
//                        $the_attachments[$count]['FileName'] = $attachment['FileName'];
//                        $count++;
//                    }
//                    $pass_this['attachments'] = $the_attachments;
                    }
                }
            }
            $pass_this['project'] = '';
            $pass_this['issue'] = 0;

            $subject_regex = '/^(Re: \[)([^0-9][A-z0-9]+)( - )(Bug|Feature) #?(\d+)(\#ic\d*){0,1}/';
            $num_closes = preg_match($subject_regex, $pass_this['subject'], $matches_closes, PREG_OFFSET_CAPTURE);
            if ($num_closes > 0) {
                $pass_this['project'] = $matches_closes[2][0];
                $pass_this['issue'] = $matches_closes[5][0];
            }
            //$fp = fopen("C:/wamp/www/out.txt", "w+");
            //$fp = fopen("/home/stealth977/files.ogitor.org/email.txt","w+");
            //foreach ($pass_this as $key => $value) {
            //    fwrite($fp, $key . ': ' . $value . "\n");
            //}
            //fclose($fp);

            $user = User::model()->find('email=?', array($pass_this['from']));
            if(null == $user)
                return;
            $issue = Issue::model()->findByPk((int) $pass_this['issue']);
            if(null == $issue)
                return;
            $new_comment = new Comment;
            $new_comment->content = $pass_this['message'];
            $new_comment->create_user_id = $user->id;
            $new_comment->update_user_id = $user->id;
            $new_comment->modified = new CDbExpression('NOW()');
            $new_comment->created = new CDbExpression('NOW()');
            $new_comment->issue_id = $issue->id;
            $new_comment->save(false);
        }
        //mail("jacmoe@mail.dk", "Script was run", "The script was run succesfully", "admin@ogitor.org");

    }

}