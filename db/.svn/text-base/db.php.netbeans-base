<?php

/*
 * Class Database: Functions for interacting with the database layer
 * Allows the application to function the same with either a Microsoft SQL or MySQL backend.
 * The methods, commands and syntax used by the classes functions are based on the DBTYPE variable. Defaults to MS SQL if not set
 * Add 06|05|2011 by rbuckley@hawaii.edu
 */
require_once('db/mssql_adapter.php');
require_once('db/mysql_adapter.php');

class Db {

    private $DBTYPE;
    private $Username;
    private $Password;
    private $Database = 'uhmc_directory';
    private $Server;
    private $Adapter;
    private $Datasource;
    private $CONCAT_PERSON;
    private $CONCAT_FULLNAME;
    private $CONCAT_LOCATION;
    private $CONCAT_DEPT;
    private $CONCAT_DDP;
    private $JOIN_ID;
    private $OPTIONAL_DDP;
    private $IFNIL; # MS SQL => ISNULL, MySQL => IFNULL
    private $OP; #Operator used in concatenation MS SQL => +, MySQL => ,
    private $CONCAT; # MS SQL => "", MySQL => "CONCAT"
    private $STRINDEX; #MS SQL => "Charindex", MySQl => "LOCATE"
    private $WRAP; #used for wrapping field names that have spaces. I.E `Program Coordinator`
    private $VIEWS = array(
        "all_tables",
        "all_tables_user",
        "all_tables_public",
        "all_tables_person_public",
        "ajax_person",
        "users_view",
        "program_view_user",
        "department_view_user",
        "department_view",
        "program_view",
        "location_view",
        "peopleA",
        "program_view_a",
        "peopleA_public"
    );


    /* Database connect methods
      #MS SQL : sqlsrv_connect
      #MySQL : mysql_connect


     * Concatenation methods
      #MS SQL : last_name + ', ' + first_name
      #MySQL : CONCAT(last_name, ', ' , first_name)
     */

    function __construct() {
        $this->set_db_info();
        $this->Adapter = ($this->DBTYPE === "mysql") ? new MysqlAdapter() : new MssqlAdapter();
        $this->setup_vars();
        $this->connect_to_db();
    }

    private function set_db_info() {
        $_host = $_SERVER['SERVER_NAME'];
        $this->DBTYPE   = ($_host == 'localhost') ? 'mysql': 'mssql';
        $this->Username = ($_host == 'localhost') ? 'root' : 'directoryadmin';
        $this->Password = ($_host == 'localhost') ? ''     : 'H0ldD1rect0ry';
        $this->Server   = ($_host == 'localhost') ? $_host : 'UHMCDIR';
    }

    private function setup_vars() {
        $this->set_ifnil();
        $this->set_op();
        $this->set_concat();
        $this->set_strindex();
        $this->set_wrap();
        $this->set_join();
    }

    private function connect_to_db() {
        $this->Datasource = $this->_connect(); // sqlsrv_connect($this->Server, $connectionOptions);
        if (!$this->Datasource) {
            $this->throw_db_error();
        }
        $this->Adapter->_after_connect();
    }

    private function _connect() {
        $options = array("UID" => $this->Username, "PWD" => $this->Password, "Database" => $this->Database); #MS SQL
        return $this->Adapter->_connect($this->Server, $options); //$this->Username, $this->Password
    }

    protected function throw_db_error() {
        return $this->Adapter->_throw_db_error();
    }

    public function query($statement) {
        $result = $this->Adapter->_query($statement);
        if (!$result)
            $this->throw_db_error();
        return $result;
    }

    public function fetch($query_results) {
        return $this->Adapter->_fetch($query_results);
    }

    public function fetch_hash($query_results) {
        return $this->Adapter->_fetch_hash($query_results);
    }

    public function fields($results_array) {
        return $this->Adapter->_fields($results_array);
    }

    public function has_rows($result) {
        return $this->Adapter->_rows($result);
    }

    public function column_names($table) {
        return $this->Adapter->_column_names($table);
    }

    public function authenticate_user($u, $p) {
        $s = "SELECT u.id FROM users u JOIN passwords p on p.users_id = u.id WHERE u.id = '$u' and p.pw_encrypted = '$p'";
        return $this->query($s);
    }

    public function query_person_details($_id) {
        $q = "SELECT o.comments, " . $this->get_concat_person() . " as Fullname, p.phone_number FROM people o LEFT JOIN people p ON p.id = o.contact_id WHERE o.id = '$_id'";
        return $q;
    }

    public function get_buildings() {
        $q = "SELECT id, building FROM location ORDER BY building";
        return $q;
    }

