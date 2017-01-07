<?php

class Religion {

    public $id = 0;
    public $name = null;

    public function __construct($id = 0) {
        if (!empty($id) && is_numeric($id) && intval($id) != 0) {
            $this->Load(intval($id));
        }
    }

    public function Update($values) {
        if (array_key_exists('id', $values) && is_numeric($values['id'])) {
            $this->id = intval($values['id']);
        }
        if (array_key_exists('name', $values)) {
            $this->name = (string) $values['name'];
        }
    }

    public function Validate() {
        $result = array();

        if (empty($this->name)) {
            $result[] = 'name';
        }

        return $result;
    }

    public function Save() {
        $result = $this->Validate();

        if (count($result) == 0) {
            if ($this->id > 0) {
                $query = 'update religion';
                $query .= ' set "name"=' . Db::SqlFormat($this->name, 'string');
                $query .= ' where id=' . Db::SqlFormat($this->id, 'int');
                $query .= ';';
                Db::ExecuteQuery($query);
            } else {
                $query = 'insert into religion ("name")';
                $query .= ' values (' . Db::SqlFormat($this->name, 'string');
                $query .= ');';
                $query .= 'select currval(\'religion_id_seq\') as currval;';
                $data = Db::GetData($query, false);
                $this->id = intval($data['currval']);
            }
        }

        return $result;
    }

    public function Load($id) {
        if (is_numeric($id)) {
            $query = 'select id,"name"';
            $query .= ' from religion';
            $query .= ' where id=' . Db::SqlFormat($id, 'int');
            $query .= ' limit 1;';
            $data = Db::GetData($query);
            if (count($data) > 0) {
                $this->Update($data);
            }
        }
    }

    public function Delete() {
        $query = 'delete from religion';
        $query .= ' where id=' . Db::SqlFormat($this->id, 'int');
        Db::ExecuteQuery($query);
    }

}

?>