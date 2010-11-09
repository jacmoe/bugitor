<?php
/**
 * CFilterLink class file.
 *
 * @author Seb <serebrov@algo-rithm.com>, Algo-rithm
 *
 * @version 0.3
 *
 * @desc CFilterLink is a link to filter data.
 */
class CFilterLink extends CFilterBase
{
    // link text
    private $_label;
    // filter value
    private $_linkValue;
    // is default link (highlighted if no other filters active)
    private $_isDefault;

    /**     
     * @param <string> $filterName - filter's name
     * @param <mixed> $filterGroup - filter's group
     * @param <string> $label - link text
     * @param <string> $linkValue - filter value
     * @param <boolean> $isDefault - is default link (highlighted if no other filters active)
     */
    public function __construct($filterName, $label, $linkValue, $isDefault = false)
    {
        parent::__construct($filterName);

        $this->_label = $label;
        $this->_linkValue = $linkValue;
        $this->_isDefault = $isDefault;
    }

    public function getLabel()
    {
        return $this->_label;
    }

    public function getLinkValue()
    {
        return $this->_linkValue;
    }

    public function getIsDefault()
    {
        // is default link (highlighted if no other filters active)
        return $this->_isDefault;
    }

    public function getFormName()
    {
        return $this->name. '_' . $this->linkValue;
    }

    /**
     *
     * @return <string> Filter's value
     */
    public function getValue()
    {
        if (Yii::app()->request->getParam('resetDataFilter')) {
            return null;
        }
        // value link $_POST['UserFilter']['City']
        return $value = Yii::app()->request->getParam($this->name. '_' . $this->linkValue);        
    }

    /**
     * Apply filter's value to criteria. Method call redirected to model's
     * method applyDataFilterCriteria()
     * @param <CActiveRecord> $model
     * @param <CDbCriteria> $criteria
     */
    public function applyCriteria($model, &$criteria)
    {           
        $model->applyDataFilterCriteria($criteria,
            $this->name, $this->getValue()
        );
    }

    /**
     * Returns filter html code
     * @param <CDataFilterWidget> $widget
     * @return <string>
     */
    public function buildFilterCode($widget)
    {
        return $widget->render('link', array('filter'=>$this), true);  
    }

}
?>