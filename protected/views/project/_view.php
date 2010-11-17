<div class="splitcontentleft">
    <div class="project box">
        <h2><?php echo CHtml::link($data->name, array('project/view', 'identifier' => $data->identifier)); ?></h2>
        <?php echo $data->getDescription(); ?>
        <?php if($data->homepage != ''): ?>
        Homepage: <?php echo CHtml::link($data->homepage); ?>
        <?php endif; ?>
        <br/>
        <br/>
        <div class="alt" style="font-size:smaller;">Created : <?php echo Time::timeAgoInWords($data->created); ?></div>
    </div>
</div>
<div class="splitcontentright">
    <div class="project box">
        project details
    </div>
</div>
<div class="clear"><hr/></div>