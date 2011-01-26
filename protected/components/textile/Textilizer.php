<?php

require_once(dirname(__FILE__).'/vendors/classTextile.php');

class Textilizer extends CApplicationComponent
{
    protected $_textile;

    public function getTextile() {
        if ($this->_textile===null)
                $this->_textile = new Textile;
        return $this->_textile;
    }


    protected function smiley($msg) {
        $msg = preg_replace("/:\)/",":smile:", $msg);
        $msg = preg_replace("/:D/",":big_grin:", $msg);
        $msg = preg_replace("/;\)/",":wink:", $msg);
        $msg = preg_replace("/:P/",":razz:", $msg);
        $msg = preg_replace("/:p/",":razz:", $msg);
        $msg = preg_replace("/:\(/",":sad:", $msg);
        $msg = preg_replace("/:smile:/","<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_smile.gif' title='Smile' alt='Smile'>", $msg);
        $msg = preg_replace("/:wink:/","<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_wink.gif' title='Wink' alt='Wink'>", $msg);
        $msg = preg_replace("/\=P/","<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_razz.gif' title='p' alt='p'>", $msg);
        $msg = preg_replace("/\:P/","<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_razz.gif' title='p' alt='p'>", $msg);
        $msg = preg_replace("/:razz:/","<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_razz.gif' title='p' alt='p'>", $msg);
        $msg = preg_replace("/:big_grin:/", "<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_biggrin.gif' title='Big Grin' alt='Big Grin'>", $msg);
        $msg = preg_replace("/:cheesy_grin:/", "<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_cheesygrin.gif' title='Cheesy Grin' alt='Cheesy Grin'>", $msg);
        $msg = preg_replace("/:confused:/", "<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_confused.gif' title='Confused' alt='Confused'>", $msg);
        $msg = preg_replace("/:cool:/", "<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_cool.gif' title='Cool' alt='Cool'>", $msg);
        $msg = preg_replace("/:cry:/", "<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_cry.gif' title='Cry' alt='Cry'>", $msg);
        $msg = preg_replace("/:crying:/", "<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_cry.gif' title='Crying' alt='Crying'>", $msg);
        $msg = preg_replace("/:sad:/", "<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_sad.gif' title='Sad' alt='Sad' >", $msg);
        $msg = preg_replace("/:surprised:/", "<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_surprised.gif' title='Surprised' alt='Surprised' >", $msg);
        $msg = preg_replace("/:evil:/", "<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_evil.gif' title='Evil' alt='Evil' >", $msg);
        $msg = preg_replace("/:twisted:/", "<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_twisted.gif' title='Twisted' alt='Twisted' >", $msg);
        $msg = preg_replace("/:blush:/", "<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_redface.gif' title='Blush' alt='Blush' >", $msg);
        $msg = preg_replace("/:neutral:/", "<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_neutral.gif' title='Neutral' alt='Neutral' >", $msg);
        $msg = preg_replace("/:mad:/", "<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_mad.gif' title='Mad' alt='Mad' >", $msg);
        $msg = preg_replace("/:eek:/", "<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_eek.gif' title='Eek' alt='Eek' >", $msg);
        $msg = preg_replace("/:roll_eyes:/", "<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_rolleyes.gif' title='Roll Eyes' alt='Roll Eyes' >", $msg);
        $msg = preg_replace("/:frown:/", "<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_frown.gif' title='Frown' alt='Frown' >", $msg);
        return $msg;
        //return 'Hello <img src="'.Yii::app()->theme->baseUrl.'/images/smilies/icon_razz.gif">';
        //return Yii::app()->theme->baseUrl;
    }

    protected function _fixupLinks($already_matched) {
        //return '"' . $already_matched[0] . '":' . $already_matched[0] . ' ';
        //return $already_matched[0];
        //return "\"http://localhost\":http://localhost";
        return 'not implemented';
    }

    protected function _replaceBugitorLinks($already_matched) {
//        $view =& ClassRegistry::getObject('view');
//        $project = isset($view->viewVars['main_project']['Project']['identifier']) ? $view->viewVars['main_project']['Project']['identifier'] : null;
        list($all, $leading, $esc, $prefix,, $sep, $oid) = $already_matched;
        if ($sep === "" && $oid === "") {
            $sep = $already_matched[7];
            $oid = $already_matched[8];
        }
        $link = "";
        if ($esc === "") {
            if($sep === '#') {
                $oid = (int)$oid;
                switch($prefix) {
                case '':
                    $issue = Issue::model()->with(array('project', 'tracker'))->findByPk((int) $oid);
                    if ($issue) {
                        $link = CHtml::link('#' . $issue->id, Yii::app()->request->hostInfo.'/projects/'.$issue->project->identifier.'/issue/view/'.$issue->id, array('class' => ($issue->closed == 1) ? 'issue closed' : 'issue', 'title' => $issue->subject));
                    }
                    break;
                }
            } elseif ($sep === ':') {
                // removes the double quotes if any
                $name = preg_replace('{^"(.*)"$}', '\\1', $oid);
                switch ($prefix) {
//                    case 'document':
//                        break;
//                    case 'version':
//                        break;
//                    case 'commit':
//                        break;
//                    case 'source':
//                    case 'export':
//                        break;
//                    case 'attachment':
//                        break;
                    case 'rev':
                        $changeset = Changeset::model()->with('scm')->findByAttributes(array('revision' => $oid));
                        if($changeset)
                            $link = CHtml::link('rev:' . $changeset->revision, Yii::app()->request->hostInfo.'/projects/'.$changeset->scm->project->identifier.'/changeset/view/'.$issue->id, array('title' => $changeset->message));
                        break;
                }
            } // if sep = :
        } // if esc
        $result = $leading;
        if ($link !== "") {
            $result .= $link;
        } else {
            $result .= "${prefix}${sep}${oid}";
        }
        return $result;
    }

    public function textilize($content, $parseSmilies = true) {
        $text = $this->getTextile()->TextileThis($content);
        $text = preg_replace_callback('{([\s\(,\-\>]|^)(!)?(attachment|document|version|commit|source|export|message|rev)?((#|rev\:)([A-z0-9]+)|(:)([^"\s<>][^\s<>]*?|"[^"]+?"))(?=(?=[[:punct:]]\W)|\s|<|$)}',
                    array($this, '_replaceBugitorLinks'),
                    $text);
        if($parseSmilies) {
            $text = $this->smiley($text);
        }
        return $text;
    }

}
