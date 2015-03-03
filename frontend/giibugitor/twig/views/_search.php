<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */
?>
{{use('yii/helpers/Html')}}
{{use('yii/widgets/ActiveForm')}}

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass), '-', true) ?>-search">

	{% set form = active_form_begin({
		'action': ['index'],
		'method': 'get'
	}) %}

<?php
$count = 0;
function phpToTwigSearchView($a){
	$a = str_replace("->", '.', $a);
	$a = str_replace("$", '', $a);
	$a = str_replace("=>", ':', $a);
	$a = str_replace("[", '{', $a);
	$a = str_replace("]", '}', $a);
	return $a;
}

foreach ($generator->getTableSchema()->getColumnNames() as $attribute) {
	if (++$count < 6) {
		echo "\t\t{{ " . phpToTwigSearchView($generator->generateActiveSearchField($attribute) ) . " }}\n\n";
	} else {
		echo "\t\t{# " . phpToTwigSearchView($generator->generateActiveSearchField($attribute) ) . " #}\n\n";
	}
}
?>
		<div class="form-group">
			{{ html.submitButton('Search', {'class' : 'btn btn-primary'}) | raw }}
			{{ html.resetButton('Reset', {'class' : 'btn btn-primary'}) | raw }}
		</div>

	{{ active_form_end() }}

</div>
