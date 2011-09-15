<?php

/**
 * ELocaltime class file.
 *
 * @author Jacob Moen <jacmoe@mail.dk>
 * @license MIT
 */
Yii::setPathOfAlias('ELocaltime', dirname(__FILE__));

class ELocaltime extends CWidget {

    public $localtime = "MMM dd, yyyy HH:mm";
    public $alttime = "MMM dd, yyyy";
    
    /**
     * Initializes the widget.
     * This method registers all needed client scripts 
     */
    public function init() {
        parent::init();

        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'localtime';
        $baseUrl = CHtml::asset($dir);
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');

        $cs->registerScriptFile($baseUrl . '/jquery.timeago.js');
        //$cs->registerScriptFile($baseUrl . '/humane.js');
        $cs->registerScriptFile($baseUrl . '/jquery.localtime-0.5.js');

        $options = $this->getClientOptions();
        CVarDumper::dumpAsString($options, 10, true);
        $options = $options === array() ? '' : CJavaScript::encode($options);

        $cs->registerScript(__CLASS__, "$.localtime.setFormat({$options});", CClientScript::POS_HEAD);
        
        $script = <<<EOD
	var format;
	var localise = function () {
            var theTime = this.title;
            this.t = this.title;
            this.title = jQuery.localtime.toLocalTime(theTime, format);	
            jQuery(this).text(jQuery.timeago(jQuery(this).text()));
            //jQuery(this).text(humaneDate(jQuery(this).text()));
	};
	var formats = jQuery.localtime.getFormat();
	var cssClass;
	for (cssClass in formats) {
		if (formats.hasOwnProperty(cssClass)) {
			format = formats[cssClass];
			jQuery("acronym." + cssClass).each(localise);

		}
	}
        $('pre').each(function(i, e) {hljs.highlightBlock(e, '    ')});
EOD;
        
        $cs->registerScript(__CLASS__, $script, CClientScript::POS_READY);

    }

    /**
     * @return array the javascript options
     */
    protected function getClientOptions() {
        $options = array();
        foreach (array('localtime', 'alttime') as $name) {
            if ($this->$name !== null)
                $options[$name] = $this->$name;
        }
        return $options;
    }

}
