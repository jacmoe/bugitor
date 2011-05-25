<?php
/**
 * JTruncate class file.
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 * @license BSD
 */

/**
 * This widget encapsulates the JCenter-jQuery plugin.
 * The plugin provides simple yet customizable truncation for text entities in your web page.
 * ({@link http://blog.jeremymartin.name/2008/02/jtruncate-text-truncation-for-jquery.html}).
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 */
Yii::setPathOfAlias('JTruncate',dirname(__FILE__));

class JTruncate extends CWidget
{
	/**
	 * @var string the name of the container element that contains the text.
     * Defaults to 'div'.
	 */
	public $tagName='div';

	/**
	 * @var integer The number of characters to display before truncating.
	 * Defaults to 300.
	 */
    public $length;
	/**
	 * @var integer The minimum number of "extra" characters required to truncate.
     * This option allows you to prevent truncation of a section of text that
     * is only a few characters longer than the specified length.
	 * Defaults to 20.
	 */
    public $minTrail;
	/**
	 * @var string The text to use for the "more" link.
	 * Defaults to "more".
	 */
    public $moreText;
	/**
	 * @var string The text to use for the "less" link.
	 * Defaults to "less".
	 */
    public $lessText;
	/**
	 * @var string The text to append to the truncated portion.
	 * Defaults to "...".
	 */
    public $ellipsisText;
	/**
	 * @var string The speed argument for the jQuery show() method ('fast' or 'slow')
	 * Defaults to an empty string.
	 */
    public $moreAni;
	/**
	 * @var string The speed argument for the jQuery hide() method ('fast' or 'slow')
	 * Defaults to an empty string.
	 */
    public $lessAni;
	/**
	 * @var array the HTML attributes that should be rendered in the HTML tag which contain the text.
	 */
	public $htmlOptions=array();



	/**
	 * Initializes the widget.
	 * This method registers all needed client scripts 
	 */
	public function init()
	{
		parent::init();

		$id=$this->getId();
		if (isset($this->htmlOptions['id']))
			$id = $this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;

      	$dir = dirname(__FILE__).DIRECTORY_SEPARATOR.'jtassets';
      	$baseUrl = CHtml::asset($dir);

  		$cs = Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');

        if (YII_DEBUG)
            $cs->registerScriptFile($baseUrl.'/js/jquery.jtruncate.js');
        else
            $cs->registerScriptFile($baseUrl.'/js/jquery.jtruncate.pack.js');

        $this->translateLabels();
		$options=$this->getClientOptions();
		$options=$options===array()?'' : CJavaScript::encode($options);

        $js = "jQuery('#{$id}').jTruncate($options);";
		$cs->registerScript(__CLASS__.'#'.$id, $js);
        echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";
	}

	/**
	 * Renders the close tag of the container.
	 */
	public function run()
	{
		echo CHtml::closeTag($this->tagName);
	}

	/**
	 * @return array the javascript options
	 */
	protected function getClientOptions()
	{
        $options = array();
		foreach(array('length','minTrail','moreText','lessText','ellipsisText','moreAni','lessAni') as $name)
		{
			if($this->$name!==null)
				$options[$name]=$this->$name;
		}
		return $options;
	}

    protected function translateLabels(){

        if($this->moreText==null)
            $this->moreText = Yii::t("JTruncate.main",'more');
        
        if($this->lessText==null)
            $this->lessText = Yii::t("JTruncate.main",'less');
    }

}
