<?php

/*
 * Db.php
 * Database Connectivity Class
 */

class Db {

    public function __construct($host, $port, $database, $username, $password) {
        $db = @pg_connect('host=' . $host . ' port=' . $port . ' dbname=' . $database . ' user=' . $username . ' password=' . $password);

        if (!$db && PHP_SELF != '/error.php') {
            header('location: /error.php');
            exit;
        }
    }

    /*
     * run a postgres function and return as an array
     */

    public function ExecuteQuery($query, $redirect = true) {
        $result = pg_query($query);
        if (!$result) {
            if ($redirect) {
                header('location: ' . DOCUMENT_ROOT . '/error.php');
                exit;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /*
     * run a postgres function and return as an array
     */

    public function GetData($query, $redirect = true) {
        $result = pg_query($query);
        if (!$result) {
            if ($redirect) {
                header('location: ' . DOCUMENT_ROOT . '/error.php');
                exit;
            } else {
                return array();
            }
        } else {
            $data = pg_fetch_assoc($result);
            $value = null;
            if (is_array($data) && count($data) > 0) {
                foreach ($data as &$value) {
                    $value = htmlspecialchars(trim($value));
                }
            }
            return $data;
        }
    }

    /*
     * run a postgres query and return it as a multidimensional array
     */

    public function GetDataArray($query, $redirect = true) {
        $result = pg_query($query);
        if (!$result) {
            if ($redirect) {
                header('location: ' . DOCUMENT_ROOT . '/error.php');
                exit;
            } else {
                return array();
            }
        } else {
            $data = pg_fetch_all($result);
            $value = null;
            $tempdata = null;
            if (is_array($data) && count($data) > 0) {
                foreach ($data as &$tempdata) {
                    foreach ($tempdata as &$value) {
                        $value = htmlspecialchars(trim($value));
                    }
                }
                return $data;
            } else {
                return array();
            }
        }
    }

    /*
     * run a postgres function and return a single result
     */

    public function GetScalar($query, $redirect = true) {
        $result = Db::GetData($query, $redirect);
        if (is_array($result)) {
            reset($result);
            return current($result);
        } else {
            return null;
        }
    }

    /*
     *
     */

    public function Dictionary($query) {
        $dictionary = array();

        $data = Db::GetDataArray($query);
        foreach ($data as $row) {
            $dictionary[$row['k']] = $row['v'];
        }

        return $dictionary;
    }

    /*
     * format a variable for use in a sql query
     */

    public function SqlFormat($value, $type) {
        switch ($type) {
            case 'string':
                return !empty($value) ? 'E\'' . pg_escape_string(utf8_encode(trim($value))) . '\'::character varying' : 'null';
                break;
            case 'int':
                return is_numeric($value) ? (string) intval($value) : 'null';
                break;
            case 'dec':
                return is_numeric($value) ? (string) floatval($value) : 'null';
                break;
            case 'date':
                return !empty($value) && count(explode('-', $value)) == 3 ? vsprintf('\'%04d-%02d-%02d\'::date', array_reverse(explode('-', $value))) : 'null';
                break;
            case 'bool':
                return !empty($value) && $value ? 'true::boolean' : 'false::boolean';
                break;
            case 'timestamp':
                return $value != !empty($value) && '--' ? '\'' . $value . '\'::timestamp' : 'null';
                break;
        }
    }

}

?>