    public function get_first_building() {
        return $this->fetch($this->query($this->Adapter->_select_limit('location', 'id', '1')));
    }

    public function get_depts($acr = FALSE) {
        $q = $acr ? "SELECT id, dep_acronym as Access FROM department WHERE dep_acronym IS NOT NULL ORDER BY dep_acronym" : "SELECT id, dep_name as dep FROM department WHERE dep_name IS NOT NULL ORDER BY dep_name";
        return $q;
    }

    public function get_person_fullname() {
        return $this->concat_fullname();
    }

    ## 
    ## Public functions for creating and dropping views
    ## This app relies on database views for many of the queries
    ## Pass URL for view to seed.php to create or drop
    ##
    /*
     * Following commands can be passed in ( seed.php?cmd=value ):
     * cmd              value
     * create_view    : "all"           => creates all of the views in db.php
     *                : <name of view>  => specify a view name to create
     *
     * drop_view      : "all"           => drops all views
     *                : <name of view>  => specify name of view to drop
     *
     * seed           :  1              => Create initial set of records w/ data below
     */

    public function drop_all_views() {
        $view = $this->get_views();
        foreach ($view as $v) {
            if ($this->query("DROP VIEW $v "))
                print "<h3>The view: $v, was successfully dropped</h3>";
        }
    }

    public function drop_view($view) {
        if (in_array($view, $this->get_views())) {
            if ($this->query("DROP VIEW $view "))
                print "<h2>The view: $view , was successfully dropped</h2>";
            else
                print "<h2>Failed to drop $view</h2>";
        }
    }

    public function create_view($view = null) {
        if ($view && in_array($view, $this->get_views())) {
            if ($this->{$view}())
                print "<h2>The view: $view , was successfully created </h2>";
            else
                print "<h2>Failed to create $view</h2>";
        }else {
            $all_views = $this->get_views();
            foreach ($all_views as $v) {
                if ($this->{$v}())
                    print "<h2>The view: $v , was successfully created </h2>";
                else
                    print "<h2>Failed to create $v </h2>";
            }
        }
    }

    # Functions to create the views used in the app

    protected function all_tables() {
        if ($this->query("CREATE VIEW all_tables AS
      SELECT p.id,  " . $this->concat_fullname() . " as Person,
      p.Email, p.Mailbox,p.phone_number as Phone," . $this->get_concat_location() . " as Location ,
      " . $this->get_concat_dept() . " as 'Dep/Program', p.Position FROM people p
      LEFT JOIN location l on l.id = p.location_id
      LEFT JOIN program r on r.id = p.program_id
      LEFT JOIN department d on d.id = $this->JOIN_ID"
        ))
            return true;
    }

    protected function all_tables_public() {
        if ($this->query(
                        "CREATE VIEW all_tables_public AS
              SELECT p.id, " . $this->concat_fullname() . " as Person,
              p.Email, p.Mailbox,p.phone_number as Phone, " . $this->get_concat_location() . " as Location,
              " . $this->get_concat_ddp() . " as 'Div/Dep/Program', p.Position, p.Permanent_position FROM people p
              LEFT JOIN location l on l.id = p.location_id
              LEFT JOIN program r on r.id = p.program_id
              LEFT JOIN department d on d.id = $this->JOIN_ID
              LEFT JOIN division v on v.id = d.division_id"
        ))
            ;
        return true;
    }

    protected function all_tables_user() {
        if ($this->query(
                        "CREATE VIEW all_tables_user AS
      SELECT p.id, " . $this->concat_fullname() . " as Person,
      p.Email, p.Mailbox,p.phone_number as Phone, " . $this->get_concat_location() . " as Location,
      " . $this->get_concat_dept() . " as 'Dep/Program', p.Position, d.id as 'department_id' FROM people p
      LEFT JOIN location l on l.id = p.location_id
      LEFT JOIN program r on r.id = p.program_id
      JOIN department d on d.id = $this->JOIN_ID"
        ))
            return true;
    }

    protected function ajax_person() {
        if ($this->query(
                        "CREATE VIEW ajax_person AS SELECT p.id," . $this->get_concat_person() . " as person from people p"
        ))
            return true;
    }

    protected function users_view() {
        if ($this->query(
                        "CREATE VIEW users_view AS
      SELECT u.id, u.Username, " . $this->get_concat_person('u') . " as Name, u.Email, u.Phone,
      u.role as Access, u.last_login as 'Last Login' FROM users u"
        ))
            return true;
    }

