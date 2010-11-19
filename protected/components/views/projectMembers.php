<?php $members = $this->getMembers(); ?>
<?php foreach($members as $member) : ?>
<div>
<?php echo Bugitor::link_to_user($member->user); ?>
<?php echo ' : ' . $member->role; ?>
</div>
<?php endforeach; ?>