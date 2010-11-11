<?php
class Bugitor {
    public static function link_to_user($name, $id) {
        return CHtml::link(ucfirst($name),array('/user/user/view', "id" => $id));
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
