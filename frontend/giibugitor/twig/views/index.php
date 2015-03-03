<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * @var yii\web\View $this
 * @var schmunk42\giiant\crud\Generator $generator
 */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

?>

{{use('yii/helpers/Html')}}
{{use('<?= $generator->indexWidgetType === 'grid' ? "yii/grid/GridView" : "yii/widgets/ListView" ?>')}}
{{use('yii/bootstrap/ButtonDropdown')}}

{{ set(this, 'title', '<?= Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>') }}

{# $this->params['breadcrumbs'][] = $this->title; #}

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass), '-', true) ?>-index">

    <div class="clearfix">
        <p class="pull-left">
            {{ html.a('<span class="glyphicon glyphicon-plus"></span> New <?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?>', ['create'],{'class': 'btn btn-success'}) | raw }}
        </p>

        <div class="pull-right">
            <?php
            $items = [];
            $model = new $generator->modelClass;
            ?>
            <?php foreach ($generator->getModelRelations($model) AS $relation): ?>
                <?php
                // relation dropdown links
                $iconType = ($relation->multiple) ? 'arrow-right' : 'arrow-left';
                if ($generator->isPivotRelation($relation)) {
                    $iconType = 'random';
                }
                $controller = $generator->pathPrefix . Inflector::camel2id(
                        StringHelper::basename($relation->modelClass),
                        '-',
                        true
                    );
                $route = $generator->createRelationRoute($relation,'index');
                $label      = Inflector::titleize(StringHelper::basename($relation->modelClass), '-', true);
                $items[] = [
                    'label' => '<i class="glyphicon glyphicon-' . $iconType . '"> ' . $label . '</i>',
                    'url'   => [$route]
                ]
                ?>
            <?php endforeach; ?>

            {{ button_dropdown_widget({
                'id' : 'giiant-relations',
                'encodeLabel': false,
                'label': '<span class="glyphicon glyphicon-paperclip"></span> Relations',
                'dropdown': {
                    'options' : {
                        'class' : 'dropdown-menu-right'
                    },
                    'encodeLabels': false,
                    'items': <?= \yii\helpers\Json::encode($items) ?>
                }
            }) }}
        </div>
    </div>

    {{ grid_view_widget({
        'dataProvider': dataProvider,
        'filterModel': searchModel,
        'columns': [
        <?php
            $count = 0;
            foreach ($generator->getTableSchema()->columns as $column) {
                $format = trim($generator->columnFormat($column,$model));
                if ($format == false) continue;
                if (++$count < 8) {
                    echo "\t\t\t{$format},\n";
                } else {
                    //echo "\t\t\t/*{$format}*/\n";
                }
            }
            ?>
            {
                'class': '<?= str_replace('\\', '\\\\', $generator->actionButtonClass) ?>',
                'contentOptions': {'nowrap':'nowrap'}
            }
        ]
    }) }}

</div>
