<?php

/* * ************************
 * Person Model
 *
 * Objectifies the people table
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

class Program extends ModelBase {

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

    public function _find($coord_id, $pid) {
        $statement = "SELECT r.id, r.pro_name, d.id as department_id, d.dep_name as dep_name,
              " . parent::get_concat_person() . " as pro_coordinator, r.mailbox
              FROM program r
              LEFT JOIN department d ON r.department_id = d.id
              LEFT JOIN people p ON p.id = '$coord_id' WHERE r.id = '$pid'";
        return parent::fetch_hash(parent::query($statement));
    }

}

?>
