<?php

/**
 * Copyright (c) 2010 Kevin Bradwick <kbradwick@googlemail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 *
 * This widget for use with the Yii Framework utilises the jQuery plugin visualize
 * (http://dwpe.googlecode.com/svn/trunk/charting/index.html) to render graphs and
 * charts for your web application.
 *
 * For information on istallation and useage please visit the porjects hosting page
 * on google code: http://code.google.com/p/cvisualizewidget/
 */

class CVisualizeWidget extends CWidget
{
	/**
	 * @var string
	 */
	public $tableID = 'visualize';
	
	
	/**
	 * @var string the chart type
	 */
	public $type = 'area';
	
	
	/**
	 * @var array the data
	 */
	public $data = array();
	
	
	/**
	 * @var string
	 */
	public $cssFile = '';
	
	
	/**
	 * @var string
	 */
	public $defaultSkin = 'light';
	
	
	/**
	 * @var array
	 */
	public $options = array();
	
	
	/**
	* @var array valid chart types
	*/
	private $_validChartTypes = array('bar','line','area','pie');
	
	
	/**
	 * @var array
	 */
	private $_defaults = array(
				'width'=>400,
				'height'=>400,
				'title'=>'Statistics',
				'title'=>'',
				'colors'=>array('#be1e2d','#666699','#92d5ea','#ee8310','#8d10ee','#5a3b16','#26a4ed','#f45a90','#e9e744'),
				'textColors'=>array('#004c93'),
				'parseDirection'=>'x',
				'pieMargin'=>20,
				'pieLabelPos'=>'inside',
				'lineWeight'=>4,
				'barGroupMargin'=>10,
				'barMargin'=>1
			);

    /**
     * @var int
     */
    private static $count = 0;
	
	
	/**
	 * The initialisation method
	 */
	public function init()
	{
        // set table id based on counter
        if($this->tableID == 'visualize') {
            $this->tableID = $this->tableID.self::$count;
            self::$count++;
        }
        
		// ensure valid chart type selected
		if(!in_array($this->type, $this->_validChartTypes))
			throw new CException($this->type . ' is an invalid chart type. Valid charts are ' . implode(',',$this->_validChartTypes));
		
		// check data is present
		if(empty($this->data))
			throw new CException('Please provide some data to render a display');
		
		$this->_registerWidgetScripts();
		
		parent::init();
	}
	
	
	/**
	 * registerCoreScripts
	 */
	private function _registerWidgetScripts()
	{
		$cs=Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		
		$basePath = Yii::getPathOfAlias('application.extensions.cvisualizewidget.assets');
		$baseUrl = Yii::app()->getAssetManager()->publish($basePath);
		
		// load the css file
		if(!empty($this->cssFile)) {
			if(is_array($this->cssFile)) {
				foreach($this->cssFile as $file) {
					$cs->registerCssFile($file);
				}
			} else {
				$cs->registerCssFile($this->cssFile);
			}
		} else {
			
			$skin = in_array($this->defaultSkin,array('light','dark')) ? $this->defaultSkin : 'light';
			$cs->registerCssFile($baseUrl.'/css/visualize.css');
			$cs->registerCssFile($baseUrl.'/css/visualize-'.$skin.'.css');
			$cs->registerCssFile($baseUrl.'/css/style.css');
		}
			
		
		// load the javascripts
		$cs->registerScriptFile($baseUrl.'/excanvas.js');
		$cs->registerScriptFile($baseUrl.'/visualize.jQuery.js');
	}
	
	
	/**
	 * Render the output
	 */
	public function run()
	{
		$this->render('visualize',
			array(
				'data'=>$this->data,
				'options'=>array_merge($this->_defaults, $this->options)
			),
			false
		);
	}
	
}


?>