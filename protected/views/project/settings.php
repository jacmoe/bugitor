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
    <li><?php echo CHtml::link($tab['label'], '/projects/'.$_GET['identifier'].'/settings'.'?tab='.$tab['name'], array('class' => 'selected')); ?></li>
<?php else : ?>
    <li><?php echo CHtml::link($tab['label'], '/projects/'.$_GET['identifier'].'/settings'.'?tab='.$tab['name']); ?></li>
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
