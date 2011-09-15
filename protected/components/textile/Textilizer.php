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
        $msg = preg_replace("/[\s|\.]:\)/",":smile:", $msg);
        $msg = preg_replace("/[\s|\.]\:D/",":big_grin:", $msg);
        $msg = preg_replace("/[\s|\.];\)/",":wink:", $msg);
        $msg = preg_replace("/[\s|\.]:P/",":razz:", $msg);
        $msg = preg_replace("/[\s|\.]:p/",":razz:", $msg);
        $msg = preg_replace("/[\s|\.]:\(/",":sad:", $msg);
        $msg = preg_replace("/:smile:/","<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_smile.gif' title='Smile' alt='Smile'>", $msg);
        $msg = preg_replace("/:wink:/","<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_wink.gif' title='Wink' alt='Wink'>", $msg);
        $msg = preg_replace("/\=P/","<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_razz.gif' title='p' alt='p'>", $msg);
        $msg = preg_replace("/[\s|\.]\:P/","<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_razz.gif' title='p' alt='p'>", $msg);
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
                    } else {
                        $link = $all;
                    }
                    break;
                }
            } elseif ($sep === ':') {
                // removes the double quotes if any
                $name = preg_replace('{^"(.*)"$}', '\\1', $oid);
                switch ($prefix) {
//                    case 'document':
//                        break;
//                    case 'milestone':
//                        break;
//                    case 'commit':
//                        break;
//                    case 'source':
//                    case 'export':
//                        break;
//                    case 'attachment':
//                        break;
                    case 'http':
                        $link = "<a href=\"http:{$oid}\">http:{$oid}</a>";
                        break;
                    case 'rev':
                        $changeset = Changeset::model()->with('scm')->findByAttributes(array('revision' => $oid));
                        if($changeset) {
                            $link = CHtml::link('rev:' . $changeset->revision, Yii::app()->config->get('hostname').'projects/'.$changeset->scm->project->identifier.'/changeset/view/'.$changeset->id, array('title' => $changeset->message));
                        }
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

public function fixcodeblocks($string) {
	// Create a new array to hold our converted string
	$newstring = array();
	
	// This variable will be true if we are currently between two code tags
	$code = false;
	
	// The total length of our HTML string
	$j = mb_strlen($string);
	
	// Loop through the string one character at a time
	for ($k = 0; $k < $j; $k++) {
		// The current character
		$char = mb_substr($string, $k, 1);
		
		if ($code) {
			// We are between code tags
			// Check for end code tag
			if ($this->atendtag($string, $k)) {
				// We're at the end of a code block
				$code = false;
				
				// Add current character to array
				array_push($newstring, $char);
				
			} else {
				// Change special HTML characters
				$newchar = htmlspecialchars($char, ENT_QUOTES);
				
				// Add character code to array
				array_push($newstring, $newchar);
			}
		} else {
			// We are not between code tags
			// Check for start code tag
			if ($this->atstarttag($string, $k)) {
				// We are at the start of a code block
				$code = true;
			}
			// Add current character to array
			array_push($newstring, $char);
		}
	}
	//Turn the new array into a string
	$newstring = join("", $newstring);
	
	// Return the new string
	return $newstring;
}

public function atstarttag($string, $pos) {
	// Only check if the last 6 characters are the start code tag
	// if we are more then 6 characters into the string
	if ($pos > 4) {
		// Get previous 6 characters
		$prev = mb_substr($string, $pos - 5, 6);
		
		// Check for a match
		if ($prev == "<code>") {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

public function atendtag($string, $pos) {
	// Get length of string
	$slen = mb_strlen($string);
	
	// Only check if the next 7 characters are the end code tag
	// if we are more than 6 characters from the end
	if ($pos + 7 <= $slen) {
		// Get next 7 characters
		$next = mb_substr($string, $pos, 7);
		
		// Check for a match
		if ($next == "</code>") {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}
        public function textilize($content, $parseSmilies = true, $use_textile = true) {
        $text = $this->fixcodeblocks($content);
        if(!$use_textile) {
            $text_lines = explode("\n", $text);
            $text = implode('<br/>', $text_lines);
        } else {
            $text = $this->getTextile()->TextileThis($content);
        }
        $text = preg_replace_callback('{([\s\(,\-]|^)(!)?(attachment|document|milestone|commit|source|export|message|http|rev)?((#|rev\:)([A-z0-9]+)|(:)([^"\s<>][^\s<>]*?|"[^"]+?"))(?=(?=[[:punct:]]\W)|\s|<|$)}',
                    array($this, '_replaceBugitorLinks'),
                    $text);
        if($parseSmilies) {
            $text = $this->smiley($text);
        }
        if(!$use_textile) {
            $text .= '<br/><br/>';
        }
        return $text;
    }

}
