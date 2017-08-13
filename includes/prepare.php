<?php
    /**
     * Search for unknown classes in folder 'includes/controllers'
     */
    function find_controller ($classname) {
        $file = __DIR__ . "/controllers/" . strtolower($classname) . ".php";
        if ( file_exists($file) ) {
            include_once $file;
        }
        else
            throw new Exception( $file . "not found; this message will not appear on screen" );
    }

    /**
     * Wrapped in a function
     * to not pollute the global namespace
     * with intermediate results
     */
    function init () {
        
        /**
         * Shall be executed only one
         */
        static $init = 0;
        if ( $init )
            return;
        $init = 1;
        
        /**
         * Data to provide to a mandatory class as static properties
         */
        $configuration = parse_ini_file("includes/configuration/config.ini", TRUE);
        
        /**
         * Ability to read directories
         */
        include_once "includes/helpers/utilities.php";
        
        /**
         * All classes to inherit from must be included
         */
        $parent_classes = Utilities::list_files("includes/parents/");
        for ( $i = 0; $i < count($parent_classes); $i++ )
            include_once "includes/parents/" . $parent_classes[$i];
        
        /**
         * Again: all classes to inherit from must be included
         * Eventual configuration data must be provided to the respective class
         */
        $mandatory_files = Utilities::list_files("includes/mandatory/");
        for ( $i = 0; $i < count($mandatory_files); $i++ ) {
            include_once "includes/mandatory/" . $mandatory_files[$i];
            $namespace = preg_replace("/\.\w+$/", "", $mandatory_files[$i]);
            $classname = ucfirst($namespace);
            if ( isset($configuration[$namespace]) ) {
                if ( is_callable($classname . "::set_basic_values") )
                    call_user_func($classname . "::set_basic_values", $configuration[$namespace]);
            }
        }
        
        /**
         * Where to search for unknown classes
         */
        spl_autoload_register("find_controller");
    }
    
    /**
     * Starting the main loop
     */
    init();
?>
