<?php
/**
 * CDataFilter class file.
 *
 * @author Seb <serebrov@algo-rithm.com>, Algo-rithm
 *
 * @version 0.3
 *
 * @desc CDataFilter represents information relevant to list (table) data filtration and search.
 *
 * When the data needs to be filtered or searched by some fields, CDataFilter is used to
 * represent information about filteration and search controls
 * This information can be passed to CDataFilterWidget to render
 * filter controls.
 *
 * Note. This class has no relation to yii's CFilter class.
 *
 * Each filter is subclass of CFilterBase class
 * Now supported following filters:
 *   CFilterDropdown - dropdown list, when option is selected, data filtration
 *                     performed
 *   CFilterSearch - dropdown list with field names and a text filed to enter
 *                   search criteria, when Search button is clicked, search is
 *                   performed
 *   CFilterLink   - a link to filter data
 *
 * CDataFilter class has an array of filters.
 * CDataFilter class receives model name, model should support following methods:
 *  - getDataFilterOptions($filterName) for CFilterDropdown and CFilterLink
 *  - getDataFilterSearchFields($filterName) for CFilterSearch
 *  - applyDataFilterCriteria(&$criteria, $filterName, $filterValue) for CFilterDropdown and CFilterLink
 *  - applyDataSearchCriteria(&$criteria, $filterName, $searchField, $serachValue) for CFilterSearch
 *
 * ************************ Using ***********************************
 * *** Controller ***
 * 
    public function actionAdmin()
    {
        $this->processAdminCommand();

        $criteria=new CDbCriteria;

        //filters group for Group, City, Country, group object created explicitly
        $fromGroup = new CFilterGroup('Filter', array('cssClass'=>'dataFilterBlock'));
        $fromFilterOptions = array('emptyValue'=>'All', 'specialOptions'=>array('null'=>'None'));

        //filters group for Group and City without autosubmit, used custom view
        $filterNoAutoGroup = new CFilterGroup('Filter (no autosubmit)',
            array('view'=>'application.views.user.dataFilterGroupWithSubmit'));

        $filters = new CDataFilter(User::model());
        $filters->addFilter(new CFilterSearch('userFieldsSearch'), 'Search');
        $filters->addFilter(new CFilterDropdown('Group', $fromFilterOptions), $fromGroup);
        $filters->addFilter(new CFilterDropdown('Country', $fromFilterOptions), $fromGroup);
        $filters->addFilter(new CFilterDropdown('City', $fromFilterOptions), $fromGroup);

        $filters->addFilter(new CFilterDropdown('activeDropFilter',
            array('displayName'=>'Active')),
            //group is given as name (string), not as object
            //this filter will be joined to 'Filter' group, because group with same name already exists
            'Filter'
        );

        // two filters without autosubmit
        // submit button is placed in custom view (see views/user/dataFilterGroupWithSubmit.php)
        $filters->addFilter(new CFilterDropdown('groupFilter2',
                array('displayName'=>'Group', 'emptyValue'=>'All', 'specialOptions'=>array('null'=>'None'),
                    'autoSubmit'=>false)
            ), $filterNoAutoGroup
        );
        $filters->addFilter(new CFilterDropdown('countryFilter2',
                array('displayName'=>'Country', 'emptyValue'=>'All', 'specialOptions'=>array('null'=>'None'),
                    'autoSubmit'=>false)
            ), $filterNoAutoGroup
        );

        // two link filters, all users is a default filter (highlighted if no other filters applied)
        $filters->addFilter(new CFilterLink('activeFilter', 'All Users', 'all', true), 'Show');
        $filters->addFilter(new CFilterLink('activeFilter', 'Active Users', 'active'), 'Show');

        $filters->applyCriteria($criteria);

        $pages=new CPagination(User::model()->count($criteria));
        $pages->pageSize=self::PAGE_SIZE;
        $pages->applyLimit($criteria);

        $sort=new CSort('User');
        $sort->applyOrder($criteria);

        $models=User::model()->findAll($criteria);

        $this->render('admin',compact(
            'models', 'pages', 'sort', 'filters'
        ));
    }
 *
 * *** Model ***
 *
 *
    // Returns list of searchable fileds for DataFilter widget
    public function getDataFilterSearchFields($filterName)
    {
        switch ($filterName) {
            case 'userFieldsSearch': //filter name
                return array(
                    'df_users.id'=>'User ID', //field name => display name
                    'df_users.name'=>'Name',
                    'df_users.username'=>'Username',
                );
        }
    }

    // Applies search criteria enterd using DataFilter widget
    public function applyDataSearchCriteria(&$criteria, $filterName, $searchField, $searchValue)
    {
        if($filterName == 'userFieldsSearch') {
            $localCriteria = new CDbCriteria;
            $localCriteria->condition = ' '.$searchField.' LIKE "%'.$searchValue.'%" ';
            //$localCriteria->condition = ' '.$searchField.' LIKE "%:searchValue%" ';
            //$localCriteria->params = array(':searchValue'=>$searchValue); //"'%1%'" //".$searchValue."
            $criteria->mergeWith($localCriteria);
        }
    }

    // Returns options for DataFilter widget
    public function getDataFilterOptions($filterName)
    {
        switch ($filterName) {
            case 'Group':  //filter name
            case 'groupFilter2':
                // data from database
                $groups = Group::model()->findAll();
                return CHtml::listData($groups, 'id', 'name');
            case 'Country':
            case 'countryFilter2':
                $countries = Country::model()->findAll();
                return CHtml::listData($countries, 'id', 'name');
            case 'City':
                $criteria = new CDbCriteria;
                $country = Yii::app()->request->getParam('countryFilter');
                // city filter depends from country filter
                if (isset($country) && !empty($country)) {
                    $criteria->condition = ' countries_id = :country';
                    $criteria->params = array(':country'=>$country);
                }
                $cities = City::model()->findAll($criteria);
                return CHtml::listData($cities, 'id', 'name');
           case 'activeDropFilter':
                // static data (not from database)
                $options = array(
                    array('id'=>'', 'name'=>'All'),
                    array('id'=>0, 'name'=>'Not active'),
                    array('id'=>1, 'name'=>'Active'),
                );
                return CHtml::listData($options, 'id', 'name');
        }
    }

    // Applies filter criteria enterd using DataFilter widget
    public function applyDataFilterCriteria(&$criteria, $filterName, $filterValue)
    {
        if($filterName == 'Group' || $filterName == 'groupFilter2') {
            $localCriteria = new CDbCriteria;
            CDataFilter::setCondition('user_groups_id', $filterValue, $localCriteria);
            $criteria->mergeWith($localCriteria);
        }

        if($filterName == 'Country' || $filterName == 'countryFilter2') {
            $localCriteria = new CDbCriteria;
            //'null' value is a spectial option for coutryFilter
            if ($filterValue != 'null') {
                $localCriteria->select = 'df_users.*';
                $localCriteria->join =
                    'INNER JOIN `df_cities` cities
                    ON (`df_users`.`cities_id`=cities.`id`)
                    AND (cities.countries_id = :countryID) ';
                $localCriteria->params = array(':countryID'=>$filterValue);
                //$localCriteria->group = ' df_users.id ';
            } else {
                $localCriteria->condition = ' cities_id is null ';
            }
            $criteria->mergeWith($localCriteria);
        }

        if($filterName == 'City') {
            $localCriteria = new CDbCriteria;
            CDataFilter::setCondition('cities_id', $filterValue, $localCriteria);
            $criteria->mergeWith($localCriteria);
        }

        if($filterName == 'activeFilter') {
            if ($filterValue !== 'active') return;
            $localCriteria = new CDbCriteria;
            CDataFilter::setCondition('is_active', 1, $localCriteria);
            $criteria->mergeWith($localCriteria);
        }

        if($filterName == 'activeDropFilter') {
            $localCriteria = new CDbCriteria;
            CDataFilter::setCondition('is_active', $filterValue, $localCriteria);
            $criteria->mergeWith($localCriteria);
        }

    }
 *
 *  *** View ***
 *  <?php $this->widget('CDataFilterWidget',array('filters'=>$filters)); ?>
 *
 *  OR
 *  // enable ajax filtering / searching - '#updateData' selector will be replaced
 *  <?php $this->widget('CDataFilterWidget',array('filters'=>$filters,
 *        'ajaxMode'=>true, 'updateSelector'=>'#updateData')); ?>
 *  
 */

