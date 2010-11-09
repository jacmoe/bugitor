<?php
/**
 * CFilterGroup class file.
 *
 * @author Seb <serebrov@algo-rithm.com>, Algo-rithm
 *
 * @version 0.3
 *
 * @desc CFilterGroup represents information relevant to group of filters.
 */

class CFilterGroup extends CComponent
{
    /**     
     * @var <string> group name
     */
    private $_name;

    /**
     * @var <string>  group display name (equals to group name by default)
     */
    private $_displayName;

    /**
     * @var <string> css class to apply to group
     */
    private $_cssClass;

    /**     
     * @var <string> path to view file
     */
    private $_view;

    /**
     * @var <string> separator between filters belongs to this group
     */
    private $_fieldsSeparator;

    /**
     * @var <array> filters added to group
     */
    private $_filters = array();

    const DEFAULT_CSS_STYLE = 'dataFilterInline';
    const DEFAULT_VIEW = 'dataFilterGroup';

    /**     
     * @param <string> $name group name
     * @param <array> $options array of options:
     *      'displayName' => display name (field set label), by default the same as name
     *      'cssClass' => css class name, by default 'dataFilterInline'
     *      'view' => view name, by default 'dataFilterGroup'
     *      'fieldsSeparator' => separator between filters in the group, by default empty
     */
    public function __construct($name, $options = array())
    {
        $this->_name = $name;
        $this->_displayName = isset($options['displayName']) ? $options['displayName'] : $name;
        $this->_cssClass = isset($options['cssClass']) ? $options['cssClass'] : self::DEFAULT_CSS_STYLE;
        $this->_view = isset($options['view']) ? $options['view'] : self::DEFAULT_VIEW;
        $this->_fieldsSeparator = isset($options['fieldsSeparator']) ? $options['fieldsSeparator'] : '';
    }    

    /**
     * @param <CFilterBase> $filter - filter object
     */
    public function addFilter($filter, $filterGroup = 'Filter')
    {
        $this->_filters[] = $filter;
    }

    public function getName() {
        return $this->_name;
    }

    public function getDisplayName() {
        return $this->_displayName;
    }

    public function getCssClass() {
        return $this->_cssClass;
    }

    public function getView() {
        return $this->_view;
    }

    public function getFieldsSeparator() {
        return $this->_fieldsSeparator;
    }

    public function getFilters() {
        return $this->_filters;
    }

}
?>