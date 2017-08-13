<?php
/**
 * Every class inheriting from 'Mandatory' can take basic values statically.
 * In case no configuration data are provided on instantiation,
 * these basic values are taken as default
 */
abstract class Mandatory {

    protected static function set_basic_values ($values = NULL, &$configuration = NULL) {
        
        if ( !$values )
            return;
        
        /**
         * Shall be executed only one
         */
        if ( !empty($configuration) ) {
            foreach ( $configuration as $key => $value ) {
                if ( isset($configuration[$key]) && $value )
                    return;
            }
        }
        
        foreach ( $values as $key => $value ) {
            if ( !isset($configuration[$key]) )
                continue;
            $configuration[$key] = $value;
        }
    }
    
    /**
     * Take provided configuration values if possible.
     * If not, take stored configuration values if possible.
     * 
     * Works individually on every property
     */
    protected function __construct ($given_configuration, $static_configuration) {
        
        if ( !empty($static_configuration) ) {
            foreach ( $static_configuration as $key => $value ) {
                if ( isset($given_configuration[$key]) )
                    $this->$key = $given_configuration[$key];
                else
                    $this->$key = $value;
            }
        }
        elseif ( !empty($given_configuration) ) {
            foreach ( $given_configuration as $key => $value ) {
                if ( property_exists($this, $key) )
                    $this->$key = $given_configuration[$key];
            }
        }
    }
}
?>
