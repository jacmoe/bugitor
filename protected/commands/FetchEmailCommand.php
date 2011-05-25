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
 * Copyright (C) 2009 - 2011 Bugitor Team
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
require_once('mimeparser/rfc822_addresses.php');

class FetchEmailCommand extends CConsoleCommand {

    public function run($args) {

        $message_data = '';
        $fd = fopen("php://stdin", "r");
        if ($fd) {
            while (!feof($fd)) {
                $message_data .= fread($fd, 1024);
            }
            fclose($fd);
        }


        $pass_this = array();

        if ($message_data) {
            $mime = new mime_parser_class;
            $mime->mbox = 0;
            $mime->decode_bodies = 1;
            $mime->ignore_syntax_errors = 1;
            $mime->track_lines = 1;
            $mime->use_part_file_names = 0;
            $parameters = array(
//                'File' => $message_file,
                'Data' => $message_data,
                'SkipBody' => 0,
            );
            if (!$mime->Decode($parameters, $decoded)) {
                //echo 'MIME message decoding error: ' . $mime->error . ' at position ' . $mime->error_position;
            } else {
                for ($message = 0; $message < count($decoded); $message++) {
                    //var_dump($decoded[$message]);
                    //echo $decoded[$message]['Parts'][0]['Body'];
                    if ($mime->decode_bodies) {
                        if ($mime->Analyze($decoded[$message], $results)) {
                            foreach ($results['From'] as $senders) {
                                $pass_this['from'] = $senders['address'];
                            }
                            $pass_this['subject'] = $results['Subject'];

                            //var_dump($results); die();

                            if ($results['Type'] == 'html') {
                                if (isset($results['Alternative'][0]['Data'])) {
                                    $incoming_message = $results['Alternative'][0]['Data'];
                                } else {
                                    //TODO: fail!?
                                    // Find a html stripping tool..
                                    $incoming_message = "";
                                    continue;
                                }
                            } else {
                                $incoming_message = $results['Data'];
                            }

                            // Convert message to utf-8 if not
                            if ($results['Encoding'] != 'utf-8') {
                                $incoming_message = iconv($results['Encoding'], "UTF-8", $incoming_message);
                            }

                            // Clean out 'quoted-printable' rubbish
                            $quoted = strpos($incoming_message, 'quoted-printable');
                            if ($quoted !== false) {
                                $quoted_parts = explode('quoted-printable', $incoming_message);
                                $incoming_message = $quoted_parts[1];
                            }
                            // Clean out 'Content-Transfer-Encoding: 8bit' rubbish
                            $quoted2 = strpos($incoming_message, 'Content-Transfer-Encoding: 8bit');
                            if ($quoted2 !== false) {
                                $quoted_parts2 = explode('Content-Transfer-Encoding: 8bit', $incoming_message);
                                $incoming_message = $quoted_parts2[1];
                            }
                            // Clean out 'Content-Transfer-Encoding: 7bit' rubbish
                            $quoted3 = strpos($incoming_message, 'Content-Transfer-Encoding: 7bit');
                            if ($quoted3 !== false) {
                                $quoted_parts3 = explode('Content-Transfer-Encoding: 7bit', $incoming_message);
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
                            $exploding = explode('--', $pass_this['message']);
                            $pass_this['message'] = $exploding[0];
                            // /On(.*)wrote\:/iU
                            $cleaned_mess = preg_match('/On(.*)wrote\:/iU', $pass_this['message'], $patterns);
                            if ($cleaned_mess > 0) {
                                if (strlen($patterns[0])) {
                                    $exploded_mess = explode($patterns[0], $pass_this['message']);
                                    $pass_this['message'] = $exploded_mess[0];
                                }
                            }
                            // /Am(.*)schrieb\:/iU
                            $cleaned_mess1 = preg_match('/Am(.*)schrieb(.*)\:/iU', $pass_this['message'], $patterns);
                            if ($cleaned_mess1 > 0) {
                                if (strlen($patterns[0])) {
                                    $exploded_mess1 = explode($patterns[0], $pass_this['message']);
                                    $pass_this['message'] = $exploded_mess1[0];
                                }
                            }
                            $cleaned_mess2 = preg_match('/Von(.*)\:/iU', $pass_this['message'], $patterns2);
                            if ($cleaned_mess2 > 0) {
                                if (strlen($patterns2[0])) {
                                    $exploded_mess2 = explode($patterns2[0], $pass_this['message']);
                                    $pass_this['message'] = $exploded_mess2[0];
                                }
                            }
                            $pass_this['message'] = preg_replace('/\=20/', "\r\n", $pass_this['message']);

                            $pass_this['project'] = '';
                            $pass_this['issue'] = 0;

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
                            // Email parsed

                            echo "From: " . $pass_this['from'] . "\n";
                            echo "Subject: " . $pass_this['subject'] . "\n";
                            echo "Message: " . $pass_this['message'];

                            // Now, get the email into Bugitor..
                            $criteria = new CDbCriteria();
                            $criteria->compare('email', $pass_this['from'], true);
                            $user = User::model()->find($criteria);
                            if (null === $user) {
                                continue;
                            }

                            $issue = Issue::model()->findByPk($pass_this['issue']);
                            if (null === $issue) {
                                continue;
                            }

                            $new_comment = new Comment;
                            $new_comment->content = $pass_this['message'];
                            $new_comment->create_user_id = $user->id;
                            $new_comment->update_user_id = $user->id;
                            $new_comment->issue_id = (int) $pass_this['issue'];
                            $new_comment->created = $new_comment->modified = date("Y-m-d\TH:i:s\Z", time());
                            if ($new_comment->validate()) {
                                $new_comment->save(false);
                            }

                            $issue->updated_by = $user->id;
                            if ($issue->validate()) {
                                $issue->save(false);
                                $issue->addToActionLog($issue->id, $user->id, 'note', '/projects/' . $issue->project->identifier . '/issue/view/' . $issue->id . '#note-' . $issue->commentCount, $new_comment->id);
                                $issue->sendNotification($issue->id, $new_comment->id, $issue->updated_by);
                            }
                            // Done :)
                        } else {
                            echo 'MIME message analyse error: ' . $mime->error . "\n";
                        }
                    } // if decode bodies
                } // for each message

                for ($warning = 0, Reset($mime->warnings); $warning < count($mime->warnings); Next($mime->warnings), $warning++) {
                    $w = Key($mime->warnings);
                    echo 'Warning: ', $mime->warnings[$w], ' at position ', $w;
                    if ($mime->track_lines
                            && $mime->GetPositionLine($w, $line, $column))
                        echo ' line ' . $line . ' column ' . $column;
                    echo "\n";
                }
            } // if email decoding was successful
        } // if there is a message
    }

}
