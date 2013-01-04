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
 * Copyright (C) 2009 - 2013 Bugitor Team
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
<?php //TODO: check if the user wants the comments in cronological order?    ?>
<?php $comments = array_reverse($comments); ?>
<?php $comment_count = count($comments); ?>
<?php $this->widget('ext.EJeditable.EJeditable', array(
    'url' => $this->createUrl('issue/editcomment'),
    'loadurl' => $this->createUrl('issue/getcomment'),
));?>

<div id="history">
    <h3>History</h3>
    <?php foreach ($comments as $comment): ?>
        <div id="change-1210" class="journal">
            <h4><div style="float:right;">
                    <?php echo CHtml::link('#' . $comment_count, '#note-' . $comment_count); ?>
                    <?php echo CHtml::tag('a', array('name' => 'note-' . $comment_count), '.'); ?>
                </div>
                Updated by <?php echo Bugitor::link_to_user($comment->author); ?>
                <?php echo Bugitor::timeAgoInWords($comment->created); ?></h4>
            <dl><dt>
                <?php echo Bugitor::gravatar($comment->author); ?>
                </dt><dd style="margin-left: 75px;">
                    <div style="font-style:italic;font-size:x-small;color:#525252;"><?php if($comment->modified <> $comment->created) echo 'Comment modified ' . Bugitor::timeAgoInWords($comment->modified); ?></div>
                    <?php if ($comment->details) : ?>
                        <ul>
                            <?php foreach ($comment->details as $detail): ?>
                                <li><?php echo $detail->change; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <div class="<?php echo (isset(Yii::app()->user->id)&&(Yii::app()->user->id == $comment->create_user_id)) ? "edit" : "nedit" ?>" id="<?php echo $comment->id; ?>"><?php echo Yii::app()->textile->textilize($comment->content); ?></div>
                </dd></dl>
        </div>
        <br class="clearfix"/>
        <?php $comment_count--; ?>
    <?php endforeach; ?>
</div>
