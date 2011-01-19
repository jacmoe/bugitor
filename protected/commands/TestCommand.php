<?php
/*
 * This file is part of
 *     ____              _ __
 *    / __ )__  ______ _(_) /_____  _____
 *   / __  / / / / __ `/ / __/ __ \/ ___/
 *  / /_/ / /_/ / /_/ / / /_/ /_/ / /
 * /_____/\__,_/\__, /_/\__/\____/_/
 *             /____/
 * A Yii powered issue tracker
 * http://bitbucket.org/jacmoe/bugitor/
 *
 * Copyright (C) 2009 - 2010 Bugitor Team
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation files
 * (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
?>
<?php

Yii::import('application.vendors.*');
require_once('mimeparser/mime_parser.php');
require_once('mimeparser/body_fetcher.php');
require_once('mimeparser/rfc822_addresses.php');

class TestCommand extends CConsoleCommand {

public function parse_email ($email) {
// Split header and message
$header = array();
$message = array();

$is_header = true;
foreach ($email as $line) {
if ($line == '<HEADER> ' . "\r\n") continue;
if ($line == '<MESSAGE> ' . "\r\n") continue;
if ($line == '</MESSAGE> ' . "\r\n") continue;
if ($line == '</HEADER> ' . "\r\n") { $is_header = false; continue; }

if ($is_header == true) {
$header[] = $line;
} else {
$message[] = $line;
}
}

// Parse headers
$headers = array();

foreach ($header as $line) {
$colon_pos = strpos($line, ':');
$space_pos = strpos($line, ' ');

if ($colon_pos === false OR $space_pos < $colon_pos) {
// attach to previous
$previous .= "\r\n" . $line;
continue;
}

// Get key
$key = substr($line, 0, $colon_pos);

// Get value
$value = substr($line, $colon_pos+2);
$headers[$key] = $value;

$previous =& $headers[$key];
}
// Parse message
$message = implode('', $message);

// Return array
$email = array();
$email['message'] = $message;
$email['headers'] = $headers;
/* echo "<pre>";
echo print_r($message);;
echo "<pre> message$id";
*/ return $email;
}

    public function run($args) {
		// debug file
		//$debugFile = "/home/stealth977/debug_dump.txt";
		//$debugfh = fopen($debugFile, 'w');
		//fwrite($debugfh,"alright\r\n");
        
		$email = '';
        $fd = fopen("php://stdin", "r");
        //$fd = fopen("/home/stealth977/email.txt", "r");
        if($fd) {
            while (!feof($fd)) {
                $email .= fread($fd, 1024);
            }
            fclose($fd);
        }
        
        // handle email
        $lines = explode("\n", $email);

        // empty vars
        $from = "";
        $subject = "";
        $headers = "";
        $message_plain = "";
        $splittingheaders = true;

        for ($i=0; $i < count($lines); $i++) {
            if ($splittingheaders) {
                // this is a header
                $headers .= $lines[$i]."\n";

                // look out for special headers
                if (preg_match("/^Subject: (.*)/", $lines[$i], $matches)) {
                    $subject = $matches[1];
                }
                if (preg_match("/^From: (.*)/", $lines[$i], $matches)) {
                    $from = $matches[1];
                }
            } elseif ($splittingmessage) {

            } else {
                //If message has some HTML headers.
                if (strstr($lines[$i + 1],"Content-Type: text/html;")) {
                    break;
                } else {
                    // not a header, but message
                    $message_plain .= $lines[$i]."\n";
                }
                //If message has some HTML headers.
                if (strstr($lines[$i],"Content-Type: text/plain;")) {
                    $message_plain = "";
                    $splittingmessage = true;
                }

            }

            if (trim($lines[$i])=="") {
                // empty line, header section has ended
                $splittingheaders = false;
                $splittingmessage = false;
            }
        }

        if ($email !== '') {
			//fwrite($debugfh,"email exists\r\n");
			
			/* Create a new instance of MimeParser - just for the body in plain text */
            //$parse = new MimeParser($email);

            /* Create a new instance of Parser */
            $mime = new mime_parser_class;
            $mime->mbox = 0;
            $mime->decode_bodies = 1;
            $mime->ignore_syntax_errors = 1;
            $mime->track_lines = 1;
            $parameters = array('Data' => $email, 'SkipBody' => 0,);

            $mime->Decode($parameters, $decoded);
			//fwrite($debugfh,"email was decoded\r\n");

            $pass_this = array();
            for ($message = 0; $message < count($decoded); $message++) {
                if ($mime->decode_bodies) {
					//fwrite($debugfh,"mime decode bodies\r\n");
                    
					if ($mime->Analyze($decoded[$message], $results)) {
						//fwrite($debugfh,"mime analyze\r\n");
                        foreach ($results['From'] as $senders) {
                            $pass_this['from'] = $senders['address'];
                        }
						//fwrite($debugfh,"Got a sender\r\n");
						//fwrite($debugfh,$pass_this['from'] . "\r\n");

                        $pass_this['subject'] = $results['Subject'];
						//fwrite($debugfh,"Got a subject\r\n");
						//fwrite($debugfh,$pass_this['subject'] . "\r\n");
                        
						$incoming_message = $message_plain;//$parse->message['plain'];
                        if(strlen($incoming_message)) {
							//fwrite($debugfh,"incoming message is not empty\r\n");
							//fwrite($debugfh, $incoming_message . "\r\n");
						}
						
						$incoming_message = iconv("windows-1256", "UTF-8", $incoming_message);
						//fwrite($debugfh,"utf8 encoded message\r\n");
                        // Clean out 'quoted-printable' rubbish
                        $quoted = strpos($incoming_message, 'quoted-printable');
                        if ($quoted !== false) {
                            $quoted_parts = explode('quoted-printable', $parse->message['plain']);
                            $incoming_message = $quoted_parts[1];
                        }
						//fwrite($debugfh,"first clean\r\n");
                        // Clean out 'Content-Transfer-Encoding: 8bit' rubbish
                        $quoted2 = strpos($incoming_message, 'Content-Transfer-Encoding: 8bit');
                        if ($quoted2 !== false) {
                            $quoted_parts2 = explode('Content-Transfer-Encoding: 8bit', $parse->message['plain']);
                            $incoming_message = $quoted_parts2[1];
                        }
						//fwrite($debugfh,"second clean\r\n");
                        // Clean out 'Content-Transfer-Encoding: 7bit' rubbish
                        $quoted3 = strpos($incoming_message, 'Content-Transfer-Encoding: 7bit');
                        if ($quoted3 !== false) {
                            $quoted_parts3 = explode('Content-Transfer-Encoding: 7bit', $parse->message['plain']);
                            $incoming_message = $quoted_parts3[1];
                        }
						//fwrite($debugfh,"third clean\r\n");
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

						//fwrite($debugfh,"just before On wrote clean\r\n");
                        // /On(.*)wrote\:/iU

                        $cleaned_mess = preg_match('/On(.*)wrote\:/iU',$pass_this['message'],$patterns);
						//fwrite($debugfh,"got cleaned mess\r\n");
                        if(strlen($patterns[0])) {
							$exploded_mess = explode($patterns[0],$pass_this['message']);
							//fwrite($debugfh,"got exploded mess\r\n");
							$pass_this['message'] = $exploded_mess[0];
						}

                        $cleaned_mess2 = preg_match('/Von(.*)\:/iU',$pass_this['message'],$patterns2);
						//fwrite($debugfh,"got cleaned2 mess\r\n");
                        if(strlen($patterns2[0])) {
							$exploded_mess2 = explode($patterns2[0],$pass_this['message']);
							//fwrite($debugfh,"got exploded2 mess\r\n");
							$pass_this['message'] = $exploded_mess2[0];
						}
						
                        $pass_this['message'] = preg_replace('/\=20/',"\r\n", $pass_this['message']);
						
						//fwrite($debugfh,"Message:\r\n");
						//fwrite($debugfh, $pass_this['message'] . "\r\n");
						
						$the_attachments = array();
                        $count = 0;
						//fwrite($debugfh,"Done here\r\n");
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

			//fwrite($debugfh,"Starting to pass on parsed\r\n");
            $subject_regex = '/^(Re: \[)([^0-9][A-z0-9]+)( - )(Bug|Feature) #?(\d+)(\#ic\d*){0,1}/';
            $num_closes = preg_match($subject_regex, $pass_this['subject'], $matches_closes, PREG_OFFSET_CAPTURE);
            if ($num_closes > 0) {
                $pass_this['project'] = $matches_closes[2][0];
                $pass_this['issue'] = $matches_closes[5][0];
            } else {
				// German .. tsk..
				$subject_regex = '/^(AW: \[)([^0-9][A-z0-9]+)( - )(Bug|Feature) #?(\d+)(\#ic\d*){0,1}/';
				$num_closes = preg_match($subject_regex, $pass_this['subject'], $matches_closes, PREG_OFFSET_CAPTURE);
				if ($num_closes > 0) {
					$pass_this['project'] = $matches_closes[2][0];
					$pass_this['issue'] = $matches_closes[5][0];
				}
			}

            $criteria = new CDbCriteria();
            $criteria->compare('email', $pass_this['from'], true);
            $user = User::model()->find($criteria);
            if(null === $user) {
				//fwrite($debugfh,"user is null\r\n");
				//fclose($debugfh);
                return;
            }
			//fwrite($debugfh,"user found\r\n");

            $issue = Issue::model()->findByPk($pass_this['issue']);
            if(null === $issue){
				//fwrite($debugfh,"issue is null\r\n");
				//fclose($debugfh);
                return;
            }
			//fwrite($debugfh,"issue found\r\n");

            $new_comment = new Comment;
            $new_comment->content = $pass_this['message'];
            $new_comment->create_user_id = $user->id;
            $new_comment->update_user_id = $user->id;
            $new_comment->issue_id = (int)$pass_this['issue'];
            $new_comment->created = $new_comment->modified = date("Y-m-d\TH:i:s\Z", time());
            if($new_comment->validate()){
                $new_comment->save(false);
            }

            $issue->updated_by = $user->id;
            if($issue->validate()){
                $issue->save(false);
                $issue->addToActionLog($issue->id, $user->id, 'note', '/projects/'.$issue->project->identifier.'/issue/view/'.$issue->id.'#note-'.$issue->commentCount, $new_comment);
                $issue->sendNotifications($issue->id, $new_comment, $issue->updated_by);
            }
        }
		//fclose($debugfh);
    }

}
