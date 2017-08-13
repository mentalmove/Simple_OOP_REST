<?php
/**
 * Everything all controllers have in common
 */
class Controller {
    
    protected $db;
    protected $view;
    protected $auth;
    
    protected $view_files = Array();
    protected $data = Array();
    
    public $requested_subpage = "";
    
    
    protected function render () {
        $this->view->render($this->data, $this->view_files);
    }
    
    protected function get_single_user ($id) {
        $sql = "SELECT * FROM user WHERE id = " . $id;
        $result = $this->db->select($sql);
        if ( count($result) )
            return $result[0];
        return NULL;
    }
    
    /**
     * Will hopefully never appear
     */
    public function index () {
        v( "This is a sad substitute if everything else fails" );
        echo "Good bye";
    }
    
    
    protected function __construct () {
        $this->db = new Database();
        $this->auth = new Authentication($this->db);                            // Authentication can use the same database class instance as the controller
        $this->view = new View();
        $this->view->set_log_zones($this->auth->username);
    }
}
?>
