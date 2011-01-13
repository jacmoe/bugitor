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
class Bugitor {
    public static function link_to_user($user) {
        if(true) {
            return CHtml::link(ucfirst($user->username),array('/user/user/view', "id" => $user->id));
        } else {
            return CHtml::link(ucwords($user->profile->getAttribute('firstname') . ' ' . $user->profile->getAttribute('lastname')),array('/user/user/view', "id" => $user->id));
        }
    }

    public static function format_username($user) {
        if(true) {
            return ucfirst($user->username);
        } else {
            return ucwords($user->profile->getAttribute('firstname') . ' ' . $user->profile->getAttribute('lastname'));
        }
    }

    public static function truncate($text, $length, $ending = '...', $exact = true) {
        if (strlen($text) <= $length) {
            return $text;
        } else {
            mb_internal_encoding("UTF-8");
            if (mb_strlen($text) > $length) {
                $length -= mb_strlen($ending);
                if (!$exact) {
                    $text = preg_replace('/\s+?(\S+)?$/', '', mb_substr($text, 0, $length+1));
                }
                return mb_substr($text, 0, $length).$ending;
            } else {
                return $text;
            }
        }
    }

    public static function truncate_single_line($string, $length, $ending = '...', $exact = true) {
        $string = Bugitor::truncate($string, $length, $ending, $exact);
        return preg_replace('/[\r\n\_]+/', ' ', $string);
    }

    public static function p2nl ($str) {
        return preg_replace(array("/<p[^>]*>/iU","/<\/p[^>]*>/iU"),
                            array("","\n"),
                            $str);
    }

    public static function format_activity_description($text, $length = 120) {
        $out = Bugitor::truncate($text, $length);
        return preg_replace('/<(pre|code)>.*$/', '...', $out);
    }

    public static function link_to_issue($issue, $absolute = false) {
        if($absolute) {
            return CHtml::link($issue->tracker->name . ' #' . $issue->id . ': ' . $issue->subject,Yii::app()->request->hostInfo.'/projects/'.$issue->project->identifier.'/issue/view/'.$issue->id, array('class' => ($issue->closed == 1) ? 'issue closed' : 'issue'));
        } else {
            return CHtml::link($issue->tracker->name . ' #' . $issue->id . ': ' . $issue->subject,array('/issue/view', "id" => $issue->id, 'identifier' => $issue->project->identifier), array('class' => ($issue->closed == 1) ? 'issue closed' : 'issue'));
        }
    }

    public static function short_link_to_issue($issue, $absolute = false) {
        if($absolute) {
            return CHtml::link($issue->tracker->name . ' #' . $issue->id,Yii::app()->request->hostInfo.'/projects/'.$issue->project->identifier.'/issue/view/'.$issue->id, array('class' => ($issue->closed == 1) ? 'issue closed' : 'issue')) . ': ' . $issue->subject;
        } else {
            return CHtml::link($issue->tracker->name . ' #' . $issue->id,array('/issue/view', "id" => $issue->id, 'identifier' => $issue->project->identifier), array('class' => ($issue->closed == 1) ? 'issue closed' : 'issue')) . ': ' . $issue->subject;
        }
    }

    public static function issue_subject($issue) {
        return '['.$issue->project->name.' - '.$issue->tracker->name.' #'.$issue->id.'] '.$issue->subject;
    }

    public static function progress_bar_auto($pcts, $options=array()) {
        $total = $pcts[0] + $pcts[1];
        $pcts[0] = $pcts[0] * 100 / $total;
        $pcts[1] = $pcts[1] * 100 / $total;
        return $this->progress_bar($pcts, $options);
    }

    public static function progress_bar($pcts, $options=array()) {
        $value = 0;
        if(!is_array($pcts)) {
            $value = $pcts;
            $pcts = array($pcts, $pcts);
        }
        $pcts[1] = $pcts[1] - $pcts[0];
        $pcts[] = (100 - $pcts[1] - $pcts[0]);
        $width = empty($options['width']) ? '100px;' : $options['width'];
        $legend = empty($options['legend']) ? '' : $options['legend'];
        if($value) {
            $out = '<table title="'.$value.' %" class="progress" style="width: '.$width.';"><tbody>';
        } else {
            $out = '<table class="progress" style="width: '.$width.';"><tbody>';
        }
        $out .= '<tr>';
        $out .= ($pcts[0] > 0) ? '<td style="width: '.$pcts[0].'%;" class="closed" />' : '';
        $out .= ($pcts[1] > 0) ? '<td style="width: '.$pcts[1].'%;" class="done" />' : '';
        $out .= ($pcts[2] > 0) ? '<td style="width: '.$pcts[2].'%;" class="todo" />' : '';
        $out .= '</tr></tbody></table>';
        $out .= '<p class="pourcent">'.$legend.'</p>';
        return $out;
    }

    public static function big_progress_bar($pcts, $options=array()) {
        if(!is_array($pcts)) $pcts = array($pcts, $pcts);
        $width = empty($options['width']) ? '100px;' : $options['width'];
        $legend = empty($options['legend']) ? '' : $options['legend'];
        $out = '<table class="big_progress" style="width: '.$width.';"><tbody>';
        $out .= '<tr>';
        $out .= ($pcts[0] > 0) ? '<td style="width: '.$pcts[0].'%;" class="closed" />' : '';
        $out .= ($pcts[1] > 0) ? '<td style="width: '.$pcts[1].'%;" class="done" />' : '';
        $out .= ($pcts[2] > 0) ? '<td style="width: '.$pcts[2].'%;" class="todo" />' : '';
        $out .= '</tr></tbody></table>';
        $out .= '<p class="big_pourcent">'.$legend.'</p>';
        return $out;
    }

    public static function gravatar($email, $size = 48, $name = '') {
        $grav_url = "http://www.gravatar.com/avatar.php?gravatar_id=" .
            md5($email) . "&size=" . $size;
        if($name !== '')
            return '<img title="'.ucfirst($name).'" class="gravatar" src="'.$grav_url.'"/>';
        return '<img class="gravatar" src="'.$grav_url.'"/>';
    }

    public static function namedImage($name) {
        return '<img title="'.$name.'" src="'.Yii::app()->theme->baseUrl.'/images/'.$name.'.png"/>';
    }
    
};
