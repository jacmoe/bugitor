<?php
/**
 * RRListView class file.
 *
 * @author Rupert, Red Rabbit Studio
 * @copyright Red Rabbit Studio, 2010
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

Yii::import('zii.widgets.CListView');

/**
 * RRGridView extends {@link CListView} to allow browser history to be updated when
 * navigating through AJAX, and to automatically display the appropriate pages of
 * lists when a page is loaded with AJAX history. It also only includes Javascript
 * files if (@param enableJavascript) is TRUE (so as not to include unnecessary files).  
 *
 * All the usual {@link CListView} options are still fully functional, but there are
 * extra options.
 *
 * @author Rupert, Red Rabbit Studio, 2010-2011
 * @link http://www.rabbitstyle.com
 * @copyright Red Rabbit Studio, 2010-2011
 * @licence http://www.opensource.org/licenses/mit-license.php MIT License
 * @version $Id: RRListView.php 4 2011-06-10
 */
class RRListView extends CListView
{
	/**
	 * Whether javascript files should be registered or not.
	 * @var Boolean
	 */
	public $enableJavascript = true;
	
	/**
	 * Location alias for the extension's assets (jquery.rr.yiigridview.js and jquery.rr.hashhandling.js).
	 * @var String
	 */
	public $extensionAssets = null;
	
	/**
	 * Whether to enable the JQuery BBQ hash history behaviour, or stick to the Yii default.
	 * @var Boolean
	 */
	public $hashHistory = true;
	
	/**
	 * @var string the jQuery selector of the HTML elements that may trigger AJAX page changes when they are clicked.
	 * If not set, a default is calculated in the js file if hashHistory is enabled.
	 */
	public $updaters = array();
	
	/**
	 * Overridden so that the extension assets may be published.
	 * @see CListView::init()
	 */
	public function init() {
		parent::init();
		if ($this->hashHistory === true && $this->extensionAssets === null && $this->enableJavascript === true) {
			$this->extensionAssets = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'eassets');
		}
	}
	
	/**
	 * Registers necessary client scripts.
	 * Overridden so as to add options for hashHistory.
	 * Additions to the original 1.1.7 CGridView::registerClientScript are surrounded by ** and -- comments
	 * @see CGridView::registerClientScript()
	 */
	public function registerClientScript()
	{
		/* ********* */
		if ($this->enableJavascript) {
		/* --------- */
			$id = $this->getId();

			if ($this->ajaxUpdate === false) {
				$ajaxUpdate=array();
			}
			else {
				$ajaxUpdate=array_unique(preg_split('/\s*,\s*/',$this->ajaxUpdate.','.$id,-1,PREG_SPLIT_NO_EMPTY));
			}
			$options = array(
				'ajaxUpdate' => $ajaxUpdate,
				'ajaxVar' => $this->ajaxVar,
				'pagerClass' => $this->pagerCssClass,
				'loadingClass' => $this->loadingCssClass,
				'sorterClass' => $this->sorterCssClass,
			);
			if ($this->updateSelector !== null)
				$options['updateSelector'] = $this->updateSelector;
			if ($this->beforeAjaxUpdate !== null)
				$options['beforeAjaxUpdate'] = (strpos($this->beforeAjaxUpdate,'js:') !== 0 ? 'js:' : '').$this->beforeAjaxUpdate;
			if ($this->afterAjaxUpdate !== null)
				$options['afterAjaxUpdate'] = (strpos($this->afterAjaxUpdate,'js:') !== 0 ? 'js:' : '').$this->afterAjaxUpdate;
			
			/* ********* */
			/* add the default paging and sorting updaters
			   if hashHistory is enabled and they are also (not
			   overriding any user set parameters) */ 
			if ($this->hashHistory && $this->enablePagination) {
				if (!isset($this->updaters['page'])) {
					$this->updaters['page'] = array();
				}
				if (!isset($this->updaters['page']['getParams'])) {
					$this->updaters['page']['getParams'] = array(
						$this->dataProvider->getPagination()->pageVar,
					);
				}
			}
			if ($this->hashHistory && $this->enableSorting) {
				if (!isset($this->updaters['sort'])) {
					$this->updaters['sort'] = array();
				}
				if (!isset($this->updaters['sort']['getParams'])) {
					$this->updaters['sort']['getParams'] = array(
						$this->dataProvider->getSort()->sortVar,
					);
				}
			}
			// add the updaters to the JS options if necessary
			if ($this->hashHistory && count($this->updaters) > 0) {
				$options['updaters'] = $this->updaters;
			}
			/* --------- */
	
			$options = CJavaScript::encode($options);
			$cs = Yii::app()->getClientScript()
			->registerCoreScript('jquery')
			->registerCoreScript('bbq')
			->registerScriptFile($this->baseScriptUrl.'/jquery.yiilistview.js',CClientScript::POS_END);
			/* ********* */
			if ($this->hashHistory) {
				$cs->registerScriptFile($this->extensionAssets.'/jquery.rr.hashhandling.js', CClientScript::POS_END)
				->registerScriptFile($this->extensionAssets.'/jquery.rr.yiilistview.js', CClientScript::POS_END);
			}
			$cs
			/* --------- */
			->registerScript(__CLASS__.'#'.$id,"jQuery('#$id').yiiListView($options);");
		/* ********* */
		}
		/* --------- */
	}
}
