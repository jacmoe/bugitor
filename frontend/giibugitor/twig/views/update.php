<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$urlParams = $generator->generateUrlParams();

function repTwigUpdateUrlParams($a){
    $a = str_replace('->', '.', $a);
    $a = str_replace('$', '', $a);
    $a = str_replace('=>', ':', $a);
    return $a;
}

?>

{{use('yii/helpers/Html')}}

{{ set(this, 'title', '<?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?> Update #{model.<?= $generator->getNameAttribute() ?>} ') }}

{#
$this->params['breadcrumbs'][] = ['label' => '<?= Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model-><?= $generator->getNameAttribute() ?>, 'url' => {'view': '', <?= repTwigUpdateUrlParams($urlParams) ?>}];
$this->params['breadcrumbs'][] = 'Edit'; #}

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass),'-', true) ?>-update">

    <p>
        {{ html.a('<span class="glyphicon glyphicon-eye-open"></span> View', url('view', {<?= repTwigUpdateUrlParams($urlParams) ?>}), {'class' : 'btn btn-info'} ) | raw }}
    </p>

    {{ this.render('_form.html', {
        'model': model
    }) |raw }}

</div>
