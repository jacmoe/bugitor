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
 * Copyright (C) 2009 - 2012 Bugitor Team
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
<div id="add_attachment"></div>
<?php if(count($attachments)>0) : ?>
<div id="attachmentlist">
    <ul>
        <?php foreach($attachments as $attachment): ?>
            <li class="icon icon-attachment"><?php echo CHtml::link($attachment->name, Yii::app()->baseUrl . '/uploads/'.$parent_id.'/'.$attachment->name); ?>
                <small><i>(<?php echo Bugitor::getReadableFileSize($attachment->size); ?>)</i></small>
                - Added by <?php echo Bugitor::link_to_user($attachment->user) ?>
                <?php echo Bugitor::timeAgoInWords($attachment->created); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>
<div id="results"></div>
<?php if(Yii::app()->user->checkAccess('Issue.Update')) : ?>
<a href="#add_attachment" onClick="$('#add_attach').toggle();">Add Attachment</a>
<div class="issues" id="add_attach" style="display: none;">
<?php
$this->widget('ext.EJqueryUpload.EJqueryUpload', array(
                    'url' => Yii::app()->createUrl("issue/upload", array("parent_id" => $parent_id)),
                    'id' => 'fileup',
                    'result_id' => 'results',
));
?>
    <div class="quiet">Filesize max. 2MB - Allowed filetypes: jpg, jpeg, gif, png, txt, patch, diff, bmp, log, zip, tgz, tar.bz2, bz2, tar, tar.gz and gz.</div>
</div>
<?php endif; ?>
