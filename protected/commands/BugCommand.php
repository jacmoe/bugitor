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

Yii::import('application.vendors.mimeparser.*');
require_once('mime_parser.php');
require_once('body_fetcher.php');
require_once('rfc822_addresses.php');

class BugCommand extends CConsoleCommand {

    public function run($args) {
            $issuees = Issue::model()->findByPk(33);
            mail("jacmoe@mail.dk", "issue was found", "The MimeParser was run", "admin@ogitor.org");

            $new_comment = new Comment;
            $new_comment->content = 'test';
            $new_comment->create_user_id = 1;
            $new_comment->update_user_id = 1;
            $new_comment->issue_id = 33;
            if($new_comment->validate()){
                mail("jacmoe@mail.dk", "Comment was saved", "success?", "admin@ogitor.org");
                $new_comment->save(false);
            } else {
                mail("jacmoe@mail.dk", "comment did not validate", "The MimeParser was run", "admin@ogitor.org");
            }
            $issuees->updated_by = 1;
            if($issuees->validate()){
                mail("jacmoe@mail.dk", "Issue was saved", "success?", "admin@ogitor.org");
                $issuees->save(false);
                $issuees->sendNotifications($issuees->id, $new_comment);
                $issuees->addToActionLog($issuees->id, 'note', 'http://files.ogitor.org/projects/'.$issuees->project->identifier.'/issue/view'.$issuees->id.'#note-'.$issuees->commentCount, $new_comment);

            } else {
                mail("jacmoe@mail.dk", "issue did not validate", "The MimeParser was run", "admin@ogitor.org");
            }
            mail("jacmoe@mail.dk", "Script was run", "The script was run succesfully", "admin@ogitor.org");
    }

}
