<?php foreach($watchers as $watcher): ?>
<div class="comment">
<div class="author">
<?php echo ucfirst($watcher->user_id); ?>:
</div>
</div><!-- comment -->
<?php endforeach; ?>
