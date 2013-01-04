<?php

class EJqueryUpload extends CInputWidget {

    /**
     * the url to the upload handler
     * @var string
     */
    public $url;
    public $id = 'fileup';
    public $result_id = 'results';

    /**
     * Publishes the required assets
     */
    public function init() {
        parent::init();
        $this->publishAssets();
    }

    /**
     * Generates the required HTML and Javascript
     */
    public function run() {

$script = <<<EOD
    $(function() {
        $('#{$this->id}').change(function() {
            var regexp = /\.(png)|(jpg)|(jpeg)|(gif)|(txt)|(patch)|(diff)|(bmp)|(log)|(zip)|(tgz)|(tar\.bz2)|(tar)|(tar\.gz)|(gz)$/i;
            if (!regexp.test($('#{$this->id}').val())) {
                alert('Only jpg, jpeg, gif, png, txt, patch, diff, bmp, log, zip, tgz, tar.bz2, bz2, tar, tar.gz and gz allowed');
                $('#{$this->id}').val('');
                return;
            }
            $(this).upload('{$this->url}', function(html) {
                $('#{$this->id}').val('');
                try{
                    var obj = jQuery.parseJSON(html);
                    if(obj.error) {
                        alert(obj.error);
                        return;
                    }
                }
                catch(e) {
                }
                $('#{$this->result_id}').append(html); 
            }, 'html');
        });
    });
EOD;
        
        Yii::app()->clientScript->registerScript(__CLASS__ . '#' . $this->id, $script, CClientScript::POS_READY);


        echo "<input id='{$this->id}' type='file' name='file' />" ;
    }

    /**
     * Publises and registers the required CSS and Javascript
     * @throws CHttpException if the assets folder was not found
     */
    public function publishAssets() {
        $assets = dirname(__FILE__) . '/jqueryupload';
        $baseUrl = Yii::app()->assetManager->publish($assets);
        if (is_dir($assets)) {
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.upload-1.0.2.js', CClientScript::POS_END);
        } else {
            throw new CHttpException(500, 'EJqueryUpload - Error: Couldn\'t find assets to publish.');
        }
    }

}
