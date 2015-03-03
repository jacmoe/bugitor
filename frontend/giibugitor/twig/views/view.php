<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$urlParams = $generator->generateUrlParams();


function repTwigViewUrlParams($a){
    $a = str_replace('->', '.', $a);
    $a = str_replace('$', '', $a);
    $a = str_replace('=>', ':', $a);
    return $a;
}
?>
{{use('yii/helpers/Html')}}
{{use('yii/widgets/DetailView')}}
{{use('yii/grid/GridView')}}
{{use('yii/widgets/Pjax')}}
{{use('yii/bootstrap/Tabs')}}
{{use('Yii')}}

{{set(this, 'title', '<?=
Inflector::camel2words(
    StringHelper::basename($generator->modelClass)
) ?> View #{model.<?= $generator->getNameAttribute() ?>} ') }}

{#
$this->params['breadcrumbs'][] = ['label' => '<?=
Inflector::pluralize(
    Inflector::camel2words(StringHelper::basename($generator->modelClass))
) ?>', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model-><?=$generator->getNameAttribute() ?>, 'url' => ['view', <?= $urlParams ?>]];
$this->params['breadcrumbs'][] = 'View'; #}

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass), '-', true) ?>-view">

    <p class='pull-left'>
        {{ html.a('<span class="glyphicon glyphicon-pencil"></span> Edit', url('update' , {<?= repTwigViewUrlParams($urlParams) ?>}), {'class' : 'btn btn-info'} ) | raw }}

        {{ html.a('<span class="glyphicon glyphicon-plus"></span> New <?=
        Inflector::camel2words(
            StringHelper::basename($generator->modelClass)
        ) ?>', ['create'], {'class' : 'btn btn-success'} ) | raw }}
    </p>

    <p class='pull-right'>
        {{ html.a('<span class="glyphicon glyphicon-list"></span> List', ['index'], {'class' : 'btn btn-default'} ) | raw }}
    </p><div class='clearfix'></div>

    <?php $label = StringHelper::basename($generator->modelClass); ?>

    <h3>
        <?= "{{ model." . $generator->getModelNameAttribute($generator->modelClass) . " }}" ?>
    </h3>

    {{ this.block_begin('<?= $generator->modelClass  ?>') }}

    {{ detail_view_widget({
        'model': model,
        'attributes': [
    <?php
    foreach ($generator->getTableSchema()->columns as $column) {
        $format = $generator->attributeFormat($column);
        if ($format === false) {
            continue;
        } else {
            echo $format . ",\n";
        }
    }
    ?>
    ],
    }) }}

    <hr/>

    {{ html.a('<span class="glyphicon glyphicon-trash"></span> Delete', {'delete': '', <?= repTwigViewUrlParams( $urlParams) ?>}, {
    'class': 'btn btn-danger',
    'data-confirm': Yii.t('app', 'Are you sure to delete this item?'),
    'data-method': 'post',
    } ) | raw }}

    {{ this.block_end() }}


    <?php
    $items = <<<EOS
    {
        'label'  : '<span class="glyphicon glyphicon-asterisk"></span> label',
        'content': attribute(this.blocks, '{$generator->modelClass}'),
        'active' : true,
    },
EOS;

    foreach ($generator->getModelRelations($generator->modelClass, ['has_many']) as $name => $relation) {

        echo "{{ this.beginBlock('$name') }}\n";

        // get relation info $ prepare add button
        $model          = new $generator->modelClass;
        $showAllRecords = false;

        if ($relation->via !== null) {
            $pivotName     = Inflector::pluralize($generator->getModelByTableName($relation->via->from[0]));
            $pivotRelation = $model->{'get' . $pivotName}();
            $pivotPk       = key($pivotRelation->link);

            $addButton = "  <?= {{html.a(
            '<span class=\"glyphicon glyphicon-link\"></span> Attach " .
                Inflector::singularize(Inflector::camel2words($name)) .
                "', ['" . $generator->createRelationRoute($pivotRelation, 'create') . "', '" .
                Inflector::singularize($pivotName) . "':{'" . key(
                    $pivotRelation->link
                ) . "':\$model->{$model->primaryKey()[0]} } },
            {'class': 'btn btn-info btn-xs'}
        ) }} \n";
        } else {
            $addButton = '';
        }

        // relation list, add, create buttons
        echo "<p class='pull-right'>\n";

        echo "  <?= {{html.a(
            '<span class=\"glyphicon glyphicon-list\"></span> List All " .
            Inflector::camel2words($name) . "',
            {'" . $generator->createRelationRoute($relation, 'index') . "'},
            {'class': 'btn btn-muted btn-xs'}
        ) }} \n";
        // TODO: support multiple PKs, VarDumper?
        echo "  <?= {{html.a(
            '<span class=\"glyphicon glyphicon-plus\"></span> New " .
            Inflector::singularize(Inflector::camel2words($name)) . "',
            {'" . $generator->createRelationRoute($relation, 'create') . "', '" .
            Inflector::singularize($name) . "': {'" . key($relation->link) . "':\$model->" . $model->primaryKey()[0] . "} },
            {'class': 'btn btn-success btn-xs'}
        ) }} \n";
        echo $addButton;

        echo "</p><div class='clearfix'></div>\n";

        // render pivot grid
        if ($relation->via !== null) {
            $pjaxId       = "pjax-{$pivotName}";
            $gridRelation = $pivotRelation;
            $gridName     = $pivotName;
        } else {
            $pjaxId       = "pjax-{$name}";
            $gridRelation = $relation;
            $gridName     = $name;
        }

        $output = $generator->relationGrid([$gridName, $gridRelation, $showAllRecords]);

        // render relation grid
        if (!empty($output)):
            echo "{{ Pjax_begin({'id':'pjax-{$name}','linkSelector':'#pjax-{$name} ul.pagination a'}) }}\n";
            echo "{{ " . $output . "}}\n";
            echo "{{ Pjax_begin() }}\n";
        endif;

        echo "{{this.endBlock() }}\n\n";

        // build tab items
        $label = Inflector::camel2words($name);
        $items .= <<<EOS
{
    'label'   : '<small><span class="glyphicon glyphicon-paperclip"></span> label</small>',
    'content' : attribute(this.blocks, 'name'),
    'active'  : false,
},
EOS;
    }
    ?>

    {{ tabs_begin({
        'id' : 'relation-tabs',
        'encodeLabels' : false,
        'items' : [ <?= $items ?> ]
    }) }}
</div>
