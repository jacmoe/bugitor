<?php $changeset_issues = array_reverse($changeset_issues); ?>
<div id="issue-changesets">
<h3>Associated revisions</h3>
<?php foreach($changeset_issues as $changeset_issue) : ?>
<div class="changeset odd">
    <p class="icon icon-changeset">Revision <?php echo Bugitor::link_to_changeset($changeset_issue->changeset) ?><br/>
        <span class="author">Committed by <?php echo Bugitor::link_to_user($changeset_issue->changeset->user) ?> <?php echo Time::timeAgoInWords($changeset_issue->changeset->commit_date); ?></span></p>
        <div class="changeset-changes">
        <?php echo Yii::app()->textile->textilize($changeset_issue->changeset->message); ?>
        </div>
    </div>
<?php endforeach; ?>
</div>
