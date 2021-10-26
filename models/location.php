<?php

/* * ************************
 * Location Model
 *
 * Objectifies the location table
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

class Location extends ModelBase {

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
        $statement = "SELECT id, building FROM location WHERE id = $id";
        return parent::fetch_hash(parent::query($statement));
    }

}

?>
