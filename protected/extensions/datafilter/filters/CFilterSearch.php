<?php
/**
 * CFilterSearch class file.
 *
 * @author Seb <serebrov@algo-rithm.com>, Algo-rithm
 *
 * @version 0.3
 *
 * @desc CFilterSearch is a link to filter data.
 */
class CFilterSearch extends CFilterBase
{
    /**
     * Apply filter's value to criteria. Method call redirected to model's
     * method applyDataSearchCriteria()
     * @param <CActiveRecord> $model
     * @param <CDbCriteria> $criteria
     */
    public function applyCriteria($model, &$criteria)
    {              
        $searchFields = $model->getDataFilterSearchFields($this->name);
        $fieldName = $this->getValue();

        if ( isset($searchFields[$fieldName])) {
            $model->applyDataSearchCriteria(
                    $criteria, $this->name,
                    $this->getValue(), $this->getTextFieldValue()
            );
        }
    }

    /**
     * Returns filter html code
     * @param <CDataFilterWidget> $widget
     * @return <string>
     */
    public function buildFilterCode($widget)
    {
        $data = $this->getDataFilterSearchFields($widget->model);
        return $widget->render('search', array('filter'=>$this, 'data'=>$data), true);        

    }

    /**
     * Returns seach fields list for search filter dropdown.
     * Method call redirected to model's method getDataFilterSearchFields()
     * @param <CActiveRecord> $model
     * @return <array>
     */
    private function getDataFilterSearchFields($model)
    {
        $searchFileds = $model->getDataFilterSearchFields($this->name);
        if (!isset($searchFileds)) {
            throw new CException('CFilterSearch: model\'s method '.
                get_class($model) .
                '.getDataFilterSearchFields() returned empty set.');
        }
        return $searchFileds;
    }

    /**
     * Returns true if field name selected and seach text is not empty
     * @return <boolean>
     */
    public function hasValue()
    {
        return (parent::hasValue() && $this->hasTextValue());
    }

    /**
     * Returns name for html input field
     * @return <string>
     */
    public function getTextFieldName()
    {
        return $this->name . "Text";
    }

    public function getTextFieldFormName()
    {
        //return $this->_filtersName . '[' . $this->_name . ']';
        return $this->_filtersName . '[' . $this->getTextFieldName() . ']';
    }

    /**
     * Returns value user entered to seach input field
     * @return <string>
     */
    public function getTextFieldValue()
    {
        if (Yii::app()->request->getParam('resetDataFilter')) {
            return null;
        }
        // value link $_POST['UserFilter']['SearchText']
        $value = Yii::app()->request->getParam($this->_filtersName);
        if (isset($value[$this->getTextFieldName()])) {
            return $value[$this->getTextFieldName()];
        } else {
            return null;
        }        
    }

    /**
     * Returns true if search value is not empty
     * @return <boolean>
     */
    public function hasTextValue()
    {
        $textValue = $this->getTextFieldValue();
        return (isset($textValue) && !CFilterBase::isEmptyVal($textValue));
    }

 }
?>