<?php
$this->pageTitle = $name . ' - Settings - Versions';
?>
<h3>Versions</h3>
<?php
foreach($model as $version) {
    echo $version->name . '<br/>';
}
?>
<br/>
