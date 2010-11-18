<?php foreach($watchers as $watcher): ?>
<?php echo Bugitor::link_to_user($watcher->user->username, $watcher->user->id); ?> 
<?php endforeach; ?>
