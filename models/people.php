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

class People extends ModelBase {

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
    
    public function _destroy($_id){
        return parent::_destroy($_id);
    }

    public function _find($_contact_id, $_id) {
        $statement =
                "SELECT p.id, p.first_name, p.last_name, UPPER(" . parent::get_ifnil() . "(p.middle_init,'')) as middle_init,p.comments, p.permanent_position,
                p.email, p.mailbox,p.phone_number, l.id as location_id, l.building as location_name, p.room_number, p.department_id, 
                r.pro_name as program_name, r.id as program_id, " . parent::get_concat_person('cnt') . " as contact, p.position, p.website
                FROM people p
                LEFT JOIN location l on l.id = p.location_id
                LEFT JOIN program r on r.id = p.program_id
                LEFT JOIN people cnt ON cnt.id = '$_contact_id' WHERE p.id = '$_id'
		    ORDER BY p.last_name ";
        return parent::fetch_hash(parent::query($statement));
    }

}

?>
