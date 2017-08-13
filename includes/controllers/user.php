<?php
/**
 * Display user data;
 * when logged in: write messages, delete recieved spam
 */
class User extends Controller {
    
    
    /**
     * All messages sent to a distinct user
     */
    private function get_messages ($id) {
        $sql = "SELECT "
                    . "m.*,"
                    . "u.nickname "
                . "FROM "
                    . "message m, "
                    . "user u "
                . "WHERE "
                    . "m.recipient = " . $id . " "
                . "AND "
                    . "m.sender = u.id "
                . "ORDER BY m.id ASC";
        return $this->db->select($sql);
    }
    
    /**
     * Cannot be done in the constructor because
     *      - page owner is not knowm at that point
     *      - eventual changes are not knowm at that point
     */
    private function post_construct () {
        
        $id = (int) $this->requested_subpage;                                   // user id; user name as URL part is not recognised
        $userdata = $this->get_single_user($id);
        
        $this->data['title'] = "Page of " . $userdata['nickname'];
        $this->data['userdata'] = $userdata;
        $this->data['logged_in'] = ($this->auth->user_id) ? TRUE : FALSE;
        $this->data['its_me'] = ($this->auth->user_id == $id);
        $this->data['msg_url'] = $_SERVER['REQUEST_URI'];                       // the actual URL
        $this->data['messages'] = $this->get_messages($id);
        $this->data['user_id'] = $this->auth->user_id;
    }
    
    /*  */
    
    /**
     * A logged-in recipient may delete messages
     */
    public function delete () {
        
        $id = (int) $_POST['id'];
        
        $sql = "DELETE FROM message WHERE id = " . $id . " AND recipient = " . $this->auth->user_id;
        $unchecked = $this->db->execute($sql);
        
        $this->post_construct();
        
        $this->render();
    }
    
    /**
     * Every logged-in user may send messages, but not to him- (or her-) self
     */
    public function post () {
        
        if ( $_POST['method'] && $_POST['method'] == "DELETE" ) {
            $this->delete();
            return;
        }
        
        $recipient = (int) $this->requested_subpage;
        $sender = $this->auth->user_id;
        
        /**
         * No message, no action
         */
        if ( !$_POST['msg'] || !trim($_POST['msg']) ) {
            $this->get();
            return;
        }
        
        $limit = 255;                                                           // Maximum of allowed characters; could be changed in the database
        
        $msg = trim($_POST['msg']);
        $messages = Array();
        if ( strlen($msg) <= $limit )
            $messages[0] = $msg;
        /**
         * Longer messages will be split up...
         */
        else {
            $tmp_messages = preg_split("/\s/", $msg);
            $counter = 0;
            $messages[0] = "";
            for ( $i = 0; $i < count($tmp_messages); $i++ ) {
                if ( !$tmp_messages[$i] )
                    continue;
                if ( strlen($tmp_messages[$i]) > $limit )
                    $tmp_messages[$i] = substr($tmp_messages[$i], 0, ($limit - 3)) . "...";
                /**
                 * ...preferable after a whitespace
                 */
                if ( strlen($messages[$counter]) + strlen($tmp_messages[$i]) < $limit )
                    $messages[$counter] .= " " . $tmp_messages[$i];
                else
                    $messages[++$counter] = $tmp_messages[$i];
            }
        }
        for ( $i = 0; $i < count($messages); $i++ ) {
            $sql = "INSERT INTO message (sender, recipient, msg) VALUES(" . $sender . ", " . $recipient . ", '" . mysql_real_escape_string($messages[$i]) . "')";
            $unchecked = $this->db->execute($sql);
        }
        
        /**
         * To be called after insertion of the new message
         */
        $this->post_construct();
        
        $this->render();
    }
    
    /**
     * No functionality, just display data of a distinct user
     */
    public function get () {
        
        $this->post_construct();
        /**
         * 'Edit account' of a foreign user would look strange
         */
        if ( $this->data['logged_in'] && !$this->data['its_me'] )
            $this->view->clear_first_log_zone();
        
        $this->render();
    }
    
    /*  */
    
    public function __construct () {
        
        parent::__construct();

        $this->data = Array(
            'headline'      => "Representational State Transfer",
            'base_url'      => BASE_URL
        );

        $this->view_files = Array(
            "head",
            "body_start",
            "user",
            "body_end"
        );
    }
}
?>
