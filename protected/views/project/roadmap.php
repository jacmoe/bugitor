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
$this->pageTitle = $model->name . ' - Roadmap - ' . Yii::app()->name;
?>
<h3 class="roadmap">Roadmap</h3>
<?php foreach($model->versions as $version) : ?>
<h3><?php echo $version->name; ?></h3>
Due: <?php echo $version->effective_date; ?><br/>
<?php echo $version->issueCount; ?> issues.<br/>
<?php $num_actual_issues = $version->issueCount - $version->issueCountRejected; ?>
<?php $open_percent = (($version->issueCountOpen / $num_actual_issues)*100); ?>
<?php $closed_percent = (($version->issueCountResolved / $num_actual_issues)*100); ?>
<?php $done_ratio = ((($version->issueCountDone / 100) * $version->issueCountOpen) / $num_actual_issues) * 100; ?>
<?php $open_ratio = $open_percent - $done_ratio; ?>
<?php echo $version->issueCountOpen; ?> open. (<?php echo number_format($open_percent) ?>%)<br/>
Done: <?php echo number_format($version->issueCountDone); ?>%.<br/>
<?php echo $version->issueCountResolved; ?> resolved. (<?php echo $version->issueCountClosed; ?> closed - <?php echo $version->issueCountRejected; ?> rejected) (<?php echo number_format($closed_percent) ?>%)<br/>
Done ratio: <?php echo number_format($done_ratio) ?>%<br/>
Open ratio: <?php echo number_format($open_ratio) ?>%<br/>
<?php echo Bugitor::big_progress_bar(array($closed_percent, $done_ratio, $open_ratio), array('width' => '500px', 'legend' => $closed_percent + $done_ratio.'%')); ?>
<?php echo $version->issueCountResolved; ?> closed (<?php echo number_format($closed_percent) ?>%) 2 open (<?php echo number_format($open_percent) ?>%) <s>1 rejected</s>
<?php endforeach; ?>
