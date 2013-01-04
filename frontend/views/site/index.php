<?php
/**
 * index.php
 *
 * @author: antonio ramirez <antonio@clevertech.biz>
 * Date: 7/22/12
 * Time: 8:30 PM
 */
?>
This is index view file...
And some other stuff
<?php if (Yii::app()->user->isGuest): ?>
    <?php echo CHtml::Link('Login', $this->createUrl('site/login')); ?>
<?php else: ?>
    <?php echo CHtml::Link('Logout', $this->createUrl('site/logout')); ?>
    Logged in as: <?php echo Yii::app()->user->name; ?>
<?php endif; ?>
