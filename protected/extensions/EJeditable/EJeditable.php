<?php

/**
 * ETableOfContents class file.
 *
 * @author Jacob Moen <jacmoe@mail.dk>
 * @license MIT
 */
Yii::setPathOfAlias('ETableOfContents', dirname(__FILE__));

class ETableOfContents extends CWidget {

    public $scope_id = null;
    public $startLevel;
    public $depth;
    public $topLinks;
    
    public $htmlOptions=array();

    /**
     * Initializes the widget.
     * This method registers all needed client scripts 
     */
    public function init() {
        parent::init();

        $id = 'toc';//$this->getId();
        
        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $id;

        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'eassets';
        $baseUrl = CHtml::asset($dir);
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');

        if (YII_DEBUG)
            $cs->registerScriptFile($baseUrl . '/js/jquery.tableofcontents.js');
        else
            $cs->registerScriptFile($baseUrl . '/js/jquery.tableofcontents.min.js');

        $options = $this->getClientOptions();
        CVarDumper::dumpAsString($options, 10, true);
        $options = $options === array() ? '' : CJavaScript::encode($options);

        if(null !== $this->scope_id) {
            $js = <<<EOD
$(document).ready(function(){ 
  $("#toc").tableOfContents(
    $("#{$this->scope_id}"),
      {$options}
  ); 
});
EOD;
        } else {
            $js = <<<EOD
$(document).ready(function(){ 
  $("#{$id}").tableOfContents(
    null,                        // Default scoping
      {$options}
  ); 
});
EOD;
        }
        $cs->registerScript(__CLASS__ . '#' . $id, $js, CClientScript::POS_READY);
//        $cs->registerScript(__CLASS__.'#'.$id, "jQuery('#{$id}').tableOfContents(null,{$options});", CClientScript::POS_READY);
    }

    /**
     * @return array the javascript options
     */
    protected function getClientOptions() {
        $options = array();
        foreach (array('startLevel', 'depth', 'topLinks') as $name) {
            if ($this->$name !== null)
                $options[$name] = $this->$name;
        }
        return $options;
    }

}