    protected function program_view_user() {
        if ($this->query(
                        "CREATE VIEW program_view_user AS
      select r.id as id, r.pro_name as Program, d.dep_acronym as Department," . $this->get_concat_person() . " as 'Program Coordinator', r.mailbox as Mailbox, d.id as department_id
      from program r LEFT OUTER JOIN people p ON r.pro_coordinator = p.id
      LEFT OUTER JOIN department d ON r.department_id = d.id"
        ))
            return true;
    }

    protected function department_view_user() {
        if ($this->query(
                        "CREATE VIEW department_view_user AS
      SELECT d.id as department_id, d.dep_name as Department, d.dep_acronym as Acronym," . $this->get_concat_person() . " as Chair,v.name as Division
      FROM department d
      INNER JOIN division v on v.id = d.division_id
      LEFT JOIN people p ON p.id = d.dep_chair"
        ))
            return true;
    }

    protected function department_view() {
        if ($this->query(
                        "CREATE VIEW department_view AS
      SELECT d.id as department_id, d.dep_name as Department, d.dep_acronym as Acronym,
      " . $this->get_concat_person() . " as Chair,v.name as Division 
      FROM department d
      INNER JOIN division v on v.id = d.division_id
      LEFT OUTER JOIN people p ON p.id = d.dep_chair"
        ))
            return true;
    }

    protected function program_view() {
        if ($this->query(
                        "CREATE VIEW program_view AS
      SELECT r.id as id, r.pro_name as Program, d.dep_acronym as Department,
      " . $this->get_concat_person() . " as 'Program Coordinator', r.mailbox as Mailbox
      from program r LEFT OUTER JOIN people p ON r.pro_coordinator = p.id
      LEFT OUTER JOIN department d ON r.department_id = d.id"
        ))
            return true;
    }

    protected function location_view() {
        if ($this->query(
                        "CREATE VIEW location_view AS
      select id, Building
      from location"
        ))
            return true;
    }

    protected function peopleA() {
        if ($this->query(
                        "CREATE VIEW peopleA AS
      SELECT  p.id,  " . $this->concat_fullname() . " as Person,
      p.Email, p.Mailbox,p.phone_number as Phone,  " . $this->get_concat_location() . " as Location,
       " . $this->get_concat_dept() . " as 'Dep/Program', p.Position FROM people p
      LEFT JOIN location l on l.id = p.location_id
      LEFT JOIN program r on r.id = p.program_id
      LEFT JOIN department d on d.id = $this->JOIN_ID
      WHERE SUBSTRING(p.last_name,1,1) = 'a'"
        ))
            return true;
    }

    protected function program_view_a() {
        if ($this->query(
                        "CREATE VIEW program_view_a AS
      SELECT r.id as id, r.pro_name as Program, d.dep_acronym as Department,
      " . $this->get_concat_person() . " as 'Program Coordinator', r.mailbox as Mailbox
      from program r LEFT OUTER JOIN people p ON r.pro_coordinator = p.id
      LEFT OUTER JOIN department d ON d.id = $this->JOIN_ID
      WHERE SUBSTRING(pro_name,1,1) = 'a'"
        ))
            return true;
    }

    protected function peopleA_public() {
        if ($this->query(
                        "CREATE VIEW peopleA_public AS
                SELECT p.id, " . $this->concat_fullname() . " as Person,
p.Email, p.Mailbox,p.phone_number as Phone, " . $this->get_concat_location() . " as Location,
" . $this->get_concat_ddp() . " as 'Div/Dep/Program', p.Position, permanent_position as perm FROM people p
                LEFT JOIN location l on l.id = p.location_id
                LEFT JOIN program r on r.id = p.program_id
                LEFT JOIN department d on d.id = $this->JOIN_ID
                LEFT JOIN division v on v.id = d.division_id
                WHERE SUBSTRING(p.last_name,1,1) = 'a'"
        ))
            return true;
    }

    protected function all_tables_person_public() {
        if ($this->query(
                        "CREATE VIEW all_tables_person_public AS
                 SELECT p.id,  p.last_name, p.first_name, p.middle_init,
p.Email, p.Mailbox,p.phone_number as Phone, " . $this->get_concat_location() . " as Location,
" . $this->get_concat_ddp() . " as 'Div/Dep/Program', p.Position, p.permanent_position as perm FROM people p
                LEFT JOIN location l on l.id = p.location_id
                LEFT JOIN program r on r.id = p.program_id
                LEFT JOIN department d on d.id = $this->JOIN_ID
                LEFT JOIN division v on v.id = d.division_id"
        ))
            return true;
    }

