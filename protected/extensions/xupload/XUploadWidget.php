<?php
Yii::import('zii.widgets.jui.CJuiInputWidget');
/**
 * XUpload
 * @author Tudor Ilisoi
 * @author AsgarothBelem <asgaroth[dot]belem[at]gmail[dot]com>
 * @link http://aquantum-demo.appspot.com/file-upload
 * @see https://github.com/blueimp/jQuery-File-Upload
 * @version 0.1
 *
 */
class XUploadWidget extends CJuiInputWidget {

	/**
	 * the url to the upload handler
	 * @var string
	 */
	public $url;

	public function init() {
		parent::init();
		$this->publishAssets();
	}

	public function run() {

		list($name,$id)=$this->resolveNameID();

		$model = $this->model;


		if( !isset($this->options['uploadTable']) ){
			$uploadTable = "files";
			$this->options['uploadTable'] = "#files";
		}else{
			$uploadTable = $this->options['uploadTable'];
			$this->options['uploadTable'] = "#{$uploadTable}";
		}


		if( !isset($this->options['downloadTable']) ){
			$downloadTable = "files";
			$this->options['downloadTable'] = "#files";
		}else{
			$downloadTable = $this->options['downloadTable'];
			$this->options['downloadTable'] = "#{$downloadTable}";
		}

		if( !isset($this->options['buildUploadRow']) ){
			$this->options['buildUploadRow'] = $this->_getBuildUploadRow();
		}

		if( !isset($this->options['buildDownloadRow']) ){
			$this->options['buildDownloadRow'] = $this->_getBuildDownloadRow();
		}

		if( !isset($this->htmlOptions['enctype']) ){
			$this->htmlOptions['enctype'] = 'multipart/form-data';
		}

		if( !isset($this->htmlOptions['class']) ){
			$this->htmlOptions['class'] = 'xupload-form file_upload';
		}

		if( !isset($this->htmlOptions['id']) ){
			$this->htmlOptions['id'] = get_class($model)."_form";
		}

		$options=CJavaScript::encode($this->options);
		CVarDumper::dumpAsString($options, 10, true);
		Yii::app()->clientScript->registerScript(__CLASS__.'#'.$this->htmlOptions['id'], "jQuery('#{$this->htmlOptions['id']}').fileUploadUI({$options});", CClientScript::POS_READY);


		echo CHtml::beginForm($this->url, 'post', $this->htmlOptions);
		if($this->hasModel()){
			echo CHtml::activeFileField($this->model, $this->attribute);
		}
		else{
			echo CHtml::fileField($name,$this->value);
		}
		echo CHtml::tag("button", array(), "Upload", true);
		echo CHtml::tag("div", array(), "Upload file", true);

		echo CHtml::endForm();

		if($uploadTable == $downloadTable){
			echo CHtml::tag("table", array("id" => $uploadTable), "", true);
		}else{
			echo CHtml::tag("table", array("id" => $uploadTable), "", true);
			echo CHtml::tag("table", array("id" => $downloadTable), "", true);
		}

	}

	public function publishAssets() {
		$assets = dirname(__FILE__) . '/assets';
		$baseUrl = Yii::app()->assetManager->publish($assets);
		if (is_dir($assets)) {
			Yii::app()->clientScript->registerScriptFile($baseUrl . '/fileupload-ui/jquery.fileupload.js', CClientScript::POS_END);
			Yii::app()->clientScript->registerScriptFile($baseUrl . '/fileupload-ui/jquery.fileupload-ui.js', CClientScript::POS_END);
			Yii::app()->clientScript->registerCssFile($baseUrl . '/fileupload-ui/jquery.fileupload-ui.css');
			Yii::app()->clientScript->registerCssFile($baseUrl . '/xuploads.css');
		} else {
			throw new CHttpException(500, 'XUpload - Error: Couldn\'t find assets to publish.');
		}
	}

	private function _getBuildDownloadRow(){
		$js = <<<EOD
js:function (files, index) {
	return $('<tr><td>' + files.name + '<\/td>' +
    	'<td class="file_upload_progress"><div><\/div><\/td>' +
    	'<td class="filesize">'+files.size+'</td>' +
        '<td class="file_upload_cancel">' +
        '<button class="ui-state-default ui-corner-all" title="Cancel">' +
        '<span class="ui-icon ui-icon-cancel">Cancel<\/span>' +
        '<\/button><\/td><\/tr>');
}
EOD;
		return $js;
	}

	private function _getBuildUploadRow(){
		$js = <<<EOD
js:function (file) {
	return $('<tr>'+
		'<td class="filename">'+file[0].name+'</td>'+
		'<td class="filesize">'+file[0].name+'</td>'+
		'<td class="file_upload_progress"><div></div></td>'+
		'<td class="file_upload_start" style="display:none">'+
			'<button class="ui-state-default ui-corner-all" title="Start Upload">'+
				'<span class="ui-icon ui-icon-circle-arrow-e">Start Upload</span>'+
			'</button>'+
		'</td>'+
		'<td class="file_upload_cancel">'+
			'<button class="ui-state-default ui-corner-all">'+
				'<span class="ui-icon ui-icon-cancel">Cancel</span>'+
			'</button>'+
		'</td>'+
	'</tr>');
}
EOD;
		return $js;
	}
}