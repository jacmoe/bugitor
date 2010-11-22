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
        $msg = preg_replace("/:smile:/","<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_smile.gif' title='Smile' alt='Smile'>", $msg);
        $msg = preg_replace("/:wink:/","<img src='".Yii::app()->theme->baseUrl."/images/smilies/icon_wink.gif' title='Wink' alt='Wink'>", $msg);
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

    protected function _replaceCandycaneLinks($already_matched) {
        /* Redmine links
     *
     * Examples:
     *   Issues:
     *     #52 -> Link to issue #52
     *   Changesets:
     *     r52 -> Link to revision 52
     *     commit:a85130f -> Link to scmid starting with a85130f
     *   Documents:
     *     document#17 -> Link to document with id 17
     *     document:Greetings -> Link to the document with title "Greetings"
     *     document:"Some document" -> Link to the document with title "Some document"
     *   Versions:
     *     version#3 -> Link to version with id 3
     *     version:1.0.0 -> Link to version named "1.0.0"
     *     version:"1.0 beta 2" -> Link to version named "1.0 beta 2"
     *   Attachments:
     *     attachment:file.zip -> Link to the attachment of the current object named file.zip
     *   Source files:
     *     source:some/file -> Link to the file located at /some/file in the project's repository
     *     source:some/file@52 -> Link to the file's revision 52
     *     source:some/file#L120 -> Link to line 120 of the file
     *     source:some/file@52#L120 -> Link to line 120 of the file's revision 52
     *     export:some/file -> Force the download of the file
     *  Forum messages:
     *     message#1218 -> Link to message with id 1218
        */
//        $view =& ClassRegistry::getObject('view');
//        $project = isset($view->viewVars['main_project']['Project']['identifier']) ? $view->viewVars['main_project']['Project']['identifier'] : null;
//        list($all, $leading, $esc, $prefix,, $sep, $oid) = $already_matched;
//        if ($sep === "" && $oid === "") {
//            $sep = $already_matched[7];
//            $oid = $already_matched[8];
//        }
//        $link = "";
//        if ($esc === "") {
//            if ($prefix === "" && $sep === 'r') {
//                $Changeset = & ClassRegistry::init('Changeset');
//                if (($project !== "") && ($change_set = $Changeset->find('first', array( 'conditions' => array( 'revision' => $oid))))) {
//                    $link = $this->Html->link("r${oid}",
//                            array('controller' => 'changesets',
//                            'action' => 'view',
//                            'project_id' => $project,
//                            $change_set['Changeset']['id']),
//                            aa('class', 'changeset',
//                            'title',
//                            ''/*truncate_single_line(changeset.comments, 100)*/));
//
//                }
//            } elseif ($sep === '#') {
//                $oid = (int)$oid;
//                switch($prefix) {
//                    case '':
//                        $issue = true;
//                        $Issue = & ClassRegistry::init('Issue');
//                        $issue = $Issue->find('first', array(
//                            'cache' => '_replaceCandycaneLinks_issue_' . $oid,
//                            'conditions' => array('Issue.id' => $oid)));
//                        if ($issue) {
//                            $class = $issue['IssueStatus']['is_closed'] ? 'issue closed' : 'issue';
//                            $link = $this->Html->link("#${oid}",
//                                    array('controller' => 'issues',
//                                    'action' => 'view',
//                                    'project_id' => $project,
//                                    $oid),
//                                    aa('class', $class,
//                                    'title', ''/*"#{truncate(issue.subject, 100)} (#{issue.status.name})")*/));
//                            if ( $issue['IssueStatus']['is_closed']) {
//                                $link = $this->Html->tag('del', $link);
//                            }
//                        }
//                        break;
//                    case 'document':
//                        $document = 1;
//                        /*document =Document.find_by_id(oid, :include => [:project], :conditions => Project.visible_by(User.current))*/
//                        if ($document) {
//                            $link = $this->Html->link($oid/*h(document.title)*/,
//                                    array('controller' => 'documents',
//                                    'action' => 'show',
//                                    'id' => $document),
//                                    aa('class', 'document'));
//                        }
//                        break;
//                    case 'version':
//                        break;
//                    case 'message':
//                        break;
//                }
//            } elseif ($sep === ':') {
//                // removes the double quotes if any
//                $name = preg_replace('{^"(.*)"$}', '\\1', $oid);
//                switch ($prefix) {
//                    case 'document':
//                        $document = 1;
//                        /*document = project.documents.find_by_title(name)*/
//                        if ($project !== "" &&  $document !== "") {
//                            $link = $this->Html->link($name/*h(document.title)*/,
//                                    array('controller' => 'documents',
//                                    'action' => 'show',
//                                    'id' => $document),
//                                    aa('class', 'document'));
//                        }
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
//                }
//            }
//        }
//        $result = $leading;
//        if ($link !== "") {
//            $result .= $link;
//        } else {
//            $result .= "${prefix}${sep}${oid}";
//        }
//        return $result;
    }

    public function textilize($content) {
        $text = $this->getTextile()->TextileThis($content);
//        $text = preg_replace_callback('{([\s\(,\-\>]|^)(!)?(attachment|document|version|commit|source|export|message)?((#|r)([A-z0-9]+)|(:)([^"\s<>][^\s<>]*?|"[^"]+?"))(?=(?=[[:punct:]]\W)|\s|<|$)}',
//                array($this, '_replaceCandycaneLinks'),
//                $text);
        return $this->smiley($text);
    }

}
