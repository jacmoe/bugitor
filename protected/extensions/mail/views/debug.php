<div class="emailDebug">
<h2>.: Dumping email</h2>
<p>The mail extension is in debug mode, which means that the email was not actually sent but is flashed here instead.</p>
<h3>Basics</h3>
<strong>To:</strong>
<ul>
<?php
foreach ($message->to as $email => $name) {
	echo '<li>'.CHtml::encode($name).CHtml::encode(' <'.$email.'>').'</li>';
}
?>
</ul>
<br /><strong>Subject:</strong>
<?php echo CHtml::encode($message->subject) ?>
<div class="emailMessage"><?php echo $message->body ?></div>
<h3>Headers</h3>
<p><?php
foreach ($message->headers->getAll() as $header) {
	echo CHtml::encode($header)."<br />\n";
}
?></p>
</div>