class CDataFilter extends CComponent
{   
    private $_filters = array();
    private $_groups = array();
    private $_model;
    private $_name;

    /**
    *  Store filters selection to session
    */
    public $storeToSession = false;
    public $sessionVariable = 'dataFilter';

    /**
     * Constructor.
     * @model to add filter to.
     */
    public function __construct($model, $name = null)
    {
        $this->_model = $model;
        $this->_name = isset($name) ? $name : get_class($model) . 'Filter';
    }

    public function getModel()
    {
        return $this->_model;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getFilters()
    {
        return $this->_filters;
    }

    public function getGroups()
    {
        return $this->_groups;
    }

    /**     
     * @param <CFilterBase> $filter - filter object
     * @param <mixed> $filterGroup - filter's group name or CFilterGroup object
     */
    public function addFilter($filter, $filterGroup = 'Filter')
    {
        $this->_filters[] = $filter;
        $group = $this->getGroup($filterGroup);
        $group->addFilter($filter);
        $filter->notifyAdded($this);
    }

    /**     
     * @param <CDbCriteria> $criteria - criteria to apply filters
     */
    public function applyCriteria(&$criteria)
    {
        if ($this->storeToSession) {
            $filterParam = Yii::app()->request->getParam($this->_name);
            if (isset($filterParam)) {
                $this->saveToSession();
            } else {
                $this->restoreFromSession();
            }
        }

        foreach ($this->_filters as $filter) {
            if ($filter->hasValue())
                $filter->applyCriteria($this->_model, $criteria);
        }
    }    

    /**
     * Returns filters count
     */
    public function getCount()
    {
        return count($this->_filters);
    }

    /**
     * @return <boolean> is any filter has a value
     */
    public function hasValues()
    {
        foreach ($this->_filters as $filter) {
            if ($filter->hasValue()) return true;
        }
        return false;

    }

    /**
     * Utility fuction. Can be used from models to apply filter's value to criteria
     * @param <string> $fieldName
     * @param <string> $fieldValue
     * @param <CDBCriteria> $criteria
     */
    public static function setCondition($fieldName, $fieldValue, &$criteria)
    {
        if (strtolower($fieldValue) === 'null') {
            $criteria->condition = ' '.$fieldName.' is null';
        } else {
            $criteria->condition = " ".$fieldName." = :$fieldName ";
            $criteria->params = array(":$fieldName"=>$fieldValue);
        }
    }

    private function getGroup($filterGroup)
    {
        $name = is_string($filterGroup) ? $filterGroup : $filterGroup->name;
        
        if (isset($this->_groups[$name])) {
            $group = $this->_groups[$name];
        } else {
            $group = is_string($filterGroup) ? new CFilterGroup($name) : $filterGroup;
            $this->_groups[$name] = $group;
        }
        return $group;
    }

    /**
     * Save filters to session
     */
    private function saveToSession()
    {
        $values = Yii::app()->request->getParam($this->_name);
        if (!empty($values)) {
            Yii::app()->user->setState($this->sessionVariable, $values);
        }
    }

    /**
     * Restore filters from session
     */
    private function restoreFromSession()
    {
        $values = Yii::app()->user->getState($this->sessionVariable);
        if (!empty($values)) {
            $_POST[$this->_name] = $values;
        }
    }

}