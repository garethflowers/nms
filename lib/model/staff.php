<?php
class Staff {

    public $id = 0;

    public $surname = null;
    public $forename = null;
    public $address = null;
    public $city = null;
    public $county = null;
    public $postcode = null;
    public $telephone = null;
    public $mobile = null;
    public $email = null;
    public $dob = null;
    public $national_insurance = null;
    public $next_of_kin = null;
    public $doctor = null;
    public $doctor_address = null;
    public $vehicle_registration = null;
    public $bank_name = null;
    public $bank_account = null;
    public $bank_sort_code = null;
    public $username = null;
    public $password = null;
    public $userlevel = null;
    public $assigned_room = null;
    public $start = null;
    public $finish = null;
    public $notes = null;

    public function __construct($id = 0) {
        if (!empty($id) && is_numeric($id) && intval($id)!=0) {
            $this->Load(intval($id));
        }
    }

    public function Update($values) {
        if (array_key_exists('id', $values) && is_numeric($values['id'])) {
            $this->id = intval($values['id']);
        }
        if (array_key_exists('forename', $values)) {
            $this->forename = (string)$values['forename'];
        }
        if (array_key_exists('surname', $values)) {
            $this->surname = (string)$values['surname'];
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
            $this->postcode = (string)strtoupper($values['postcode']);
        }
        if (array_key_exists('telephone', $values)) {
            $this->telephone = (string)$values['telephone'];
        }
        if (array_key_exists('mobile', $values)) {
            $this->mobile = (string)$values['mobile'];
        }
        if (array_key_exists('email', $values)) {
            $this->email = (string)$values['email'];
        }
        if (array_key_exists('dob', $values) && IsDate($values['dob'])) {
            $this->dob = (string)$values['dob'];
        }
        if (array_key_exists('national_insurance', $values)) {
            $this->national_insurance = (string)$values['national_insurance'];
        }
        if (array_key_exists('next_of_kin', $values)) {
            $this->next_of_kin = (string)$values['next_of_kin'];
        }
        if (array_key_exists('vehicle_registration', $values)) {
            $this->vehicle_registration = (string)$values['vehicle_registration'];
        }
        if (array_key_exists('doctor', $values)) {
            $this->doctor = (string)$values['doctor'];
        }
        if (array_key_exists('doctor_address', $values)) {
            $this->doctor_address = (string)$values['doctor_address'];
        }
        if (array_key_exists('bank_name', $values)) {
            $this->bank_name = (string)$values['bank_name'];
        }
        if (array_key_exists('bank_account', $values)) {
            $this->bank_account = (string)$values['bank_account'];
        }
        if (array_key_exists('bank_sort_code', $values)) {
            $this->bank_sort_code = (string)$values['bank_sort_code'];
        }
        if (array_key_exists('username', $values)) {
            $this->username = (string)$values['username'];
        }
        if (array_key_exists('password', $values)) {
            $this->password = (string)$values['password'];
        }
        if (array_key_exists('userlevel', $values) && is_numeric($values['userlevel'])) {
            $this->userlevel = intval($values['userlevel']);
        }
        if (array_key_exists('assigned_room', $values) && is_numeric($values['assigned_room'])) {
            $this->assigned_room = intval($values['assigned_room']);
        }
        if (array_key_exists('start', $values) && IsDate($values['start'])) {
            $this->start = (string)$values['start'];
        }
        if (array_key_exists('finish', $values) && IsDate($values['finish'])) {
            $this->finish = (string)$values['finish'];
        }
        if (array_key_exists('notes', $values)) {
            $this->notes = (string)$values['notes'];
        }
    }

    public function Validate() {
        $result = array();

        if (empty($this->surname)) {
            $result[] = 'surname';
        }
        if (empty($this->forename)) {
            $result[] = 'forename';
        }
        if (empty($this->username)) {
            $result[] = 'username';
        }
        if (empty($this->password)) {
            $result[] = 'password';
        }
        if (empty($this->userlevel)) {
            $result[] = 'userlevel';
        }

        return $result;
    }


