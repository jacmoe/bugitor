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
<?php $this->widget('application.widgets.HighlightDiffWidget.HighlightDiffWidget'); ?>
<h3 class="code">Code</h3>
<a name="top"></a>
<div id="changelog">
<div id="codemenu" class="box">
    <?php
    $this->widget('BugitorMenu', array(
        'items' => array(
            array('label' => 'Overview', 'url' => array('/projects/' . $_GET['identifier'] . '/code'), 'id' => 'notused'),
            array('label' => 'Changesets', 'url' => array('/projects/' . $_GET['identifier'] . '/changesets'), 'id' => 'changeset/index'),
//            array('label' => 'Source', 'url' => array('/projects/' . $_GET['identifier'] . '/code'), 'id' => 'project/index'),
        ),
    )); ?>
</div>
<div id="changesets-inner"><h3>Changeset <?php echo $model->short_rev; ?>:<?php echo $model->revision; ?></h3></div>
<div class="box">
<div id="source-summary">
    <dl class="relations">
        <dt>commit <?php echo $model->short_rev; ?></dt>
        <dd><?php echo $model->revision; ?></dd>
        <?php if($model->parent_count > 1) : ?>
            <?php $parents = explode(",", $model->parents) ?>
            <?php $count = 0; ?>
            <?php while($count < $model->parent_count) : ?>
                <?php list($parent_short, $parent_rev) = explode(":", $parents[$count]); ?>
                <dt>parent <?php echo $parent_short; ?></dt>
                <dd><?php echo Bugitor::link_to_changeset(Changeset::changesetFromRevision($parent_rev)); ?></dd>
                <?php $count++; ?>
            <?php endwhile; ?>
        <?php else : ?>
            <?php if($model->parent_count == 0) : ?>
                <dt>parent</dt>
                <dd>none</dd>
            <?php else : ?>
                <?php list($parent_short, $parent_rev) = explode(":", $model->parents); ?>
                <dt>parent <?php echo $parent_short; ?></dt>
                <dd><?php echo Bugitor::link_to_changeset(Changeset::changesetFromRevision($parent_rev)); ?></dd>
            <?php endif; ?>
        <?php endif; ?>
        <dt>branch</dt>
        <dd><?php echo $model->branches; ?></dd>
    </dl>
    <div><?php echo Yii::app()->textile->textilize($model->message, true, false); ?></div>
    <dl class="metadata">
        <dt>Who</dt>
        <dd style="position: relative; left: 18px;"><?php echo Bugitor::link_to_user_author($model->user, $model->author); ?></dd>
        <dd><?php echo Bugitor::gravatar($model->user); ?></dd>
        <dt>When</dt>
        <dd style="position: relative; left: -35px;"><?php echo Time::timeAgoInWords($model->commit_date); ?></dd>
    </dl>
</div>
</div>
<div id="changeset" class="layout-box">
<h3>Changing <?php echo count($model->changes) ?> files:</h3>
<div class="quiet">
<?php echo ($model->edit > 0) ? $model->edit . ' modified. ' : '' ?>
<?php echo ($model->add > 0) ? $model->add . ' added. ' : '' ?>
<?php echo ($model->del > 0) ? $model->del . ' deleted. ' : '' ?></div>
<?php foreach($model->changes as $change) : ?>
    <?php
    $change_class = '';
    switch ($change->action) {
        case 'M':
            $change_class = 'class="change-modified"';
            break;
        case 'A':
            $change_class = 'class="change-added"';
            break;
        case 'D':
            $change_class = 'class="change-removed"';
            break;
        default:
            $change_class = 'class="change-modified"';
            break;
    }
    ?>
    <?php if($change->action != 'C') : ?>
        <a <?php echo $change_class; ?> href="#<?php echo $change->path; ?>"><?php echo $change->path; ?></a><br/>
    <?php endif; ?>
<?php endforeach; ?>
</div>
<?php foreach($model->changes as $change) : ?>
    <?php if('M' == $change->action) : ?>
        <a name="<?php echo $change->path; ?>"></a><div class="diff box">
        <?php echo htmlspecialchars($change->diff); ?>
        </div>
        <a style="float: right;" href="#top">Up To File-list</a>
    <?php endif; ?>
<?php endforeach; ?>
</div>