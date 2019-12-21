<?php
// exit if accessed directly
if (!defined('ABSPATH')) exit;

// check if class already exists
if (!class_exists('acf_field_cities')):

class acf_field_cities extends acf_field {
    // vars
    var $settings, // will hold info such as dir / path
        $defaults; // will hold default field options

    /*
    *  __construct
    *
    *  Set name / label needed for actions / filters
    *
    *  @since   3.6
    *  @date    23/01/13
    */
    function __construct($settings) {
        // vars
        $this->name = 'cities';
        $this->label = __('Estados e Cidades');
        $this->category = __("Basic",'acf'); // Basic, Content, Choice, etc
        $this->defaults = array(
            // add default here to merge into your field.
            // This makes life easy when creating the field options as you don't need to use any if (isset('')) logic. eg:
            //'preview_size' => 'thumbnail'
        );

        // do not delete!
        parent::__construct();

        // settings
        $this->settings = $settings;
    }

    /*
    *  create_field()
    *
    *  Create the HTML interface for your field
    *
    *  @param   $field - an array holding all the field's data
    *
    *  @type    action
    *  @since   3.6
    *  @date    23/01/13
    */
    function create_field($field) {
        // create Field HTML
        ?>
        <ul class="acf-hl" data-cols="3">
            <li>
                <select name="<?php echo esc_attr($field['name']) ?>[country]" data-select="countries" value="<?php echo esc_attr($field['value']['country']) ?>">
                    <option value=""><?php _e('País', 'acf-cities'); ?></option>
                    <?php foreach($field['countries'] as $country): ?>
                    <option value="<?php echo $country->Sigla; ?>" <?php echo ($country->Sigla === $field['value']['country']) ? 'selected' : '' ?>><?php echo $country->Pais; ?></option>
                    <?php endforeach; ?>
                </select>
            </li>

            <li>
                <select name="<?php echo esc_attr($field['name']) ?>[state]" data-select="states" data-value="<?php echo esc_attr($field['value']['state']) ?>">
                    <option value=""><?php _e('Estado', 'acf-cities'); ?></option>
                </select>

                <input <?php echo (isset($field['value']['custom-state']) && $field['value']['custom-state'] == 1) ? 'name="'. esc_attr($field['name']) .'[state]"' : ''; ?> type="text" value="<?php echo esc_attr($field['value']['state']) ?>" placeholder="Digite o estado" class="state-input" <?php echo (!isset($field['value']['custom-state']) || $field['value']['custom-state'] == 0) ? 'style="display: none"' : ''; ?>>

                <label>
                    <input type="hidden" name="<?php echo esc_attr($field['name']) ?>[custom-state]" value="0">
                    <input type="checkbox" name="<?php echo esc_attr($field['name']) ?>[custom-state]" <?php if (isset($field['value']['custom-state'])) checked($field['value']['custom-state'], 1); ?> value="1" class="custom-state-toggler">
                    Não encontrei o estado
                </label>
            </li>

            <li>
                <select name="<?php echo esc_attr($field['name']) ?>[city]" data-select="cities" data-value="<?php echo isset($field['value']['city']) ? esc_attr($field['value']['city']) : ''; ?>" <?php echo (isset($field['value']['custom-city']) && $field['value']['custom-city'] == 1) ? 'style="display: none"' : ''; ?>>
                    <option value=""><?php _e('Cidade', 'acf-cities'); ?></option>
                </select>

                <input <?php echo (isset($field['value']['custom-city']) && $field['value']['custom-city'] == 1) ? 'name="'. esc_attr($field['name']) .'[city]"' : ''; ?> type="text" value="<?php echo esc_attr($field['value']['city']) ?>" placeholder="Digite a cidade" class="city-input" <?php echo (!isset($field['value']['custom-city']) || $field['value']['custom-city'] == 0) ? 'style="display: none"' : ''; ?>>

                <label>
                    <input type="hidden" name="<?php echo esc_attr($field['name']) ?>[custom-city]" value="0">
                    <input type="checkbox" name="<?php echo esc_attr($field['name']) ?>[custom-city]" <?php if (isset($field['value']['custom-city'])) checked($field['value']['custom-city'], 1); ?> value="1" class="custom-city-toggler">
                    Não encontrei a cidade
                </label>
            </li>
        </ul>
        <?php
    }

    /*
    *  input_admin_enqueue_scripts()
    *
    *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
    *  Use this action to add CSS + JavaScript to assist your create_field() action.
    *
    *  $info    http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
    *  @type    action
    *  @since   3.6
    *  @date    23/01/13
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
    *  @since   3.6
    *  @date    23/01/13
    *
    *  @param   $field - the field array holding all the field options
    *
    *  @return  $field - the field array holding all the field options
    */
    function load_field($field) {
        if (($countries = get_transient('acf_cities_countries')) === false) {
            $url = 'http://api.londrinaweb.com.br/PUC/Paisesv2/0/1000';

            $response = wp_remote_get($url);

            // Return nothing if the request returns an error
            if (!is_wp_error($response)) {
                $body = wp_remote_retrieve_body($response);
                $data = json_decode($body);
                $countries = $data;

                set_transient('acf_cities_countries', $countries, 10 * MINUTE_IN_SECONDS);
            }
        }

        $field['countries'] = $countries;
        return $field;
    }
}

// initialize
new acf_field_cities($this->settings);

// class_exists check
endif;
