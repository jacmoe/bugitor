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
<?php $this->widget('application.components.HighlightDiffWidget.HighlightDiffWidget'); ?>
<h3 class="code">View Changeset <?php echo $model->short_rev; ?>:<?php echo $model->revision; ?></h3>
<a name="top"></a>
<div class="box">
<div id="source-summary">
    <dl class="relations">
        <dt>commit <?php echo $model->short_rev; ?></dt>
        <dd><?php echo $model->revision; ?></dd>
        <dt>parent <?php echo $model->short_rev-1; ?></dt>
        <dd><?php echo $model->parent; ?></dd>
        <dt>branch</dt>
        <dd><?php echo $model->branch; ?></dd>
    </dl>
    <div><?php echo Yii::app()->textile->textilize($model->message); ?></div>
    <dl class="metadata">
        <dt>Who</dt>
        <dd><?php echo Bugitor::link_to_user($model->user); ?></dd>
        <dd><?php echo Bugitor::gravatar($model->user); ?></dd>
        <dt>When</dt>
        <dd style="position: relative; left: -55px;"><?php echo Time::timeAgoInWords($model->commit_date); ?></dd>
    </dl>
</div>
</div>
<div id="changeset-changed" class="layout-box">
<?php foreach($model->changes as $change) : ?>
<a class="change-modified" href="#<?php echo $change->path; ?>"><?php echo $change->path; ?></a><br/>
<?php endforeach; ?>
</div>
<?php foreach($model->changes as $change) : ?>
<?php $rev = $model->short_rev - 1; ?>
<a name="<?php echo $change->path; ?>"></a><div class="diff box">
<?php echo htmlspecialchars(`/usr/bin/hg diff --git -r{$rev} -R /opt/lampp/htdocs/repositories/bugitor --cwd /opt/lampp/htdocs/repositories/bugitor {$change->path}`); ?>
</div>
<a style="float: right;" href="#top">Up To File-list</a>
<?php endforeach; ?>
