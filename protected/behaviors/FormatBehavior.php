<?php
/*
 * This file is part of
 *     ____              _ __
 *    / __ )__  ______ _(_) /_____  _____
 *   / __  / / / / __ `/ / __/ __ \/ ___/
 *  / /_/ / /_/ / /_/ / / /_/ /_/ / /
 * /_____/\__,_/\__, /_/\__/\____/_/
 *             /____/
 * A Yii powered issue tracker
 * http://bitbucket.org/jacmoe/bugitor/
 *
 * Copyright (C) 2009 - 2010 Bugitor Team
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation files
 * (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
?>
<?php
class FormatBehavior extends CActiveRecordBehavior {

    public $collumns = array();
    public $valueOnEnter;
    public $valueOnExit;
    public $typeFormater;
    public $useTimeStamp = false;
    public $autoTimeStamp = false;
    public $created = 'created';
    public $modified = 'modified';

    public function beforeValidate($on) {
        if($this->autoTimeStamp):
            if ($this->Owner->isNewRecord) {
                if($this->created!==null) $this->Owner->{$this->created} = $this->getNow();
                if($this->modified!==null) $this->Owner->{$this->modified} = $this->getNow();
            }
            else {
                if($this->created!==null) $this->Owner->{$this->created} = $this->datetimeOnEnter($this->Owner->{$this->created});
                if($this->modified!==null) $this->Owner->{$this->modified} = $this->getNow();
        }
        else:
            foreach($this->collumns as $collum):
                $this->Owner->{$collum} = $this->{"{$this->typeFormater}onEnter"}($this->Owner->{$collum});
            endforeach;
        endif;
        return true;
    }

    public function afterFind($on) {
        if($this->autoTimeStamp) $this->collumns = array_merge(array('created', 'modified'), $this->collumns);
        foreach($this->collumns as $collum):
            $this->Owner->{$collum} = $this->{"{$this->typeFormater}onExit"}($this->Owner->{$collum});
        endforeach;
        return true;
    }

    public function datasOnEnter($data) {
        if(empty ($data)) return false;
        if($this->useTimeStamp) {
            return strtotime(preg_replace('/^(\d{2})\/(\d{2})\/(\d{4})$/', '${3}-${2}-${1}', $data));
        }
        else {
            return preg_replace('/^(\d{2})\/(\d{2})\/(\d{4})$/', '${3}-${2}-${1}', $data);
        }
    }

    public function datasOnExit($data) {
        if(empty ($data)) return false;
        if($this->useTimeStamp) {
            return date('d/m/Y', ($data));
        }
        else {
            return date('d/m/Y', strtotime($data));
        }
    }

    public function datetimeOnEnter($data) {
        if(empty ($data)) return false;
        if($this->useTimeStamp) {
            return strtotime(preg_replace('/^(\d{2})\/(\d{2})\/(\d{4}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/', '${3}-${2}-${1} ${4}:${5}:${6}', $data));
        }
        else {
            return preg_replace('/^(\d{2})\/(\d{2})\/(\d{4}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/', '${3}-${2}-${1} ${4}:${5}:${6}', $data);
        }
    }

    public function datetimeOnExit($data) {
        if(empty ($data)) return false;
        if($this->useTimeStamp) {
            return date('d/m/Y H:i:s', ($data));
        }
        else {
            return date('d/m/Y H:i:s', strtotime($data));
        }
    }

    public function moneyOnEnter ($money) {
        return preg_replace('/^R[$] (\d+),(\d{1,2})$/', '${1}.${2}', $money);
    }

    public function moneyOnExit ($money ) {
        //return preg_replace('/^(\d+)[\.]?(\d{1,2})?$/', 'R$ ${1},${2}', $money);
        return 'R$ '.number_format($money, 2, ',', '');
    }

    public function cpfOnEnter ($cpf) {
        return preg_replace('/^(\d{3})\.(\d{3})\.(\d{3})-(\d{2})$/', '${1}${2}${3}${4}', $cpf);
    }

    public function cnpjOnEnter ($cnpj) {
        return preg_replace('/^(\d{2})\.(\d{3})\.(\d{3})\/(\d{4})-(\d{2})$/', '${1}${2}${3}${4}${5}', $cnpj);
    }

    public function cpfOnExit ($cpf) {
        return preg_replace('/^(\d{3})(\d{3})(\d{3})(\d{2})$/', '${1}.${2}.${3}-${4}', $cpf);
    }

    public function cnpjOnExit ($cnpj) {
        return preg_replace('/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/', '${1}.${2}.${3}/${4}-${5}', $cnpj);
    }

    public static function getHora($data) {
        if(preg_match('/[:]/', $data)) return preg_replace('/^(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2}):(\d{2})$/', '${4}', $data);
        return date('H', strtotime($data));
    }

    public static function getMinuto($data) {
        if(preg_match('/[:]/', $data)) return preg_replace('/^(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2}):(\d{2})$/', '${5}', $data);
        return date('i', strtotime($data));
    }

    public static function getTime($data) {
        if(preg_match('/[:]/', $data)) return preg_replace('/^(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2}):(\d{2})$/', '${4}:${5}:${6}', $data);
        return date('H:i:s', strtotime($data));
    }

    public static function getDate($data) {
        if(preg_match('/[/]/', $data)) return preg_replace('/^(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2}):(\d{2})$/', '${1}/${2}/${3}', $data);
        if(preg_match('/[-]/', $data)) return preg_replace('/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/', '${3}/${2}/${1}', $data);
        return date('d/m/Y', strtotime($data));
    }


    public function getNow() {
        if($this->useTimeStamp):
            return strtotime('now');
        else:
            return date('Y-m-d H:i:s', strtotime('now'));
        endif;
    }

}
?>