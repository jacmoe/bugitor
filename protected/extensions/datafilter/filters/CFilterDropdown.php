<?php
/**
 * CFilterDropdown class file.
 *
 * @author Seb <serebrov@algo-rithm.com>, Algo-rithm
 *
 * @version 0.3
 *
 * @desc CFilterDropdown is a dropdown filter class.
 */
class CFilterDropdown extends CFilterBase
{
    // filter's display name (label for dropdown list)
    private $_displayName;
    // filter option with empty value
    private $_emptyValue;
    // spectial options to add to filter's options
    private $_specialOptions;
    // if set to true filter will submit form after value changed
    private $_autoSubmit;

    /**
     * Construct CFilterDropdown object
     * @param <string> $filterName - filter's name
     * @param <mixed> $filterGroup - filter's group
     * @param <string> $filterDisplayName - label for dropdown list
     * @param <string> $emptyValue - option with empty value
     * @param <array> $specialOptions - spectial options to add to filter's options
     * @param <boolean> $autoSubmit - if set to true filter will submit form after value changed
     */

     //$filterDisplayName,
     //   $emptyValue = "All", $specialOptions = array(), $autoSubmit=true

    /**
     * Construct CFilterDropdown object
     * @param <type> $filterName - filter's name
     * @param <type> $filterGroup - filter's group
     * @param <type> $options array of options:
     *      'displayName' - label for dropdown list, by default same as filter name
     *      'emptyValue' - option with empty value, by default 'All'
     *      'specialOptions' - spectial options to add to filter's options
     *      'autoSubmit' - if set to true filter will submit form after value changed
     */
    public function __construct($filterName, $options = array())
    {
        parent::__construct($filterName);

        $this->_displayName = isset($options['displayName']) ? $options['displayName'] : $filterName;
        $this->_emptyValue = isset($options['emptyValue']) ? $options['emptyValue'] : '';
        $this->_specialOptions = isset($options['specialOptions']) ? $options['specialOptions'] : array();        
        $this->_autoSubmit = isset($options['autoSubmit']) ? $options['autoSubmit'] : true;        
    }

    public function getDisplayName()
    {
        return $this->_displayName;
    }

    public function getEmptyValue()
    {
        return $this->_emptyValue;
    }

    public function getSpecialOptions()
    {
        return $this->_specialOptions;
    }

    public function getAutoSubmit()
    {
        return $this->_autoSubmit;
    }

    public function setAutoSubmit($value)
    {
        $this->_autoSubmit = $value;
    }


    /**
     * Method invoked by CDataFilter class when filter is added
     * @param <CDataFilter> $dataFilter
     * @return <void>
     */
    public function notifyAdded($dataFilter)
    {
        if (CFilterBase::isEmptyVal($this->emptyValue)) {
            $data = $this->getDataFilterOptions($dataFilter->model);
            $data = $this->_specialOptions + $data;

            $values = array_keys($data);
            list ($value, $name) = each($data);
            // If there is no empty filter value and web page is shown first time
            // (because !isset($_GET[$filterName] = true) - we should change $_GET[$filterName] to first
            // filter value and data will be filtered correctly
            // The second check - !in_array($_GET[$filterName], $values)
            // is for case, when filter's options was changed (because of dependency on another filter)
            // then we need to rewrite $_GET[$filterName] to show correct data
            $valueGet = Yii::app()->request->getParam($this->name);
            if (!isset($valueGet) || !in_array($valueGet, $values)) {
                $_GET[$this->name] = $value;
            }
        }
        parent::notifyAdded($dataFilter);
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
        // 'submit' parameter handeled internally by javaScript causing problems
        // with ajax-enabled pages (for example page wich renders partial data grig
        // using ajax pagination - in such case yii's submit handler becomes broken)
        // $params = $this->_ajaxMode ? array() : array('submit'=>'');
        $params = ($widget->getAjaxMode() || !$this->autoSubmit) ? array() : array('onchange'=>'this.form.submit();');              
        if (!CFilterBase::isEmptyVal($this->emptyValue)) {
            $params['empty'] = $this->emptyValue;
        } 

        $data = $this->getDataFilterOptions($widget->model);
        $data = $this->_specialOptions + $data;

        return $widget->render('dropdown', array('filter'=>$this, 
            'data'=>$data, 'params'=>$params), true);
    }

    /**
     * Returns options for dropdown filter, throws an exception if model returns an empty set
     * @param <string> $filterName
     * @return <string> options for dropdown
     */
    private function getDataFilterOptions($model)
    {
        $options = $model->getDataFilterOptions($this->name);
        if (!isset($options)) {
            throw new CException('CFilterDropdown: model\'s method '.
                get_class($model) .
                '.getDataFilterOptions() returned empty set.');
        }
        return $options;
    }
    
}
?>