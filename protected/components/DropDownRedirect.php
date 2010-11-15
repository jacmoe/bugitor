<?php

/**
 * @brief This widget is a dropDown which can redirect to  an url base on selected value via javascript
 */
class DropDownRedirect extends CWidget {
	public $name; // name attribute of the dropdownlist
	
	public $select; // selected value
	
	public $data; // data of the dropdownlist
	
	public $htmlOptions = array(); // options of the dropdownlist
	
	public $url; // url with the string $replacement somewhere, wich will be replaced by the current value
	
	public $replacement = '__value__'; // will be replaced by the value
	
	public function init() {
		if (! isset($this->name))
			$this->name= $this->id;
	}
	
	public function run() {
		if (!isset($this->htmlOptions['id'])) $this->htmlOptions['id'] = $this->id;
                $script = 'window.location = "'.$this->url.'".replace("'.$this->replacement.'", $(this).val());';
                if (!isset($this->htmlOptions['onChange'])) $this->htmlOptions['onChange'] = $script;
                echo CHtml::dropDownList($this->name, $this->select, $this->data, $this->htmlOptions);
	}
}
