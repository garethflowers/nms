<?php

class User {

    private $id = 0;
    private $username = null;
    private $password = null;
    private $userlevel = 0;
    private $name = null;

    public function __construct($username, $password) {
        $query = 'select username,password,forename,surname,id,userlevel';
        $query .= ' from staff';
        $query .= ' where upper(username)=' . Db::SqlFormat(strtoupper($username), 'string');
        $query .= ' and password=' . Db::SqlFormat($password, 'string');
        $query .= ' limit 1;';
        $data = Db::GetData($query);

        if (count($data) > 0) {
            $this->username = $data['username'];
            $this->password = $data['password'];
            $this->name = $data['forename'] . ' ' . $data['surname'];
            $this->userlevel = $data['userlevel'];
            $this->id = intval($data['id']);
        }
    }

    public function GetLevel() {
        return $this->userlevel;
    }

    public function GetID() {
        return $this->id;
    }

    public function GetUsername() {
        return $this->username;
    }

    public function GetName() {
        return $this->name;
    }

}

?>