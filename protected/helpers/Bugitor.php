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
        if(false) {
            return CHtml::link(ucfirst($user->username),array('/user/user/view', "id" => $user->id));
        } else {
            return CHtml::link(ucwords($user->profile->getAttribute('firstname') . ' ' . $user->profile->getAttribute('lastname')),array('/user/user/view', "id" => $user->id));
        }
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
