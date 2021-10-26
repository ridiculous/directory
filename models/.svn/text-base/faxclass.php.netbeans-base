<?php

/* * ************************
 * Fax Model
 *
 * Objectifies the Fax table
 *
 * Inherits from the ModelBase Class
 *
 * Functions: _new(), _create(), _find(), _update(), _attributes()
 *
 * The attributes are set dynamically based on the fax table each time the model is accessed
 *
 * @author Ryan Buckley <rbuckley@hawaii.edu>
 *
 * Last Update: 02/01/2012
 *
 * ************************* */


require_once 'models/model_base.php';

class Fax extends ModelBase {

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
        return parent::query(parent::_all($order));
    }

    public function _destroy($_id) {
        return parent::_destroy($_id);
    }

    public function _find($id) {
        $statement = "SELECT id, fax_machine, fax_number FROM fax WHERE id = $id";
        return parent::fetch_hash(parent::query($statement));
    }

}

?>
