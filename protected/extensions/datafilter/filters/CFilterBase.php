<?php
/**
 * CFilterBase class file.
 *
 * @author Seb <serebrov@algo-rithm.com>, Algo-rithm
 *
 * @version 0.3
 *
 * @desc CFilterBase is a base class for different filter types.
 */
class CFilterBase extends CComponent
{
    /**     
     * @var <string> filter name
     */
    private $_name;
    protected $_filtersName;

    /**     
     * @param <string> $filterName - filter name
     * @param <mixed> $group - filter's group name or CFilterGroup object
     */
    public function __construct($filterName)
    {
        $this->_name = $filterName;
    }    

    /**
     *
     * @return <string> filter name
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     *
     * @return <string> filter name for form
     */
    public function getFormName()
    {
        return $this->_filtersName . '[' . $this->_name . ']';
    }

    /**
     * Applys filter value to criteria. Should be implemented in subclasses.
     * @param <CDbCriteria> $criteria
     * @return <type>
     */
    public function applyCriteria($model, &$criteria)
    {
        return;
    }

    /**
     * Builds filter's html code. Should be implemented in subclasses.
     * @param <CDataFilterWidget> $widget - filter widget
     * @return <string> filter's html code
     */
    public function buildFilterCode($widget)
    {
        return '';
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
        $value = Yii::app()->request->getParam($this->_filtersName);
        if (isset($value[$this->name])) {
            return $value[$this->name];
        } else {
            return null;
        }
    }

    /**
     *
     * @return <boolean> is filter has not empty value
     */
    public function hasValue()
    {
        $value = $this->getValue();
        return isset($value) && !CFilterBase::isEmptyVal($value);
    }

    /**
     * Method invoked by CDataFilter class when filter is added
     * @param <CDataFilter> $dataFilter
     * @return <void>
     */
    public function notifyAdded($dataFilter)
    {
        $this->_filtersName = $dataFilter->getName();
        return;
    }

    /**
     * Checks is value empty. Zero values (0 and '0') are not treated as empty
     * @param <value> $string - value to check
     * @return <boolean> is value empty
     */
    public static function isEmptyVal($string)
    {
        $string = trim($string);
        if(!is_numeric($string)) {
            // empty() returns true for '0' (string) and 0 (int)
            return empty($string);
        }
        return false;
    }   

}
?>