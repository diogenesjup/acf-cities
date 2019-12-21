<?php
/*
Plugin Name: Advanced Custom Fields: Estados e Cidades
Description: Tipo de campo para o ACF com cidades e estados brasileiros
Version: 1.0.0
Author: Carlos Fernandes Cunha
Author URI: https://carlosfernand.es
*/

// exit if accessed directly
if (!defined('ABSPATH')) exit;


// check if class already exists
if (!class_exists('acf_plugin_cities')) :

class acf_plugin_cities {
    /*
    *  __construct
    *
    *  This function will setup the class functionality
    *
    *  @type    function
    *  @date    17/02/2016
    *  @since   1.0.0
    *
    *  @param   n/a
    *  @return  n/a
    */
    function __construct() {
        // vars
        $this->settings = array(
            'version'   => '1.0.0',
            'url'       => plugin_dir_url(__FILE__),
            'path'      => plugin_dir_path(__FILE__)
        );

        // include field
        add_action('acf/include_field_types',   array($this, 'include_field_types')); // v5
        add_action('acf/register_fields',       array($this, 'include_field_types')); // v4
    }

    /*
    *  include_field_types
    *
    *  This function will include the field type class
    *
    *  @type    function
    *  @date    17/02/2016
    *  @since   1.0.0
    *
    *  @param   $version (int) major ACF version. Defaults to false
    *  @return  n/a
    */
    function include_field_types($version = false) {
        // support empty $version
        if (!$version) $version = 4;

        // include
        include_once('fields/acf-cities-v' . $version . '.php');
    }
}

// initialize
new acf_plugin_cities();

// class_exists check
endif;
