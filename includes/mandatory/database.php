<?php
/**
 * Basic database functionality
 * 
 * As many instances as wanted
 * with equal or different credentials
 */
class Database extends Mandatory {
    
    private static $configuration = Array(
        'server'        => "",
        'user'          => "",
        'password'      => "",
        'db'            => ""
    );
    protected $server, $user, $password, $db;
    
    private $mysqli = NULL;
    
    
    public static function set_basic_values ($values = NULL) {
        parent::set_basic_values($values, self::$configuration);
    }
    
    /*  */
    
    /**
     * Complete result, row by row
     */
    public function select ($sql) {
        
        $raw_result = $this->mysqli->query($sql);
        $result = Array();
        
        if ( $raw_result )
            while ( $row = mysqli_fetch_assoc($raw_result) )
		$result[] = $row;
        
        $raw_result->close();
        
        return $result;
    }
    
    /**
     * Second parameter decides if method is more likely
     * 'insert()' or 'update()'
     */
    public function execute ($sql, $return_insert_id = FALSE) {
        
        $success = $this->mysqli->query($sql);
        
        if ( $success && $return_insert_id )
            return $this->mysqli->insert_id;
        
        return $success;
    }
    
    /*  */
    
    public function __destruct () {
        if ( $this->mysqli && !$this->mysqli->connect_errno )
            $this->mysqli->close();
    }
    
    public function __construct ($configuration = Array()) {
        parent::__construct($configuration, self::$configuration);
        $this->mysqli = new mysqli($this->server, $this->user, $this->password, $this->db);
    }
}
?>
