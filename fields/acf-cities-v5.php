<?php

// exit if accessed directly
if(!defined('ABSPATH')) exit;


// check if class already exists
if(!class_exists('acf_field_cities')) :


class acf_field_cities extends acf_field {

    /*
    *  __construct
    *
    *  This function will setup the field type data
    *
    *  @type    function
    *  @date    5/03/2014
    *  @since   5.0.0
    *
    *  @param   n/a
    *  @return  n/a
    */
    function __construct($settings) {
        $this->name = 'cities';
        $this->label = __('Estados e Cidades', 'acf-cities');
        $this->category = 'basic';

        /*
        *  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
        */
        $this->settings = $settings;

        // do not delete!
        parent::__construct();
    }

    /*
    *  render_field()
    *
    *  Create the HTML interface for your field
    *
    *  @param   $field (array) the $field being rendered
    *
    *  @type    action
    *  @since   3.6
    *  @date    23/01/13
    *
    *  @param   $field (array) the $field being edited
    *  @return  n/a
    */
    function render_field($field) {
        ?>
        <ul class="acf-hl" data-cols="3">
            <li>
                <select name="<?php echo esc_attr($field['name']) ?>[state]" data-select="states">
                    <option value=""><?php _e('Estado', 'acf-cities'); ?></option>
                    <?php foreach ($field['estados'] as $estado): ?>
                        <option value="<?php echo esc_attr($estado['sigla']); ?>" <?php selected($estado['sigla'], $field['value']['state']); ?>>
                            <?php echo esc_html($estado['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </li>
    
            <li>
                <select name="<?php echo esc_attr($field['name']) ?>[city]" data-select="cities">
                    <option value=""><?php _e('Cidade', 'acf-cities'); ?></option>
                    <!-- As cidades serão carregadas via JavaScript -->
                </select>
            </li>
        </ul>
        <?php
    }
    

    /*
    *  input_admin_enqueue_scripts()
    *
    *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
    *  Use this action to add CSS + JavaScript to assist your render_field() action.
    *
    *  @type    action (admin_enqueue_scripts)
    *  @since   3.6
    *  @date    23/01/13
    *
    *  @param   n/a
    *  @return  n/a
    */
    function input_admin_enqueue_scripts() {
        // vars
        $url = $this->settings['url'];
        $version = $this->settings['version'];

        // register & include JS
        wp_register_script('acf-input-cities', "{$url}assets/js/input.js", array('acf-input'), $version);
        wp_enqueue_script('acf-input-cities');
    }

    /*
    *  load_field()
    *
    *  This filter is applied to the $field after it is loaded from the database
    *
    *  @type    filter
    *  @date    23/01/2013
    *  @since   3.6.0
    *
    *  @param   $field (array) the field array holding all the field options
    *  @return  $field
    */
    function load_field($field) {
        $file_path = WP_PLUGIN_DIR . '/acf-cities-master/data/estados-cidades.json';
    
        if (file_exists($file_path)) {
            $data = file_get_contents($file_path);
            $estados = json_decode($data, true)['estados'];
        } else {
            $estados = array();
        }
    
        $field['estados'] = $estados;
    
        return $field;
    }
    
    
}

// initialize
new acf_field_cities($this->settings);

// class_exists check
endif;
