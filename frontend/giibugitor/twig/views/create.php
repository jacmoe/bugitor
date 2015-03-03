<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */
?>

{{use('yii/helpers/Html')}}

{{ set(this, 'title', 'Create') }}
{# $this->params['breadcrumbs'][] = ['label' => '<?= Inflector::pluralize(
    Inflector::camel2words(StringHelper::basename($generator->modelClass))
) ?>', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
#}

?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass), '-', true) ?>-create">

    <p class="pull-left">
        {{ html.a('Cancel', url.previous(), {'class': 'btn btn-default'}) |raw}}
    </p>
    <div class="clearfix"></div>

    {{ this.render('_form.html', {
        'model': model
    }) |raw }}

</div>
