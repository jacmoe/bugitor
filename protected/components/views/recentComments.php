<ul>
<?php foreach($this->getRecentComments() as $comment): ?>
<div class="author">
<?php echo $comment->author->username; ?> added a comment.
</div>
<div class="issue">
<?php echo CHtml::link(CHtml::encode($comment->issue->subject),
array('issue/view', 'id'=>$comment->issue->id)); ?>
</div>
<?php endforeach; ?>
</ul>