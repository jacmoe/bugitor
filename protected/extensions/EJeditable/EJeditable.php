<?php

/**
 * EJeditable class file.
 *
 * @author Jacob Moen <jacmoe@mail.dk>
 * @license MIT
 */

class EJeditable extends CWidget {

    public $url = '';
    public $loadurl = '';
    public $type = 'textarea';
    public $submit = 'Update';
    public $cancel = 'Cancel';
    public $tooltip = 'Click to edit...';

    /**
     * Initializes the widget.
     * This method registers all needed client scripts 
     */
    public function init() {
        parent::init();

        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'jeditable';
        $baseUrl = CHtml::asset($dir);
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($baseUrl . '/jquery.jeditable.js');

        $js = <<<EOD
     $('.edit').editable('{$this->url}', { 
         loadurl  : '{$this->loadurl}',
         type    : '{$this->type}',
         submit  : '{$this->submit}',
         cancel    : '{$this->cancel}',
        tooltip   : '{$this->tooltip}'
    });
EOD;
        
        $cs->registerScript(__CLASS__, $js, CClientScript::POS_READY);
    }

}
