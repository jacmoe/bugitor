<?php
$this->pageTitle = $name . ' - Settings - Issue Categories';
?>
<h3>Issue Categories</h3>
<?php
foreach($model as $category) {
    echo $category->name . '<br/>';
}
?>
<br/>
