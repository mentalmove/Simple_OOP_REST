<?php
/**
 * Content to be shown on the screen
 * (either included or generated)
 */
class View extends Mandatory {
    
    private static $configuration = Array(
        'folder'        => "",
        'css_url'       => ""
    );
    protected $folder, $css_url;
    
    private $log_zone1 = "";
    private $log_zone2 = "";
    private $welcome = "";
    
    
    public function clear_first_log_zone () {
        $this->log_zone1 = "";
    }
    public function clear_second_log_zone () {
        $this->log_zone2 = "";
    }
    /**
     * To avoid complicated includes...
     * 
     * Username provided: Edit account or log out
     * Username not provided: Create account or log in
     */
    public function set_log_zones ($username) {
        if ( $username ) {
            $this->log_zone1 = "<a href='" . BASE_URL . "account'>Edit account</a>";
            
            $this->log_zone2 = "<form method='POST' action='" . BASE_URL . "'>";
            $this->log_zone2 .= "<input type='hidden' name='method' value='DELETE'>";
            $this->log_zone2 .= "<a href='' onclick='this.parentNode.submit(); return false'>Log out</a>";
            $this->log_zone2 .= "</form>";
            
            $this->welcome = "<b>Hello, " . $username . "!</b>";
        }
        else {
            $this->log_zone1 = "<form method='POST' action='" . BASE_URL . "'>";
            $this->log_zone1 .= "<a href='' onclick='this.parentNode.submit(); return false'>Log in</a>";
            $this->log_zone1 .= "&nbsp;&nbsp;";
            $this->log_zone1 .= "<input type='text' name='nickname' size='16' maxlength='40'>";
            $this->log_zone1 .= "</form>";
            
            $this->log_zone2 = "<a href='" . BASE_URL . "account'>Create account</a>";
            
            if ( $this->welcome )
                $this->welcome = str_replace("Hello", "Good bye", $this->welcome);
        }
    }
    
    
    /**
     * Provides '$data' - properties to global namespace.
     * Includes all necessary files from folder 'includes/views'
     */
    public function render ($data, $files) {
        
        $data['css_url'] = $this->css_url;
        $data['log_zone1'] = $this->log_zone1;
        $data['log_zone2'] = $this->log_zone2;
        $data['welcome'] = $this->welcome;
        extract($data);
        
        for ( $i = 0; $i < count($files); $i++ ) {
            $file = $this->folder . $files[$i] . ".php";
            if ( file_exists($file) )
                include $file;
        }
    }
    
    
    public static function set_basic_values ($values = NULL) {
        parent::set_basic_values($values, self::$configuration);
    }
    
    
    public function __construct ($configuration = Array()) {
        parent::__construct($configuration, self::$configuration);
        $this->css_url = BASE_URL . $this->css_url;
    }
}
?>
