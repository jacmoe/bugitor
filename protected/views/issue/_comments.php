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
<?php $comments = array_reverse($comments); ?>
<div id="history">
    <h3>History</h3>
<?php foreach($comments as $comment): ?>
<div id="change-1210" class="journal">
<h4><div style="float: right;"><a href="/projects/ogitor/issues/view/51#note-6">#6</a></div>
Updated by <?php echo Bugitor::link_to_user($comment->author); ?>
 <?php echo Time::timeAgoInWords($comment->created); ?></h4>
<span><?php echo Bugitor::gravatar($comment->author->email); ?></span>
<?php if($comment->details) : ?>
<ul>
<?php foreach($comment->details as $detail): ?>
<li><?php echo $detail->change; ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
<div class="content">
<p><?php echo $comment->content; ?>
<br/></p>
</div>
</div>
<?php endforeach; ?>
</div>