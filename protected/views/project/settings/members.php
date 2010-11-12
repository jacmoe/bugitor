<?php
$this->pageTitle = $name . ' - Settings - Members';
?>
<h3>Members</h3>
<?php
foreach($model as $member) {
    echo $member->username . '<br/>';
}
?>
<br/>
