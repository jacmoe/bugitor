<?php

namespace frontend\models;

class RegistrationForm extends \dektrium\user\models\RegistrationForm
{
    /**
     * @var string
     */
    public $reCaptcha;

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = ['reCaptcha', \himiklab\yii2\recaptcha\ReCaptchaValidator::className()];
        return $rules;
    }

}
