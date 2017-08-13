<?php
/**
 * Create or edit account
 */
class Account extends Controller {

    /**
     * Edit existing account if data different from existing ones are provided
     * Switch to user page on success
     */
    public function put () {
        
        $id = (int) $_POST['id'];
        
        if ( !$id ) {
            $this->get();
            return;
        }
        
        $old_data = $this->get_single_user($id);
        if ( !$old_data ) {
            $this->auth->update_values(0);
            header("Location: " . BASE_URL);
            return;
        }
        
        $data = Array();
        $tmp_colour = trim($_POST['colour']);
        $tmp_pet = trim($_POST['pet']);
        if ( $tmp_colour && $tmp_colour != $old_data[0]['colour'] )
            $data['colour'] = mysql_real_escape_string($tmp_colour);
        if ( $tmp_pet && $tmp_pet != $old_data[0]['pet'] )
            $data['pet'] = mysql_real_escape_string($tmp_pet);
        
        if ( count($data) ) {
            $sql = "UPDATE user SET ";
            $started = 0;
            foreach ( $data as $key => $value ) {
                if ( $started )
                    $sql .= ", ";
                $started = 1;
                $sql .= $key . " = '" . $value . "'";
            }
            $sql .= " WHERE id = " . $id;
            $success = $this->db->execute($sql);
            if ( !$success ) {
                $this->get();
                return;
            }
        }
        
        $location = BASE_URL . "user/" . $id . "/" . preg_replace("/\W+/", "", $result[0]['nickname']);
        header("Location: $location");
    }
    
    /**
     * Create new account if at least 'nickname' is provided
     * Switch to user page on success
     */
    public function post () {
        
        if ( $_POST['method'] && $_POST['method'] == "PUT" ) {
            $this->put();
            return;
        }
        
        if ( !$_POST['nickname'] || !trim($_POST['nickname']) ) {
            $this->get();
            return;
        }
        
        $data = Array(
            'nickname'      => mysql_real_escape_string(trim($_POST['nickname'])),
            'colour'        => mysql_real_escape_string(trim($_POST['colour'])),
            'pet'           => mysql_real_escape_string(trim($_POST['pet']))
        );
        $sql = "INSERT INTO user (nickname, colour, pet) VALUES('" . implode("', '", $data) . "')";
        $user_id = $this->db->execute($sql, TRUE);
        
        if ( !$user_id ) {
            $this->get();
            return;
        }
        $this->auth->update_values($user_id);
        $location = BASE_URL . "user/" . $user_id . "/" . preg_replace("/\W+/", "", trim($_POST['nickname']));
        header("Location: $location");
    }
    
    /**
     * Form data - Taken from database when logged in (edit),
     * empty when not logged in (create)
     */
    public function get () {
        
        if ( $this->auth->user_id ) {
            $this->data['userdata'] = $this->get_single_user($this->auth->user_id);
            if ( !$this->data['userdata'] ) {
                $this->auth->update_values(0);
                header("Location: " . BASE_URL);
                return;
            }
            $this->data['title'] = "Edit account";
            $this->data['method'] = "PUT";
            $this->data['name_disabled'] = "disabled";
        }
        else {
            $this->data['title'] = "Create account";
            $this->data['method'] = "POST";
            $this->data['userdata'] = Array(
                'id'            => 0,
                'nickname'      => "",
                'colour'        => "",
                'pet'           => ""
            );
            $this->data['name_disabled'] = "";
        }
        
        $this->render();
    }
    
    /*  */
    
    public function __construct () {
        
        parent::__construct();

        $this->data = Array(
            'base_url'      => BASE_URL,
            'headline'      => "<small>Cancel</small>",                         // Cancel button is more useful than title here
            'id'            => $this->auth->user_id
        );

        $this->view_files = Array(
            "head",
            "body_start",
            "account",
            "body_end"
        );
        
        /**
         * No invitation to create or edit - we are already there
         */
        $this->view->clear_first_log_zone();
        $this->view->clear_second_log_zone();
    }
}
?>
