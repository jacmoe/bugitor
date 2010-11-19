<div class="splitcontentleft">
    <div class="project box">
        <?php $this->widget('ProjectBox', array('project' => $data)) ?>
    </div>
</div>
<div class="splitcontentright">
    <div class="project box">
        <?php $this->widget('ProjectIssuesByTracker', array('project' => $data)) ?>
    </div>
</div>
<div class="clear"><hr/></div>