    public function Save() {
        $result = $this->Validate();

        if (count($result) == 0) {
            if ($this->id > 0) {
                $query = 'update staff';
                $query .= ' set surname=' . Db::SqlFormat($this->surname, 'string');
                $query .= ',forename=' . Db::SqlFormat($this->forename, 'string');
                $query .= ',address=' . Db::SqlFormat($this->address, 'string');
                $query .= ',city=' . Db::SqlFormat($this->city, 'string');
                $query .= ',county=' . Db::SqlFormat($this->county, 'string');
                $query .= ',postcode=' . Db::SqlFormat($this->postcode, 'string');
                $query .= ',telephone=' . Db::SqlFormat($this->telephone, 'string');
                $query .= ',mobile=' . Db::SqlFormat($this->mobile, 'string');
                $query .= ',email=' . Db::SqlFormat($this->email, 'string');
                $query .= ',dob=' . Db::SqlFormat($this->dob, 'date');
                $query .= ',national_insurance=' . Db::SqlFormat($this->national_insurance, 'string');
                $query .= ',next_of_kin=' . Db::SqlFormat($this->next_of_kin, 'string');
                $query .= ',doctor=' . Db::SqlFormat($this->doctor, 'string');
                $query .= ',doctor_address=' . Db::SqlFormat($this->doctor_address, 'string');
                $query .= ',vehicle_registration=' . Db::SqlFormat($this->vehicle_registration, 'string');
                $query .= ',bank_name=' . Db::SqlFormat($this->bank_name, 'string');
                $query .= ',bank_account=' . Db::SqlFormat($this->bank_account, 'string');
                $query .= ',bank_sort_code=' . Db::SqlFormat($this->bank_sort_code, 'string');
                $query .= ',username=' . Db::SqlFormat($this->username, 'string');
                $query .= ',password=' . Db::SqlFormat($this->password, 'string');
                $query .= ',userlevel=' . Db::SqlFormat($this->userlevel, 'int');
                $query .= ',assigned_room=' . Db::SqlFormat($this->assigned_room, 'int');
                $query .= ',"start"=' . Db::SqlFormat($this->start, 'date');
                $query .= ',finish=' . Db::SqlFormat($this->finish, 'date');
                $query .= ',notes=' . Db::SqlFormat($this->notes,'string');
                $query .= ' where id=' . Db::SqlFormat($this->id, 'int');
                $query .= ';';
                Db::ExecuteQuery($query);
            }
            else {
                $query = 'insert into staff (surname,forename,address,city,county,postcode,telephone,mobile,email,dob,national_insurance,next_of_kin,doctor,doctor_address,vehicle_registration,bank_name,bank_account,bank_sort_code,username,"password",userlevel,assigned_room,"start",finish,notes)';
                $query .= ' values (' . Db::SqlFormat($this->surname, 'string');
                $query .= ',' . Db::SqlFormat($this->forename, 'string');
                $query .= ',' . Db::SqlFormat($this->address, 'string');
                $query .= ',' . Db::SqlFormat($this->city, 'string');
                $query .= ',' . Db::SqlFormat($this->county, 'string');
                $query .= ',' . Db::SqlFormat($this->postcode, 'string');
                $query .= ',' . Db::SqlFormat($this->telephone, 'string');
                $query .= ',' . Db::SqlFormat($this->mobile, 'string');
                $query .= ',' . Db::SqlFormat($this->email, 'string');
                $query .= ',' . Db::SqlFormat($this->dob, 'date');
                $query .= ',' . Db::SqlFormat($this->national_insurance, 'string');
                $query .= ',' . Db::SqlFormat($this->next_of_kin, 'string');
                $query .= ',' . Db::SqlFormat($this->doctor, 'string');
                $query .= ',' . Db::SqlFormat($this->doctor_address, 'string');
                $query .= ',' . Db::SqlFormat($this->vehicle_registration, 'string');
                $query .= ',' . Db::SqlFormat($this->bank_name, 'string');
                $query .= ',' . Db::SqlFormat($this->bank_account, 'string');
                $query .= ',' . Db::SqlFormat($this->bank_sort_code, 'string');
                $query .= ',' . Db::SqlFormat($this->username, 'string');
                $query .= ',' . Db::SqlFormat($this->password, 'string');
                $query .= ',' . Db::SqlFormat($this->userlevel, 'int');
                $query .= ',' . Db::SqlFormat($this->assigned_room, 'int');
                $query .= ',' . Db::SqlFormat($this->start, 'date');
                $query .= ',' . Db::SqlFormat($this->finish, 'date');
                $query .= ',' . Db::SqlFormat($this->notes,'string');
                $query .= ');';
                $query .= 'select currval(\'staff_id_seq\') as currval;';
                $data = Db::GetData($query, false);
                $this->id = intval($data['currval']);
            }
        }

        return $result;
    }

    public function Load($id) {
        if (is_numeric($id)) {
            $query = 'select id,surname,forename,address,city,county,postcode,telephone,mobile,email,format_date(dob) as dob,national_insurance,next_of_kin,doctor,doctor_address,vehicle_registration,bank_name,bank_account,bank_sort_code,username,"password",userlevel,assigned_room,format_date("start") as start,format_date(finish) as finish,notes';
            $query .= ' from staff';
            $query .= ' where id=' . Db::SqlFormat($id, 'int');
            $query .= ' limit 1;';
            $data = Db::GetData($query);
            if (count($data)>0) {
                $this->Update($data);
            }
        }
    }

    public function Delete() {
        $query = 'delete from staff_contact';
        $query .= ' where staff_id=' . Db::SqlFormat($this->id, 'int');
        $query .= ';';
        $query .= 'delete from staff';
        $query .= ' where id=' . Db::SqlFormat($this->id, 'int');
        $query .= ';';
        Db::ExecuteQuery($query);
    }
}
?>