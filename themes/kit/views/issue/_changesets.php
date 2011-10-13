<?php $changeset_issues = array_reverse($changeset_issues); ?>
<div id="issue-changesets">
<h3>Related changesets</h3>
<?php foreach($changeset_issues as $changeset_issue) : ?>
<div class="changeset odd">
    <p class="icon icon-changeset">Changeset <?php echo Bugitor::link_to_changeset($changeset_issue->changeset) ?><br/>
        <span class="author">Committed by <?php echo Bugitor::link_to_user($changeset_issue->changeset->user) ?> <?php echo Bugitor::timeAgoInWords($changeset_issue->changeset->commit_date); ?></span></p>
        <div class="changeset-changes">
        <?php echo Yii::app()->textile->textilize($changeset_issue->changeset->message, true, false); ?>
        </div>
    </div>
<?php endforeach; ?>
</div>