    /*
     *   //>>MS SQL
      #(IFNULL(p.last_name,'') + ', ' + IFNULL(p.first_name,'') + ' ' + UPPER(IFNULL(p.middle_init,'')))as Person
      #(IFNULL(l.building,'') + ' ' + IFNULL(p.room_number,'')) as Location
      #(IFNULL(d.dep_acronym,'') + ' - ' + IFNULL(r.pro_name,'')) as 'Dep/Program'


      //>>MySQL
     * Concatenations
      #CONCAT( IFNULL(first_name,'') , ' ' , IFNULL(last_name,'') ) as Person
      #CONCAT( IFNULL(p.last_name,'') , ', ', IFNULL(p.first_name,'') ,' ', UPPER(IFNULL(p.middle_init,'')) ) as Person or Fullname
      #CONCAT( IFNULL(l.building,''),' ', IFNULL(p.room_number,'') ) as Location
      #CONCAT( IFNULL(d.dep_acronym,''), ' - ', IFNULL(r.pro_name,'') ) as 'Dep/Program'
     */


    #Functions to retrieve private variables used for SQL statements. Values are set based on database type
    #Start >> Getters

    public function get_ifnil() {
        return $this->IFNIL;
    }

    public function get_op() {
        return $this->OP;
    }

    public function get_concat() {
        return $this->CONCAT;
    }

    public function get_strindex() {
        return $this->STRINDEX;
    }

    public function wrap() {
        return $this->WRAP;
    }

    public function get_concat_person($pre = NULL) {
        $this->set_concat_person($pre);
        return $this->CONCAT_PERSON;
    }

    protected function concat_fullname($prefix = null) {
        $this->set_concat_fullname($prefix);
        return $this->CONCAT_FULLNAME;
    }

    protected function get_concat_location() {
        $this->set_concat_location();
        return $this->CONCAT_LOCATION;
    }

    protected function get_concat_dept() {
        $this->set_concat_dept();
        return $this->CONCAT_DEPT;
    }

    protected function get_concat_ddp() {
        $this->set_concat_ddp();
        return $this->CONCAT_DDP;
    }

    protected function get_views() {
        return $this->VIEWS;
    }

    protected function get_optional_ddp() {
        $this->set_optional_ddp();
        return $this->OPTIONAL_DDP;
    }

    #End Gets
    #Store the syntax for concatenating fields in private SQL variables
    #Start >> Setter functions

    private function set_strindex() {
        $this->STRINDEX = $this->Adapter->_string_index();
    }

    private function set_ifnil() {
        $this->IFNIL = $this->Adapter->_ifnil();
    }

    private function set_op() {
        $this->OP = $this->Adapter->_op();
    }

    private function set_concat() {
        $this->CONCAT = $this->Adapter->_concat();
    }

    private function set_wrap() {
        $this->WRAP = $this->Adapter->_wrap();
    }

    protected function set_optional_ddp() {
        $this->OPTIONAL_DDP = $this->Adapter->_ddp();
    }

    protected function set_concat_person($prefix = null) {
        $p = $prefix ? $prefix : 'p'; #Set field prefix ( ie what table )
        $this->CONCAT_PERSON = $this->get_concat() . "( " . $this->get_ifnil() . "($p.first_name,'')" . $this->get_op() . "' '" . $this->get_op() . " " . $this->get_ifnil() . "($p.last_name,'') )";
    }

    protected function set_concat_fullname($prefix = null) {
        $p = $prefix ? ($prefix == 'none' ? "" : $prefix) : "p.";
        $this->CONCAT_FULLNAME = $this->get_concat() . "( " . $this->get_ifnil() . "(" . $p . "last_name,'')" . $this->get_op() . " ', ' " . $this->get_op() . "  " . $this->get_ifnil() . "(" . $p . "first_name,'')  " . $this->get_op() . " ' ' " . $this->get_op() . "  UPPER(" . $this->get_ifnil() . "(" . $p . "middle_init,'')) )";
    }

    protected function set_concat_location() {
        $this->CONCAT_LOCATION = $this->get_concat() . "( " . $this->get_ifnil() . "(l.building,'') " . $this->get_op() . " ' ' " . $this->get_op() . "  " . $this->get_ifnil() . "(p.room_number,'') )";
    }

    protected function set_concat_dept() {
        $this->CONCAT_DEPT = $this->get_concat() . "( " . $this->get_ifnil() . "(d.dep_acronym,'') " . $this->get_op() . "  ' - ' " . $this->get_op() . "  " . $this->get_ifnil() . "(r.pro_name,'') )";
    }

    protected function set_concat_ddp() {
        $this->CONCAT_DDP = $this->get_concat() . "(" . $this->get_ifnil() . "(v.name,'')" . $this->get_op() . " ' - '" . $this->get_op() . $this->get_optional_ddp() . ")";
    }

