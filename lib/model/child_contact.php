<?php

class ChildContact {

    public $id = 0;
    public $child_id = null;
    public $name = null;
    public $occupation = null;
    public $place_work = null;
    public $work_telephone = null;
    public $mobile = null;
    public $address = null;
    public $city = null;
    public $county = null;
    public $postcode = null;
    public $telephone = null;
    public $type = null;
    public $relationship = null;

    public function __construct($id = 0) {
        if (!empty($id) && is_numeric($id) && intval($id) != 0) {
            $this->Load(intval($id));
        }
    }

    public function Update($values) {
        if (array_key_exists('id', $values) && is_numeric($values['id'])) {
            $this->id = intval($values['id']);
        }
        if (array_key_exists('child_id', $values) && is_numeric($values['child_id'])) {
            $this->child_id = intval($values['child_id']);
        }
        if (array_key_exists('name', $values)) {
            $this->name = (string) $values['name'];
        }
        if (array_key_exists('occupation', $values)) {
            $this->occupation = (string) $values['occupation'];
        }
        if (array_key_exists('place_work', $values)) {
            $this->place_work = (string) $values['place_work'];
        }
        if (array_key_exists('work_telephone', $values)) {
            $this->work_telephone = (string) $values['work_telephone'];
        }
        if (array_key_exists('mobile', $values)) {
            $this->mobile = (string) $values['mobile'];
        }
        if (array_key_exists('address', $values)) {
            $this->address = (string) $values['address'];
        }
        if (array_key_exists('city', $values)) {
            $this->city = (string) $values['city'];
        }
        if (array_key_exists('county', $values)) {
            $this->county = (string) $values['county'];
        }
        if (array_key_exists('postcode', $values)) {
            $this->postcode = (string) strtoupper($values['postcode']);
        }
        if (array_key_exists('telephone', $values)) {
            $this->telephone = (string) $values['telephone'];
        }
        if (array_key_exists('relationship', $values) && is_numeric($values['relationship'])) {
            $this->relationship = intval($values['relationship']);
        }
        if (array_key_exists('type', $values)) {
            $this->type = (string) $values['type'];
        }
    }

    public function Validate() {
        $result = array();

        if (empty($this->child_id)) {
            $result[] = 'child_id';
        }
        if (empty($this->name)) {
            $result[] = 'name';
        }
        if (empty($this->type)) {
            $result[] = 'type';
        }

        return $result;
    }

    public function Save() {
        $result = $this->Validate();

        if (count($result) == 0) {
            if ($this->id > 0) {
                $query = 'update child_contact';
                $query .= ' set name=' . Db::SqlFormat($this->name, 'string');
                $query .= ',occupation=' . Db::SqlFormat($this->occupation, 'string');
                $query .= ',place_work=' . Db::SqlFormat($this->place_work, 'string');
                $query .= ',work_telephone=' . Db::SqlFormat($this->work_telephone, 'string');
                $query .= ',mobile=' . Db::SqlFormat($this->mobile, 'string');
                $query .= ',address=' . Db::SqlFormat($this->address, 'string');
                $query .= ',city=' . Db::SqlFormat($this->city, 'string');
                $query .= ',county=' . Db::SqlFormat($this->county, 'string');
                $query .= ',postcode=' . Db::SqlFormat($this->postcode, 'string');
                $query .= ',telephone=' . Db::SqlFormat($this->telephone, 'string');
                $query .= ',type=' . Db::SqlFormat($this->type, 'string');
                $query .= ',relationship=' . Db::SqlFormat($this->relationship, 'int');
                $query .= ' where id=' . Db::SqlFormat($this->id, 'int');
                $query .= ';';
                Db::ExecuteQuery($query);
            } else {
                $query = 'insert into child_contact (child_id,"name",occupation,place_work,work_telephone,mobile,address,city,county,postcode,telephone,"type",relationship)';
                $query .= ' values (' . Db::SqlFormat($this->child_id, 'int');
                $query .= ',' . Db::SqlFormat($this->name, 'string');
                $query .= ',' . Db::SqlFormat($this->occupation, 'string');
                $query .= ',' . Db::SqlFormat($this->place_work, 'string');
                $query .= ',' . Db::SqlFormat($this->work_telephone, 'string');
                $query .= ',' . Db::SqlFormat($this->mobile, 'string');
                $query .= ',' . Db::SqlFormat($this->address, 'string');
                $query .= ',' . Db::SqlFormat($this->city, 'string');
                $query .= ',' . Db::SqlFormat($this->county, 'string');
                $query .= ',' . Db::SqlFormat($this->postcode, 'string');
                $query .= ',' . Db::SqlFormat($this->telephone, 'string');
                $query .= ',' . Db::SqlFormat($this->type, 'string');
                $query .= ',' . Db::SqlFormat($this->relationship, 'int');
                $query .= ');';
                $query .= 'select currval(\'child_contact_id_seq\') as currval;';
                $data = Db::GetData($query, false);
                $this->id = intval($data['currval']);
            }
        }

        return $result;
    }

    public function Load($id) {
        if (is_numeric($id)) {
            $query = 'select id,child_id,"name",occupation,place_work,work_telephone,mobile,address,city,county,postcode,telephone,"type",relationship';
            $query .= ' from child_contact';
            $query .= ' where id=' . Db::SqlFormat($id, 'int');
            $query .= ' limit 1;';
            $data = Db::GetData($query);
            if (count($data) > 0) {
                $this->Update($data);
            }
        }
    }

    public function Delete() {
        $query = 'delete from child_contact';
        $query .= ' where id=%s;';
        $query = sprintf($query, $this->id);
        Db::ExecuteQuery($query);
    }

}

?>