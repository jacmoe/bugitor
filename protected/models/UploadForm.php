<?php
class UploadForm extends CFormModel
{
  public $attachment;

  public function rules()
  {
    return array(
      array('attachment', 'file', 'types'=>'txt,log,diff,patch,bmp,zip,tar.gz,tgz,bz2,tar.bz2,jpg,jpeg,gif,png', 'maxSize'=>30720),
      array('attachment', 'required'),
    );
  }
}
