<?php
/**
 * CDataFilterWidget class file.
 *
 * @author Seb <serebrov@algo-rithm.com>, Algo-rithm
 *
 * @version 0.3
 *
 * @desc CDataFilterWidget renders filter controls represented by 
 * CDataFilter class.
 *
 */
class CDataFilterWidget extends CWidget
{
    /**
     * @var CDataFilter filters data
     */
    private $_filters;

    /**
     * @var boolean if set to true - ajax filtration / search will be enabled
     */
    private $_ajaxMode = false;

    /**
     * @var string selector name of html element to update by ajax request
     */
    private $_updateSelector = "#updateData";

    /**
     * @var string JS code to execute before process ajax request
     */
    private $_beforeUpdateCode = "";

    /**
     * @var mixed the CSS file used for the widget. Defaults to null, meaning
     * using the default CSS file included together with the widget.
     * If false, no CSS file will be used. Otherwise, the specified CSS file
     * will be included when using this widget.
     */
    public $cssFile;

    /**
    *  Filter's form action
    */
    public $formAction = '';
    public $formMethod = 'get';
    public $formOptions = array();

    /**
    *  Submit button options
    */
    public $renderSubmit = false;
    // disable filters autosubmit if submit button is enabled
    public $disableFiltersAutoSubmit = true;
    public $submitLabel = 'Submit';

    /**
    *  Reset button options
    */
    public $renderReset = false;
    public $resetLabel = 'Reset';

    public function init()
    {
        if($this->cssFile===null)
        {
            $file=dirname(__FILE__).DIRECTORY_SEPARATOR.'datafilter.css';
            $this->cssFile=Yii::app()->getAssetManager()->publish($file);
        }
        parent::init();
    }

    /**
     * Returns the filters information used by this widget.
     * @return CDataFilter
     */
    public function getFilters()
    {
        return $this->_filters;
    }

    /**
     * Sets the filters information used by this widget.
     * @param CDataFilter
     */
    public function setFilters($filters)
    {
        $this->_filters = $filters;
    }

    /**
     *
     * @return <boolean> is ajax mode enabled
     */
    public function getAjaxMode()
    {
        return $this->_ajaxMode;
    }

    /**
     *
     * @param <boolean> $mode - set to true to enable ajax filtration
     */
    public function setAjaxMode($mode)
    {
        $this->_ajaxMode = $mode;
    }

    /**
     * @return <string> selector name - jQuery will update element(s)
     *                  found using this selector after request finished
     */
    public function getUpdateSelector()
    {
        return $this->_updateSelector;
    }

    /**
     *
     * @param <string> $selector - set selector name
     */
    public function setUpdateSelector($selector)
    {
        $this->_updateSelector = $selector;
    }

    /**
     * @return <string> JS code to execute before process ajax request
     */
    public function getBeforeUpdateCode()
    {
        return $this->_beforeUpdateCode;
    }

    /**
     *
     * @param <string> $code - set JS code to execute before process ajax request
     */
    public function setBeforeUpdateCode($code)
    {
        $this->_beforeUpdateCode = $code;
    }

    /**
     * Executes the widget.
     * This overrides the parent implementation by displaying the generated page buttons.
     */
    public function run()
    {
        //no filters - no widget!
        if ($this->getFilters() != null) {
            $this->buildWidget();
        }
    }

    /**
     * Builds widget
     */
    private function buildWidget()
    {
        $groupsData = array();

        $searchFilters = '';
        $dropDownFilters = '';
        $linkFilters = '';

        echo CHtml::form(CHtml::normalizeUrl($this->formAction),$this->formMethod,$this->formOptions);

        foreach ($this->getFilters()->groups as $group) {
            $data = null;
            foreach ($group->filters as $filter) {
                if ($this->renderSubmit && $this->disableFiltersAutoSubmit) {
                    if (method_exists($filter, 'setAutoSubmit')) {
                        $filter->setAutoSubmit(false);
                    }
                } 

                if (!isset($data)) {
                    $data = $filter->buildFilterCode($this);
                } else {
                    $data .= $group->fieldsSeparator . $filter->buildFilterCode($this);
                }
            }
            echo $this->render($group->view, array('group'=>$group, 'data'=>$data), true);
        }

        if ($this->renderSubmit || $this->renderReset) {
            echo "<div class='action'>";
            if ($this->renderSubmit) echo CHtml::submitButton($this->submitLabel);
            if ($this->renderReset) echo CHtml::submitButton($this->resetLabel, array('name'=>'resetDataFilter'));
            echo "</div>";
        }

        echo CHtml::endForm();

        $this->registerClientScript();
    }    

    /**
     * Returns associated model
     */
    public function getModel()
    {
        return $this->getFilters()->model;
    }
   

    /**
     * Registers the needed client scripts (mainly CSS file).
     */
    public function registerClientScript()
    {
        if($this->cssFile!==false)
            self::registerCssFile($this->cssFile);
    }

    /**
     * Registers the needed CSS file.
     * @param string the CSS URL. If null, a default CSS URL will be used.     
     */
    public static function registerCssFile($url=null)
    {
        Yii::app()->getClientScript()->registerCssFile($url);
    }


}