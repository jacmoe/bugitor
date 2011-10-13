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
        $msg = preg_replace("/:smile:/","<span class=\"smilies-icon_smile\" title=\"Smile\"></span>", $msg);
        $msg = preg_replace("/:wink:/","<span class=\"smilies-icon_wink\" title=\"Wink\"></span>", $msg);
        $msg = preg_replace("/\=P/","<span class=\"smilies-icon_razz\" title=\"Razz\"></span>", $msg);
        $msg = preg_replace("/[\s|\.]\:P/","<span class=\"smilies-icon_razz\" title=\"Razz\"></span>", $msg);
        $msg = preg_replace("/:razz:/","<span class=\"smilies-icon_razz\" title=\"Razz\"></span>", $msg);
        $msg = preg_replace("/:big_grin:/","<span class=\"smilies-icon_biggrin\" title=\"Big Grin\"></span>", $msg);
        $msg = preg_replace("/:cheesy_grin:/","<span class=\"smilies-icon_cheesygrin\" title=\"Cheezy Grin\"></span>", $msg);
        $msg = preg_replace("/:confused:/","<span class=\"smilies-icon_confused\" title=\"Confused\"></span>", $msg);
        $msg = preg_replace("/:cool:/","<span class=\"smilies-icon_cool\" title=\"Cool\"></span>", $msg);
        $msg = preg_replace("/:cry:/","<span class=\"smilies-icon_cry\" title=\"Cry\"></span>", $msg);
        $msg = preg_replace("/:crying:/","<span class=\"smilies-icon_cry\" title=\"Crying\"></span>", $msg);
        $msg = preg_replace("/:sad:/","<span class=\"smilies-icon_sad\" title=\"Sad\"></span>", $msg);
        $msg = preg_replace("/:surprised:/","<span class=\"smilies-icon_surprised\" title=\"Surprised\"></span>", $msg);
        $msg = preg_replace("/:evil:/","<span class=\"smilies-icon_evil\" title=\"Evil\"></span>", $msg);
        $msg = preg_replace("/:twisted:/","<span class=\"smilies-icon_twisted\" title=\"Twisted\"></span>", $msg);
        $msg = preg_replace("/:blush:/","<span class=\"smilies-icon_redface\" title=\"Blush\"></span>", $msg);
        $msg = preg_replace("/:neutral:/","<span class=\"smilies-icon_neutral\" title=\"Neutral\"></span>", $msg);
        $msg = preg_replace("/:mad:/","<span class=\"smilies-icon_mad\" title=\"Mad\"></span>", $msg);
        $msg = preg_replace("/:eek:/","<span class=\"smilies-icon_eek\" title=\"Eek\"></span>", $msg);
        $msg = preg_replace("/:roll_eyes:/","<span class=\"smilies-icon_rolleyes\" title=\"Roll Eyes\"></span>", $msg);
        $msg = preg_replace("/:frown:/","<span class=\"smilies-icon_frown\" title=\"Frown\"></span>", $msg);
        return $msg;
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
				$newchar = htmlspecialchars($char, ENT_NOQUOTES);
				
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
	if ($pos /*+ 7*/ <= $slen) {
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
        $text = $content;//$this->fixcodeblocks($content);
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
