<?php

/* * ************************
 * Model Base Class
 *
 * Objectifies Database Tables
 *
 * Dynamically generates routine SQL statements for models
 *
 * Functions: _attributes(), _new(), _create(), _update(), _all(), _destroy()
 *
 * The attributes are based on the respective model
 *
 * @author Ryan Buckley <rbuckley@hawaii.edu>
 *
 * Last Update: 01/21/2012
 *
 * *************************** */

require_once 'db/db.php';

class ModelBase extends Db {

    private $model = '';
    private $attributes = array();

    public function __construct($model) {
        $this->model = $model;
        parent::__construct();
    }

    #
    # Returns a set of attributes for each column in the table. Merges values in arguments

    public function _new() {
        return $this->_attributes(func_get_args());
    }

    #
    # Saves the record by creating an insert statement

    public function _save() {
        $numargs = func_num_args();
        $args = func_get_args();
        $args = $this->_flatten($args);

        $attrs = array();
        for ($i = 0; $i < $numargs; $i++) {
            $args[$i]['created_at'] = date('Y-m-d H:i:s');
            foreach ($args[$i] as $key => $val) {
                if ($key != 'id')
                    $attrs[] = "'$val'";
            }
        }
        $columns = $this->_attributes();
        unset($columns['id']);

        return "INSERT INTO $this->model(" . implode(', ', array_keys($columns)) . ") VALUES(" . implode(', ', $attrs) . ")";
    }

    #
    # Update record by creating sql update statement

    public function _update() {
        $numargs = func_num_args();
        $args = func_get_args();
        $args = $this->_flatten($args);

        $id = 0;
        $attrs = array();
        for ($i = 0; $i < $numargs; $i++) {
            $args[$i]['updated_at'] = date('Y-m-d H:i:s');
            if(isset($args[$i]['last_login'])) unset($args[$i]['last_login']);
            foreach ($args[$i] as $key => $val) {
                if ($key == 'id')
                    $id = $val;
                else
                    $attrs[] = "$key = '$val'";
            }
        }
        return "UPDATE $this->model SET " . implode(', ', $attrs) . " WHERE id = $id ";
        ;
    }

    public function _destroy($_id) {
        return "DELETE FROM $this->model WHERE id = $_id";
    }

    #
    # Return all records for model (w/ certain columns suppressed)

    public function _all($order = 'id DESC') {
        $attrs = $this->_attributes();

        unset($attrs['created_at']);
        unset($attrs['created_by']);
        unset($attrs['updated_at']);
        unset($attrs['updated_by']);

        return $statement = "SELECT " . implode(', ', array_keys($attrs)) . " FROM $this->model ORDER BY $order";
    }

    /* Class Helper Methods */

    # Returns all the attributes/columns of the table
    # Arguments are merged if included in attributes  

    private function _attributes($attrs = array()) {

        $statement = parent::column_names($this->model);
        $result = parent::query($statement);
        $attrs = isset($attrs[0]) ? $this->_flatten($attrs[0]) : array();

        while ($row = parent::fetch_hash($result)) {
            $col = array_values($row);
            $this->attributes[$col[0]] = isset($attrs[$col[0]]) ? $attrs[$col[0]] : '';
        }

        return $this->attributes;
    }

    private function _flatten($args) {
        $attrs = array();
        for ($i = 0; $i < count($args); $i++) {
            foreach ($args[$i] as $key => $val) {
                $attrs[$key] = $val;
            }
        }
        return $attrs;
    }

}

?>