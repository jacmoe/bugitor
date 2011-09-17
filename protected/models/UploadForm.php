<?php
class UploadForm extends CFormModel
{
	public $file;
	public $parent_id;
	public $mime_type;
	public $size;
	public $name;
	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
		// username and password are required
		array('file',
                        'file',
                        'types'=>'jpg, jpeg, gif, png, txt, patch, diff, bmp, log, zip, tgz, tar.bz2, bz2, tar, tar.gz, gz',
                        'maxSize'=>1024 * 1024 * 2, // 4MB
                        //'tooLarge' => 'The file cannot be bigger than 4MB',
                        //'wrongType' => 'You can only upload files of type jpg, jpeg, gif, png, txt, patch, diff, bmp, log, zip, tgz, tar.bz2, tar, tar.gz or gz',
                    ),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'attachment'=>'Add Attachment',
		);
	}

	public function getReadableFileSize($retstring = null) {
		// adapted from code at http://aidanlister.com/repos/v/function.size_readable.php
		$sizes = array('bytes', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

		if ($retstring === null) { $retstring = '%01.2f %s'; }

		$lastsizestring = end($sizes);

		foreach ($sizes as $sizestring) {
			if ($this->size < 1024) { break; }
			if ($sizestring != $lastsizestring) { $this->size /= 1024; }
		}
		if ($sizestring == $sizes[0]) { $retstring = '%01d %s'; } // Bytes aren't normally fractional
		return sprintf($retstring, $this->size, $sizestring);
	}
}
