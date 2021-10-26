<?php

/* * ************************
 * Department Model
 *
 * Objectifies the Department table
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

class Department extends ModelBase {

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

    public function _find($id, $chair_id, $division_id) {
        $statement = "SELECT d.id, d.dep_name, d.dep_acronym, " . parent::get_concat_person() . " as dep_chair,
                d.admin_type, d.division_id, v.name as division_name
            FROM department d 
            INNER JOIN division v ON v.id = '$division_id'
            LEFT OUTER JOIN people p ON p.id = '$chair_id' 
            WHERE d.id = '$id'";
        return parent::fetch_hash(parent::query($statement));
    }

    public function _for_select($ids) {
        $statement = "SELECT id, dep_name FROM department WHERE id in ($ids)";
        return parent::query($statement);
    }

}

?>
