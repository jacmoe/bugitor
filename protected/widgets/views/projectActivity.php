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
<?php $activities = $this->getActivities(); ?>
<?php $pages = $this->getPages(); ?>
<?php $curr_day = 0; ?>
<?php if(null !== $pages) : ?>
<div class="small" style="float:right;"><?php $this->widget('CustomLinkPager',array('pages'=>$pages)); ?></div>
<hr/>
<?php endif; ?>
<div id="activity" class="quiet">
    <?php foreach ($activities as $activity): ?>
    <?php if($curr_day != date('d Y', Time::makeUnix($activity->theDate))) : ?>
    <h3><?php echo (Time::isToday($activity->theDate) ? 'Today' : Yii::app()->dateFormatter->formatDateTime($activity->theDate, 'medium', null)) ; ?></h3>
    <?php $curr_day = date('d Y', Time::makeUnix($activity->theDate)); ?>
    <?php endif; ?>
    <dl>
        <dt class="<?php echo $activity->type; ?>">
            <?php echo Bugitor::gravatar($activity->author, 16); ?>
            <span class="time"><?php echo Time::timeAgoInWords($activity->theDate); ?></span>
            <?php echo CHtml::link($activity->subject, $activity->url) ?>
        </dt>
        <dd><span class="description"><?php echo Yii::app()->textile->textilize(Bugitor::format_activity_description(CHtml::encode($activity->description))); ?></span></dd>
    </dl>
    <?php endforeach; ?>
</div>
<?php if(null !== $pages) : ?>
<hr/>
<div class="small" style="float:right;"><?php $this->widget('CustomLinkPager',array('pages'=>$pages)); ?></div>
<?php endif; ?>
