<?php

/**
 * EHighlight class file.
 *
 * @author Jacob Moen <jacmoe@mail.dk>
 * @license MIT
 */

class EHighlight extends CWidget {

    public $theme = 'github';
    public $tag = 'pre';
    public $tab = '    ';

    /**
     * Initializes the widget.
     * This method registers all needed client scripts 
     */
    public function init() {
        parent::init();

        $baseUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__).'/highlight');
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');

        $cs->registerScriptFile($baseUrl . '/highlight.pack.js');
        $cs->registerCssFile($baseUrl . '/styles/'. $this->theme .'.css');

        $cs->registerScript(__CLASS__.'init' , "hljs.initHighlightingOnLoad();", CClientScript::POS_HEAD);
        
        $script = <<<EOD
            try {
                $('{$this->tag}').each(function(i, e) {hljs.highlightBlock(e, '{$this->tab}')});
            }
            catch (e) {
                // handle the unsavoriness if needed
            }
EOD;
        
        $cs->registerScript(__CLASS__.'highlight' , $script, CClientScript::POS_END);
    }

}
