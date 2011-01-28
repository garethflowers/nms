<?php
class StaffContact {

    public $id = 0;

    public $staff_id = null;
    public $name = null;
    public $telephone = null;
    public $address = null;
    public $city = null;
    public $county = null;
    public $postcode = null;
    public $mobile = null;
    public $relationship = null;

    public function __construct($id = 0) {
        if (!empty($id) && is_numeric($id) && intval($id)!=0) {
            $this->Load(intval($id));
        }
    }

    public function Update($values) {
        if (array_key_exists('id', $values) && is_numeric($values['id'])) {
            $this->id = intval($values['id']);
        }
        if (array_key_exists('staff_id', $values) && is_numeric($values['staff_id'])) {
            $this->staff_id = intval($values['staff_id']);
        }
        if (array_key_exists('name', $values)) {
            $this->name = (string)$values['name'];
        }
        if (array_key_exists('telephone', $values)) {
            $this->telephone = (string)$values['telephone'];
        }
        if (array_key_exists('address', $values)) {
            $this->address = (string)$values['address'];
        }
        if (array_key_exists('city', $values)) {
            $this->city = (string)$values['city'];
        }
        if (array_key_exists('county', $values)) {
            $this->county = (string)$values['county'];
        }
        if (array_key_exists('postcode', $values)) {
            $this->postcode = (string)$values['postcode'];
        }
        if (array_key_exists('mobile', $values)) {
            $this->mobile = (string)$values['mobile'];
        }
        if (array_key_exists('relationship', $values) && is_numeric($values['relationship'])) {
            $this->relationship = intval($values['relationship']);
        }
    }

    public function Validate() {
        $result = array();

        return $result;
    }

    public function Save() {
        $result = $this->Validate();

        if (count($result) == 0) {
            if ($this->id > 0) {
                $query = 'update staff_contact';
                $query .= ' set name=' . Db::SqlFormat($this->name,'string');
                $query .= ',telephone=' . Db::SqlFormat($this->telephone,'string');
                $query .= ',address=' . Db::SqlFormat($this->address,'string');
                $query .= ',city=' . Db::SqlFormat($this->city,'string');
                $query .= ',county=' . Db::SqlFormat($this->county,'string');
                $query .= ',postcode=' . Db::SqlFormat($this->postcode,'string');
                $query .= ',mobile=' . Db::SqlFormat($this->mobile,'string');
                $query .= ',relationship=' . Db::SqlFormat($this->relationship,'int');
                $query .= ' where id=' . Db::SqlFormat($this->id,'int');
                $query .= ';';
                Db::ExecuteQuery($query);
            } else {
                $query = 'insert into staff_contact (staff_id,"name",telephone,address,city,county,postcode,mobile,relationship)';
                $query .= ' values (' . Db::SqlFormat($this->staff_id,'int');
                $query .= ',' . Db::SqlFormat($this->name,'string');
                $query .= ',' . Db::SqlFormat($this->telephone,'string');
                $query .= ',' . Db::SqlFormat($this->address,'string');
                $query .= ',' . Db::SqlFormat($this->city,'string');
                $query .= ',' . Db::SqlFormat($this->county,'string');
                $query .= ',' . Db::SqlFormat($this->postcode,'string');
                $query .= ',' . Db::SqlFormat($this->mobile,'string');
                $query .= ',' . Db::SqlFormat($this->relationship,'int');
                $query .= ');';
                $query .= 'select currval(\'staff_contact_id_seq\') as currval;';
                $data = Db::GetData($query,false);
                $this->id = intval($data['currval']);
            }
        }

        return $result;
    }

    public function Load($id) {
        if (is_numeric($id)) {
            $query = 'select id,staff_id,"name",telephone,address,city,county,postcode,mobile,relationship';
            $query .= ' from staff_contact';
            $query .= ' where id=' . Db::SqlFormat($id,'int');
            $query .= ' limit 1;';
            $data = Db::GetData($query);
            if (count($data)>0) {
                $this->Update($data);
            }
        }
    }

    public function Delete() {
        $query = 'delete from staff_contact';
        $query .= ' where id=%s;';
        $query = sprintf($query, $this->id);
        Db::ExecuteQuery($query);
    }
}
?>