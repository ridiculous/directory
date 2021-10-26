<?php

/* * ************************
 * Division Model
 *
 * Objectifies the Division table
 *
 * Inherits from the ModelBase Class
 *
 * Functions: _new(), _create(), _find(), _update(), _attributes()
 *
 * The attributes are set dynamically based on the people table each time the model is accessed
 *
 * @author Ryan Buckley <rbuckley@hawaii.edu>
 *
 * Last Update: 02/01/2012
 *
 * ************************* */


require_once 'models/model_base.php';

class Division extends ModelBase {

    public function __construct($cmd = null) {
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

    public function _all($order = 'name ASC') {
        return parent::query(parent::_all($order));
    }

    public function _destroy($_id) {
        return parent::_destroy($_id);
    }

    public function _find($id) {
        $statement = "SELECT id, name FROM division WHERE id = $id";
        return parent::fetch_hash(parent::query($statement));
    }
    
    // return departments for a given division
    public function _departments($id) {
        $depts = array();
        $statement = "SELECT id FROM department WHERE division_id = $id";
        $results = parent::query($statement);
        while ($row = parent::fetch_hash($results)) {
            $depts[] = $row['id'];
        }
        return $depts;
    }

}

?>
