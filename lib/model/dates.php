<?php
class Dates {

    public $id = 0;

    public $date = null;
    public $repeat = false;

    public function __construct($id = 0) {
        if (!empty($id) && is_numeric($id) && intval($id)!=0) {
            $this->Load(intval($id));
        }
    }

    public function Update($values) {
        if (array_key_exists('id', $values) && is_numeric($values['id'])) {
            $this->id = intval($values['id']);
        }
        if (array_key_exists('date', $values) && IsDate($values['date'])) {
            $this->date = (string)$values['date'];
        }
        if (array_key_exists('repeat', $values)) {
            $this->repeat = IsBool($values['repeat']);
        }
    }

    public function Validate() {
        $result = array();

        if (!IsDate($this->date)) {
            $result[] = 'date';
        }

        return $result;
    }


    public function Save() {
        $result = $this->Validate();

        if (count($result) == 0) {
            if ($this->id > 0) {
                $query = 'update dates';
                $query .= ' set repeat=' . Db::SqlFormat($this->repeat,'bool');
                $query .= ',date=' . Db::SqlFormat($this->date,'date');
                $query .= ' where id=' . Db::SqlFormat($this->id,'int');
                $query .= ';';
                Db::ExecuteQuery($query);
            }
            else {
                $query = 'insert into dates (repeat,date)';
                $query .= ' values (' . Db::SqlFormat($this->repeat,'bool');
                $query .= ',' . Db::SqlFormat($this->date,'date');
                $query .= ');';
                $query .= 'select currval(\'dates_id_seq\') as currval;';
                $data = Db::GetData($query,false);
                $this->id = intval($data['currval']);
            }
        }

        return $result;
    }

    public function Load($id) {
        if (is_numeric($id)) {
            $query = 'select id,repeat,format_date(date) as date';
            $query .= ' from dates';
            $query .= ' where id=' . Db::SqlFormat($id,'int');
            $query .= ' limit 1;';
            $data = Db::GetData($query);
            if (count($data)>0) {
                $this->Update($data);
            }
        }
    }

    public function Delete() {
        $query = 'delete from dates';
        $query .= ' where id=' . Db::SqlFormat($this->id,'int');
        Db::ExecuteQuery($query);
    }

}
?>