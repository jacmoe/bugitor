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
 * Copyright (C) 2009 - 2011 Bugitor Team
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
<?php if(!isset($ajax_shown)) $ajax_shown = false; ?>
<?php $watchers = $model->getWatchers() ?>
<?php if(count($watchers)>0) : ?>
    <?php foreach($watchers as $watcher): ?>
        <?php echo Bugitor::link_to_user($watcher->user); ?>
    <?php endforeach; ?>
<?php endif; ?>
<br/>
<?php if(Yii::app()->user->checkAccess('Issue.Update')) : ?>
<a href="#" onClick="$('#add_watch').toggle();">Add / Remove Watcher</a>
<?php if($ajax_shown) : ?>
<div class="issues" id="add_watch">
<?php else: ?>
<div class="issues" id="add_watch" style="display: none;">
<?php endif; ?>
<?php $this->renderPartial('_addwatchers', array('model' => $model));?>
</div>
<br/>
<?php endif; ?>
<br/>
