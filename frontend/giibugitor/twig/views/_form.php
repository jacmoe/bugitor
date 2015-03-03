<?php

use yii\helpers\StringHelper;

/**
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */

/** @var \yii\db\ActiveRecord $model */
$model = new $generator->modelClass;
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->getTableSchema()->columnNames;
}

?>
{{use('yii/helpers/Html')}}
{{use('yii/bootstrap/ActiveForm')}}
{{use('yii/bootstrap/Tabs')}}

<div class="<?= \yii\helpers\Inflector::camel2id(StringHelper::basename($generator->modelClass), '-', true) ?>-form">

    {% set form = active_form_begin({
        'layout': '<?= $generator->formLayout ?>',
        'enableClientValidation': false
    }) %}

    <div class="">
        {{form.errorSummary(model) | raw}}
        {{this.block_begin('main') | raw}}

        <p>
            <?php
            function phpToTwigFormView($a){
                $a = str_replace("->", '.', $a);
                $a = str_replace("$", '', $a);
                $a = str_replace("=>", ':', $a);
                $a = str_replace("[", '{', $a);
                $a = str_replace("]", '}', $a);
                if($a){
                    $a .= '| raw';
                }
                return $a;
            }
            foreach ($safeAttributes as $attribute) {
                $column   = $generator->getTableSchema()->columns[$attribute];

                $prepend = $generator->prependActiveField($column, $model);
                $field = $generator->activeField($column, $model);
                $append = $generator->appendActiveField($column, $model);
                // this is ugly, might need a fix


                $prepend = phpToTwigFormView($prepend);
                $field = phpToTwigFormView($field);
                $append = phpToTwigFormView($append);

                if ($prepend) {
                    echo "\n\t\t\t{{ " . $prepend . " }}";
                }
                if ($field) {
                    echo "\n\t\t\t{{ " . $field . " }}";
                }
                if ($append) {
                    echo "\n\t\t\t{{ " . $append . " }}";
                }
            } ?>

        </p>
        {{this.block_end('main')}}

        <?php
        $label = substr(strrchr($model::className(), "\\"), 1);;

        $items = <<<EOS
        {
            'label'   : label,
            'content' : this.blocks['main'],
            'active'  : true,
        },
EOS;
        ?>


        {{ tabs_widget({
            'encodeLabels': false,
            'items': [<?= $items; ?>]
        }) }}
        <hr/>
        {{ html.submitButton('<span class="glyphicon glyphicon-check"></span> Save', {'class' : 'btn btn-primary'}) | raw }}

        {{ active_form_end() }}

    </div>

</div>
