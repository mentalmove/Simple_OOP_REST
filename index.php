<?php
    /**
     * Human-readable vardump
     * An invention of O. Schwarten, osdata.org
     * 
     * Debugging purpose only
     */
    function v ($mixed) {
	echo "<pre>";
	print_r($mixed);
	echo "</pre>";
    }
    
    
    include_once "includes/prepare.php";                                        // All basic data needed for every call
    
    $method = $_SERVER['REQUEST_METHOD'];                                       // GET, POST, PUT, DELETE - will invoke methods get(), post(), put(), delete()
    
    $page_components = explode("/", trim($_SERVER['PATH_INFO'], "/"));                  // Url translated to information
    $requested_page = $page_components[0];                                              // First url part is file resp. class name
    $requested_subpage = (count($page_components) > 1 ) ? $page_components[1] : "";     // Second url part needed in class 'User'
                                                                                        // Further url parts unrecognised but harmless
    
    $page = ($requested_page) ? $requested_page : "home";
    $class_name = ucfirst($page);
    try {
        $control = new $class_name();                                           // First guess: The wanted class exists
    } catch (Exception $e) {
        //echo $e->getMessage();                                                // Debugging purpose only
        $requested_subpage = "";
        $method = "GET";
        $control = new Home();                                                  // Assumption: 'home.php' with class 'Home' always exists; can be taken as fallback
    }
    
    $control->requested_subpage = $requested_subpage;                           // All controller classes inherit from 'Controller', therefore property 'requested_subpage' always exists
    
    $object_method = strtolower($method);
    if ( is_callable( Array($control, $object_method) ) ) {
        $control->$object_method();
    }
    else
        $control->index();                                                      // All controller classes inherit from 'Controller', therefore 'index()' always exists
?>
