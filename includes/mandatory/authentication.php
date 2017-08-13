<?php
/**
 * No security mechanisms at all.
 * Just checks provided user id against existence in database
 */
class Authentication extends Mandatory {
    
    private $db;
    
    public $user_id, $username;
    
    
    public function update_values ($user_id) {
        if ( !$user_id ) {
            $this->user_id = 0;
            $this->username = "";
            $_SESSION['user_id'] = 0;
        }
        else {
            $sql = "SELECT nickname FROM user WHERE id = " . $user_id;
            $result = $this->db->select($sql);
            if ( empty($result) ) {
                $this->update_values(0);
                return;
            }
            $this->username = $result[0]['nickname'];
            $this->user_id = (int) $user_id;
            $_SESSION['user_id'] = $user_id;
        }
    }
    
    
    public function __construct ($db) {

        $this->db = $db;
        
        /**
         * User id to be remembered on page change
         */
        session_start();
        $session_user_id = ($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
        $this->update_values($session_user_id);
    }
}
?>
