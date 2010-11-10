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