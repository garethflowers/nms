<?php
class Child {

    public $id = 0;
    public $age = 0;
    
    public $forename = null;
    public $surname = null;
    public $dob = null;
    public $unborn = null;
    public $address = null;
    public $city = null;
    public $county = null;
    public $postcode = null;
    public $telephone = null;
    public $next_kin = null;
    public $nationality = null;
    public $religion = null;
    public $gradual_admission = null;
    public $start = null;
    public $finish = null;
    public $keyworker = null;
    public $backup_keyworker = null;
    public $pickup_password = null;
    public $room_override = null;
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
        if (array_key_exists('dob', $values) && IsDate($values['dob'])) {
            $this->dob = (string)$values['dob'];
        }
        if (array_key_exists('unborn', $values)) {
            $this->unborn = IsBool($values['unborn']);
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
        if (array_key_exists('next_kin', $values)) {
            $this->next_kin = (string)$values['next_kin'];
        }
        if (array_key_exists('nationality', $values) && is_numeric($values['nationality'])) {
            $this->nationality = intval($values['nationality']);
        }
        if (array_key_exists('religion', $values) && is_numeric($values['religion'])) {
            $this->religion = intval($values['religion']);
        }
        if (array_key_exists('gradual_admission', $values) && IsDate($values['gradual_admission'])) {
            $this->gradual_admission = (string)$values['gradual_admission'];
        }
        if (array_key_exists('start', $values) && IsDate($values['finish'])) {
            $this->start = (string)$values['start'];
        }
        if (array_key_exists('finish', $values) && IsDate($values['finish'])) {
            $this->finish = (string)$values['finish'];
        }
        if (array_key_exists('keyworker', $values) && is_numeric($values['keyworker'])) {
            $this->keyworker = intval($values['keyworker']);
        }
        if (array_key_exists('backup_keyworker', $values) && is_numeric($values['backup_keyworker'])) {
            $this->backup_keyworker = intval($values['backup_keyworker']);
        }
        if (array_key_exists('pickup_password', $values)) {
            $this->pickup_password = (string)$values['pickup_password'];
        }
        if (array_key_exists('room_override', $values) && is_numeric($values['room_override'])) {
            $this->room_override = intval($values['room_override']);
        }
        if (array_key_exists('age', $values) && is_numeric($values['age'])) {
            $this->age = intval($values['age']);
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
        if (!IsDate($this->dob)) {
            $result[] = 'dob';
        }
        if (!IsDate($this->gradual_admission)) {
            $result[] = 'gradual_admission';
        }
        if (!IsDate($this->start)) {
            $result[] = 'start';
        }
        if (!IsDate($this->finish)) {
            $result[] = 'finish';
        }

        return $result;
    }


    public function Save() {
        $result = $this->Validate();

        if (count($result) == 0) {
            if ($this->id > 0) {
                $query = 'update child';
                $query .= ' set forename=' . Db::SqlFormat($this->forename,'string');
                $query .= ',surname=' . Db::SqlFormat($this->surname,'string');
                $query .= ',dob=' . Db::SqlFormat($this->dob,'date');
                $query .= ',unborn=' . Db::SqlFormat($this->unborn,'bool');
                $query .= ',address=' . Db::SqlFormat($this->address,'string');
                $query .= ',city=' . Db::SqlFormat($this->city,'string');
                $query .= ',county=' . Db::SqlFormat($this->county,'string');
                $query .= ',postcode=' . Db::SqlFormat($this->postcode,'string');
                $query .= ',telephone=' . Db::SqlFormat($this->telephone,'string');
                $query .= ',next_kin=' . Db::SqlFormat($this->next_kin,'string');
                $query .= ',nationality=' . Db::SqlFormat($this->nationality,'int');
                $query .= ',religion=' . Db::SqlFormat($this->religion,'int');
                $query .= ',gradual_admission=' . Db::SqlFormat($this->gradual_admission,'date');
                $query .= ',start=' . Db::SqlFormat($this->start,'date');
                $query .= ',finish=' . Db::SqlFormat($this->finish,'date');
                $query .= ',keyworker=' . Db::SqlFormat($this->keyworker,'int');
                $query .= ',backup_keyworker=' . Db::SqlFormat($this->backup_keyworker,'int');
                $query .= ',pickup_password=' . Db::SqlFormat($this->pickup_password,'string');
                $query .= ',room_override=' . Db::SqlFormat($this->room_override,'int');
                $query .= ',notes=' . Db::SqlFormat($this->notes,'string');
                $query .= ' where id=' . Db::SqlFormat($this->id,'int');
                $query .= ';';
                Db::ExecuteQuery($query);
            } else {
                $query = 'insert into child (forename,surname,dob,unborn,address,city,county,postcode,telephone,next_kin,nationality,religion,gradual_admission,start,finish,keyworker,backup_keyworker,pickup_password,room_override,notes)';
                $query .= ' values (' . Db::SqlFormat($this->forename,'string');
                $query .= ',' . Db::SqlFormat($this->surname,'string');
                $query .= ',' . Db::SqlFormat($this->dob,'date');
                $query .= ',' . Db::SqlFormat($this->unborn,'bool');
                $query .= ',' . Db::SqlFormat($this->address,'string');
                $query .= ',' . Db::SqlFormat($this->city,'string');
                $query .= ',' . Db::SqlFormat($this->county,'string');
                $query .= ',' . Db::SqlFormat($this->postcode,'string');
                $query .= ',' . Db::SqlFormat($this->telephone,'string');
                $query .= ',' . Db::SqlFormat($this->next_kin,'string');
                $query .= ',' . Db::SqlFormat($this->nationality,'int');
                $query .= ',' . Db::SqlFormat($this->religion,'int');
                $query .= ',' . Db::SqlFormat($this->gradual_admission,'date');
                $query .= ',' . Db::SqlFormat($this->start,'date');
                $query .= ',' . Db::SqlFormat($this->finish,'date');
                $query .= ',' . Db::SqlFormat($this->keyworker,'int');
                $query .= ',' . Db::SqlFormat($this->backup_keyworker,'int');
                $query .= ',' . Db::SqlFormat($this->pickup_password,'string');
                $query .= ',' . Db::SqlFormat($this->room_override,'int');
                $query .= ',' . Db::SqlFormat($this->notes,'string');
                $query .= ');';
                $query .= 'select currval(\'child_id_seq\') as currval;';
                $data = Db::GetData($query,false);
                $this->id = intval($data['currval']);
            }
        }

        return $result;
    }

    public function Load($id) {
        if (is_numeric($id)) {
            $query = 'select age_months(dob) as age,id,forename,format_date(dob) as dob,surname,address,city,county,postcode,telephone,next_kin,nationality,religion,format_date(gradual_admission) as gradual_admission,format_date("start") as start,format_date(finish) as finish,keyworker,backup_keyworker,pickup_password,room_override,unborn,notes';
            $query .= ' from child';
            $query .= ' where id=' . Db::SqlFormat($id,'int');
            $query .= ' limit 1;';
            $data = Db::GetData($query);
            if (count($data)>0) {
                $this->Update($data);
            }
        }
    }

    public function Delete() {
        $query = 'delete from child_contact';
        $query .= ' where child_id=%s;';
        $query .= 'delete from child_session';
        $query .= ' where child_id=%s;';
        $query .= 'delete from child';
        $query .= ' where id=%s;';
        $query = sprintf($query,
            Db::SqlFormat($this->id, 'int'),
            Db::SqlFormat($this->id, 'int'),
            Db::SqlFormat($this->id, 'int')
        );
        Db::ExecuteQuery($query);
    }
}
?>