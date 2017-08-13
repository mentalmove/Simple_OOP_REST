<?php
/**
 * Home page (and fallback)
 */
class Home extends Controller {
    
    private function get_users () {
        $sql = "SELECT * FROM user";
        return $this->db->select($sql);
    }
    
    /*  */
    
    /**
     * Log out (i.e. set user id to 0)
     */
    function delete () {
        
        $this->auth->update_values(0);
        $this->data['actual_user'] = 0;
        
        $this->view->set_log_zones($this->auth->username);
        $this->render();
    }
    
    /**
     * Log in
     * Checks provided user name against existence;
     * if exists, log in succeeded
     */
    function post () {
        
        if ( $_POST['method'] && $_POST['method'] == "DELETE" ) {
            $this->delete();
            return;
        }
        
        if ( $_POST['nickname'] && trim($_POST['nickname']) ) {
            $nickname = mysql_real_escape_string($_POST['nickname']);
            $sql = "SELECT id, nickname FROM user WHERE nickname = '" . $nickname . "' ORDER BY id DESC LIMIT 1";
            $result = $this->db->select($sql);
            if ( count($result) ) {
                $this->auth->update_values($result[0]['id']);
                $this->view->set_log_zones($this->auth->username);
                $this->data['actual_user'] = $this->auth->user_id;
            }
        }
    
        $this->render();
    }
    
    /**
     * No functionality, just display
     */
    public function get () {
        
        $this->data['actual_user'] = $this->auth->user_id;
        
        $this->render();
    }
    
    /*  */
    
    public function __construct () {
        
        parent::__construct();

        /**
         * Data needed in view files
         */
        $this->data = Array(
            'title'         => "Homepage",
            'headline'      => "Representational State Transfer",
            'base_url'      => BASE_URL,
            'user_list'     => $this->get_users()
        );

        $this->view_files = Array(
            "head",
            "body_start",
            "home",
            "body_end"
        );
        
        /**
         * No users, no log in ;-)
         */
        if ( empty($this->data['user_list']) )
            $this->view->clear_first_log_zone();
    }
}
?>
