<?php foreach($watchers as $watcher): ?>
<?php echo Bugitor::link_to_user($watcher->user); ?> 
<?php endforeach; ?>
