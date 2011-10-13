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
<?php
if(isset($_GET['tab'])) {
    $this->pageTitle = $information->name . ' - Settings - ' . ucfirst($_GET['tab']);
} else {
    $this->pageTitle = $information->name . ' - Settings - Information';
}
?>
<h3 class="settings">Settings</h3>
<div class="tabs">
<ul>
<?php foreach($tabs as $tab): ?>
<?php if($tab['name'] == $selected_tab): ?>
    <li><?php echo CHtml::link($tab['label'], $this->createUrl('/project/settings', array('identifier' => $_GET['identifier'], 'tab' => $tab['name'])) , array('class' => 'selected')); ?></li>
<?php else : ?>
    <li><?php echo CHtml::link($tab['label'], $this->createUrl('/project/settings', array('identifier' => $_GET['identifier'], 'tab' => $tab['name']))); ?></li>
<?php endif; ?>
<?php endforeach; ?>
</ul>
</div>
<?php foreach($tabs as $tab): ?>
<?php if($tab['name'] == $selected_tab): ?>
<?php echo'<div ?tab='.$tab['name'].' class="tab-content">';
$this->renderPartial($tab['partial'], array('model' => ${$tab['name']}, 'name' => $information->name));
echo '</div>';
?>
<?php else : ?>
<?php echo'<div ?tab='.$tab['name'].' class="tab-content" style="display:none;">';
$this->renderPartial($tab['partial'], array('model' => ${$tab['name']}, 'name' => $information->name));
echo '</div>';
?>
<?php endif; ?>
<?php endforeach; ?>
