<?php $comments = array_reverse($comments); ?>
<?php foreach($comments as $comment): ?>
<div class="comment">
<div class="author">
<?php echo ucfirst($comment->author->username); ?>:
</div>
<div class="time">
<?php echo Time::timeAgoInWords($comment->created); ?>
</div>
<div class="content">
<?php echo nl2br(CHtml::encode($comment->content)); ?>
</div>
<hr>
</div><!-- comment -->
<?php endforeach; ?>
