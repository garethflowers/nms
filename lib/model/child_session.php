<?php
class ChildSession {

    public $id = 0;

    public $child_id = null;
    public $session = null;
    public $date = null;
    public $date_till = null;
    public $extra = null;
    public $exclude = null;

    public function __construct($id = 0) {
        if (!empty($id)) {
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
        if (array_key_exists('session', $values)) {
            $this->session = (string)$values['session'];
        }
        if (array_key_exists('date', $values) && IsDate($values['date'])) {
            $this->date = (string)$values['date'];
        }
        if (array_key_exists('date_till', $values) && IsDate($values['date_till'])) {
            $this->date_till = (string)$values['date_till'];
        }
        if (array_key_exists('extra', $values)) {
            $this->extra = IsBool($values['extra']);
        }
        if (array_key_exists('exclude', $values)) {
            $this->exclude = IsBool($values['exclude']);
        }
    }

    public function Validate() {
        $result = array();

        if (empty($this->child_id)) {
            $result[] = 'child_id';
        }
        if (empty($this->session)) {
            $result[] = 'session';
        }
        if (!IsDate($this->date)) {
            $result[] = 'date';
        }
        if (!IsDate($this->date_till)) {
            $result[] = 'date_till';
        }

        return $result;
    }


    public function Save() {
        $result = $this->Validate();

        if (count($result) == 0) {
            if ($this->id > 0) {
                $query = 'update child_session';
                $query .= ' set "session"=' . Db::SqlFormat($this->session,'string');
                $query .= ',date=' . Db::SqlFormat($this->date,'date');
                $query .= ',date_till=' . Db::SqlFormat($this->date_till,'date');
                $query .= ',exclude=' . Db::SqlFormat($this->exclude,'bool');
                $query .= ',extra=' . Db::SqlFormat($this->extra,'bool');
                $query .= ' where id=' . Db::SqlFormat($this->id,'int');
                $query .= ';';
                Db::ExecuteQuery($query);
            }
            else {
                $query = 'insert into child_session (child_id,"session",date,date_till,exclude,extra)';
                $query .= ' values (' . Db::SqlFormat($this->child_id,'int');
                $query .= ',' . Db::SqlFormat($this->session,'string');
                $query .= ',' . Db::SqlFormat($this->date,'date');
                $query .= ',' . Db::SqlFormat($this->date_till,'date');
                $query .= ',' . Db::SqlFormat($this->exclude,'bool');
                $query .= ',' . Db::SqlFormat($this->extra,'bool');
                $query .= ');';
                $query .= 'select currval(\'child_session_id_seq\') as currval;';
                $data = Db::GetData($query,false);
                $this->id = intval($data['currval']);
            }
        }

        return $result;
    }

    public function Load($id) {
        if (is_numeric($id)) {
            $query = 'select id,child_id,"session",format_date(date) as date,format_date(date_till) as date_till,exclude,extra';
            $query .= ' from child_session';
            $query .= ' where id=' . Db::SqlFormat($id,'int');
            $query .= ' limit 1;';
            $data = Db::GetData($query);
            if (count($data)>0) {
                $this->Update($data);
            }
        }
    }

    public function Delete() {
        $query = 'delete from child_session';
        $query .= ' where id=%s;';
        $query = sprintf($query, $this->id);
        Db::ExecuteQuery($query);
    }
}
?>