    protected function set_join() {
        $this->JOIN_ID = $this->Adapter->_ddp_join_id();
    }

//SELECT p.id, 
//CONCAT( IFNULL(p.last_name,'') , ', ' , IFNULL(p.first_name,'') , ' ' , UPPER(IFNULL(p.middle_init,'')) ) as Person,
//p.Email, p.Mailbox,p.phone_number as Phone, CONCAT( IFNULL(l.building,'') , ' ' , IFNULL(p.room_number,'') ) as Location,
//CONCAT(IFNULL(d.admin_type,'') , ' - ' , IF(p.program_id > 0, CONCAT( IFNULL(d.dep_acronym,'') , ' - ', IFNULL(r.pro_name,'')), IFNULL(d.dep_acronym,''))) as 'Div/Dep/Program',
//p.Position,
//p.Permanent_position 
//FROM people p 
//LEFT JOIN location l on l.id = p.location_id 
//LEFT JOIN program r on r.id = p.program_id 
//LEFT JOIN department d on d.id = if(p.program_id > 0, r.department_id, p.department_id)
    #End >> Setters
}

/*
 * END OF CLASS
 */

/*
 * 
 * CREATE DATABASE & TABLES !!!!MySQL!!!!!
 * 

 * 
  CREATE DATABASE uhmc_directory

  delimiter $$
  CREATE TABLE `people` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(55) DEFAULT NULL,
  `last_name` varchar(55) DEFAULT NULL,
  `middle_init` varchar(15) DEFAULT NULL,
  `email` varchar(65) DEFAULT NULL,
  `website` varchar(155) DEFAULT NULL,
  `username` varchar(65) DEFAULT NULL,
  `mailbox` varchar(30) DEFAULT NULL,
  `phone_number` varchar(19) DEFAULT NULL,
  `cell_number` varchar(19) DEFAULT NULL,
  `position` varchar(85) DEFAULT NULL,
  `room_number` varchar(30) DEFAULT NULL,
  `comments` varchar(255) DEFAULT NULL,
  `contact_id` int(11) DEFAULT NULL,
  `location_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `permanent_position` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
  ) ENGINE=MyISAM AUTO_INCREMENT=1 $$
 * 
  CREATE TABLE `department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dep_name` varchar(85) DEFAULT NULL,
  `dep_acronym` varchar(50) DEFAULT NULL,
  `dep_chair` int(11) DEFAULT NULL,
  `admin_type` varchar(55) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
  ) ENGINE=MyISAM AUTO_INCREMENT=1 $$
 * 
  CREATE TABLE `division` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(85) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
  ) ENGINE=MyISAM AUTO_INCREMENT=1 $$

  CREATE TABLE `fax` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fax_machine` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci$$


  CREATE TABLE `location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `building` varchar(65) DEFAULT NULL,
  `island` varchar(45) DEFAULT NULL,
  `building_manager` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
  ) ENGINE=MyISAM AUTO_INCREMENT=1 $$


  CREATE TABLE `passwords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL,
  `pw_encrypted` varchar(255) DEFAULT NULL,
  `password_number` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
  ) ENGINE=MyISAM AUTO_INCREMENT=1 $$


  CREATE TABLE `program` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pro_name` varchar(145) DEFAULT NULL,
  `mailbox` varchar(40) DEFAULT NULL,
  `pro_coordinator` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
  ) ENGINE=MyISAM AUTO_INCREMENT=1 $$


  CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) NOT NULL,
  `username` varchar(85) DEFAULT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(65) DEFAULT NULL,
  `middle_init` varchar(1) DEFAULT NULL,
  `suffix` varchar(5) DEFAULT NULL,
  `email` varchar(105) DEFAULT NULL,
  `phone` varchar(19) DEFAULT NULL,
  `position` varchar(45) DEFAULT NULL,
  `access_level` varchar(4) DEFAULT NULL,
  `security_question` varchar(85) DEFAULT NULL,
  `security_answer` varchar(85) DEFAULT NULL,
  `password_reset` tinyint(1) DEFAULT NULL,
  `account_locked` bit(1) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
  ) ENGINE=MyISAM AUTO_INCREMENT=1
 *
 * 
 * NEW MIGRATIONS
 * 
 *    CREATE TABLE `division` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(85) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
  ) ENGINE=MyISAM AUTO_INCREMENT=1 $$
 * 
 * ALTER TABLE department ADD COLUMN division_id int(11) NOT NULL
 *
 * ALTER TABLE people ADD COLUMN department_id int(11) NOT NULL
 * 
 * ALTER TABLE users ADD COLUMN role varchar(55) DEFAULT NULL
 *   
 */
?>
