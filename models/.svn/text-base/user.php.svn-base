<?php

/* * ************************
 * User Model
 *
 * Objectifies the User table
 *
 * Inherits from the ModelBase Class
 *
 * Functions: _new(), _create(), _find(), _update(), _attributes()
 *
 * The attributes are set dynamically based on the people table each time the model is accessed
 *
 * @author Ryan Buckley <rbuckley@hawaii.edu>
 *
 * Last Update: 01/19/2012
 *
 * ************************* */


require_once 'models/model_base.php';

class Users extends ModelBase {

    public $Role = '';
    public $Access = '';
    public $Dept_id = '';
    public $Dept_name = '';
    public $Div_id = '';
    public $Div_name = '';

    public function __construct() {
        parent::__construct(strtolower(get_class($this)));
    }

    public function _new() {
        $args = func_get_args();
        return parent::_new($args);
    }

    public function _save() {
        $args = func_get_args();
        return parent::_save($args);
    }

    public function _update() {
        $args = func_get_args();
        return parent::_update($args);
    }

    public function _all($order = 'id DESC') {
        return parent::_all($order);
    }

    public function _destroy($_id) {
        return parent::_destroy($_id);
    }

    public function _find($id) {
        // select role and access for this user
        $s = "SELECT id, role, access_level FROM users WHERE id = '$id'";

        // execute query
        $_u = parent::fetch_hash(parent::query($s));

        // Store users role and access level for use in view
        $this->Role = $_u['role'];
        $this->Access = $_u['access_level'];

        // Join on division if acces is division, otherwise join on department table
        if ($this->_is_division()) {
            $statement = "SELECT u.id, u.person_id, u.username, u.first_name , u.last_name ,u.email, u.phone,
                d.name as access_name, pro.pro_name, pro.id as program_id, u.position, u.access_level, u.password_reset, u.role
                FROM users u
                LEFT JOIN division d ON d.id = u.access_level
                LEFT JOIN people p ON p.id = u.person_id
                LEFT JOIN program pro on pro.id = p.program_id  WHERE u.id = '$id'";
        } else {
            $statement = "SELECT u.id, u.person_id, u.username, u.first_name , u.last_name ,u.email, u.phone,
                d.dep_acronym as access_name, pro.pro_name, pro.id as program_id, u.position, u.access_level, u.password_reset, u.role
                FROM users u
                LEFT JOIN department d ON d.id = u.access_level
                LEFT JOIN people p ON p.id = u.person_id
                LEFT JOIN program pro on pro.id = p.program_id  WHERE u.id = '$id'";
        }
        // Execute query and store results in variable
        $_user = parent::fetch_hash(parent::query($statement));

        // determine which access level and name to set (div or dept)
        if ($this->_is_division()) {
            $this->Div_id = $_user['access_level'];
            $this->Div_name = $_user['access_name'];
        } else if ($this->_is_department()) {
            $this->Dept_id = $_user['access_level'];
            $this->Dept_name = $_user['access_name'];
        }

        return $_user;
    }

    public function _update_last_login($id) {
        $statement = "UPDATE users SET last_login = '" . date('Y-m-d H:i:s') . "' WHERE id = $id";
        return parent::fetch_hash(parent::query($statement));
    }

    public function _is_admin() {
        return $this->Access == -1;
    }

    public function _is_department() {
        return $this->Role == 'department';
    }

    public function _is_division() {
        return $this->Role == 'division';
    }

    public function _division_or_department() {
        return $this->_is_department() || $this->_is_division();
    }

}

?>
