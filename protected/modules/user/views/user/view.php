<div class="contextual_left">
<?php echo Bugitor::gravatar($model); ?>
</div>
<h2><?php echo ucfirst($model->username); ?></h2>
<br/>
<div class="splitcontentleft">
    <ul>
        <li>Registered on: <?php echo date("d.m.Y H:i:s",$model->createtime); ?></li>
        <li>Last connection: <?php echo (($model->lastvisit)?date("d.m.Y H:i:s",$model->lastvisit):UserModule::t('Never')) ?></li>
    </ul>
    <br/>
<!--    <h3>Projects</h3>
    <ul>
        <li><a href="/projects/redmine">Redmine</a>
        (Contributor, 2011-01-01)</li>
    </ul>-->
</div>
<div class="splitcontentright">
<!--<h3><a href="/activity?from=2011-04-23&amp;user_id=11192">Activity</a></h3>
<p>
Reported issues: 16
</p>
<div id="activity">
<h4>2011-04-23</h4>
<dl>
  <dt class="changeset">
  <span class="time">11:25</span>
  <span class="project">Redmine</span>
  <a href="/projects/redmine/repository/revisions/5543">Revision 5543: scm: mercurial: update locales for repository note in setting.</a></dt>
  <dd><span class="description"></span></dd>
</dl>
</div>-->
</div>
<hr/>
<ul class="actions">
	<li><?php echo CHtml::link(UserModule::t('List Users'),array('index')); ?></li>
</ul><!-- actions -->
