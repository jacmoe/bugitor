<?php $this->pageTitle=Yii::app()->name . ' : Welcome'; ?>
<h3 class="welcome">Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h3>
<div class="splitcontentleft">
    <div class="activity box">
        <h3>Latest Activity</h3>
        <br/>
    </div>
    <div class="issues box">
        <h3>My Issues</h3>
        <br/>
    </div>
    <div class="watched box">
        <h3>Watched Issues</h3>
        <br/>
    </div>
</div>
<div class="splitcontentright">
    <div class="project box">
        <h3>My Projects</h3>
        <br/>
    </div>
    <div class="assigned box">
        <h3>Issues Assigned to me</h3>
        <br/>
    </div>
</div>
