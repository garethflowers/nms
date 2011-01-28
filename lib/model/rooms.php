<?php
class Rooms {

    public $id = 0;

    public $name = null;
    public $total = null;
    public $min = null;
    public $max = null;
    public $include = null;
    public $price_session = null;
    public $price_day = null;

    public function __construct($id = 0) {
        if (!empty($id) && is_numeric($id)) {
            $this->Load(intval($id));
        }
    }

    public function Update($values) {
        if (array_key_exists('id', $values) && is_numeric($values['id'])) {
            $this->id = intval($values['id']);
        }
        if (array_key_exists('name', $values)) {
            $this->name = trim($values['name']);
        }
        if (array_key_exists('total', $values) && is_numeric($values['total'])) {
            $this->total = intval($values['total']);
        }
        if (array_key_exists('min', $values) && is_numeric($values['min'])) {
            $this->min = intval($values['min']);
        }
        if (array_key_exists('max', $values) && is_numeric($values['max'])) {
            $this->max = intval($values['max']);
        }
        if (array_key_exists('include', $values)) {
            $this->include = IsBool($values['include']);
        }
        if (array_key_exists('price_session', $values) && is_numeric($values['price_session'])) {
            $this->price_session = floatval($values['price_session']);
        }
        if (array_key_exists('price_day', $values) && is_numeric($values['price_day'])) {
            $this->price_day = floatval($values['price_day']);
        }
    }

    public function Validate() {
        $result = array();

        if (empty($this->name)) {
            $result[] = 'name';
        }
        if (empty($this->total) || !is_numeric($this->total)) {
            $result[] = 'total';
        }
        if (empty($this->min) || !is_numeric($this->min)) {
            $result[] = 'min';
        }
        if (empty($this->max) || !is_numeric($this->max)) {
            $result[] = 'max';
        }
        if (!is_numeric($this->price_session)) {
            $result[] = 'price_session';
        }
        if (!is_numeric($this->price_day)) {
            $result[] = 'price_day';
        }

        return $result;
    }


    public function Save() {
        $result = $this->Validate();

        if (count($result) == 0) {
            if ($this->id > 0) {
                $query = 'update rooms';
                $query .= ' set name=' . Db::SqlFormat($this->name,'string');
                $query .= ',total=' . Db::SqlFormat($this->total,'int');
                $query .= ',min=' . Db::SqlFormat($this->min,'int');
                $query .= ',max=' . Db::SqlFormat($this->max,'int');
                $query .= ',include=' . Db::SqlFormat($this->include,'bool');
                $query .= ',price_session=' . Db::SqlFormat($this->price_session,'dec');
                $query .= ',price_day=' . Db::SqlFormat($this->price_day,'dec');
                $query .= ' where id=' . Db::SqlFormat($this->id,'int');
                $query .= ';';
                Db::ExecuteQuery($query);
            }
            else {
                $query = 'insert into rooms (name,total,min,max,include,price_session,price_day)';
                $query .= ' values (' . Db::SqlFormat($this->name,'string');
                $query .= ',' . Db::SqlFormat($this->total,'int');
                $query .= ',' . Db::SqlFormat($this->min,'int');
                $query .= ',' . Db::SqlFormat($this->max,'int');
                $query .= ',' . Db::SqlFormat($this->include,'bool');
                $query .= ',' . Db::SqlFormat($this->price_session,'dec');
                $query .= ',' . Db::SqlFormat($this->price_day,'dec');
                $query .= ');';
                $query .= 'select currval(\'rooms_id_seq\') as currval;';
                $data = Db::GetData($query,false);
                $this->id = intval($data['currval']);
            }
        }

        return $result;
    }

    public function Load($id) {
        if (is_numeric($id)) {
            $query = 'select id,name,total,min,max,include,price_session,price_day';
            $query .= ' from rooms';
            $query .= ' where id=' . Db::SqlFormat($id,'int');
            $query .= ' limit 1;';
            $data = Db::GetData($query);
            if (count($data)>0) {
                $this->Update($data);
            }
        }
    }

    public function Delete() {
        $query = 'delete from rooms';
        $query .= ' where id=' . Db::SqlFormat($this->id,'int');
        Db::ExecuteQuery($query);
    }

}
?>