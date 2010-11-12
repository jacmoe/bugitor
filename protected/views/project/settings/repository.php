<?php
$this->pageTitle = $name . ' - Settings - Repositories';
?>
<h3>Repositories</h3>
<?php
foreach($model as $repository) {
    echo $repository->name . '<br/>';
}
?>
<br/>
