<?php

/**
 * A custom ACF field for Cvent Data
 *
 * @since 0.0.1
 */

namespace Pikari\CventAcfFields\ACF\Fields;

use PHP_CodeSniffer\Tokenizers\JS;

abstract class CventSelect extends \acf_field
{
    /**
     * Environment values relating to the theme or plugin.
     *
     * @var array $env Plugin or theme context such as 'url' and 'version'.
     */
    private $env;

    /**
     * The default field settings
     *
     * @var array
     */
    public $defaults = array(
        'return_format' => 'value',
        'allow_null'    => 0,
        'multiple'      => 1,
        'ui'            => 1,
        'ajax'          => 1,
    );

    /**
     * The endpoint to fetch the data from.
     *
     * @var string
     */
    protected $endpoint = '';

    /**
     * The query parameters to send with the request.
     *
     * @var array
     */
    protected $query_params = array();

    /**
     * Field type label.
     *
     * For public-facing UI. May contain spaces.
     *
     * @var string
     */
    protected $placeholder = '';

    /**
     * Constructor.
     */
    public function __construct()
    {
        /**
         * The category the field appears within in the field type picker.
         * Options are basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
         *
         * @var string
         */
        $this->category = 'choice';


        /**
         * Environment values relating to the theme or plugin.
         *
         * @var array
         */
        $this->env = array(
            'url'     => plugin_dir_url(dirname(__DIR__, 1)) . 'ACF/Fields/', // URL to the acf-FIELD-NAME directory.
            'version' => '1.0', // Replace this with your theme or plugin version constant.
        );

        parent::__construct();
    }

    /**
     * Initialize the field type.
     *
     * This function is called once per field type when ACF is initialized.
     *
     * Use this function to setup actions, filters, and other functionality that will be used by the field type.
     *
     * @return void
     */
    public function initialize()
    {
        $hook_name = 'pikari_cvent_acf_fields/fields/' . $this->name . '/query';

        add_action('wp_ajax_' . $hook_name, array( $this, 'ajax_query' ));
        add_action('wp_ajax_nopriv_' . $hook_name, array( $this, 'ajax_query' ));
    }

    /**
     * Settings to display when users configure a field of this type.
     *
     * These settings appear on the ACF “Edit Field Group” admin page when
     * setting up the field.
     *
     * @param array $field
     * @return void
     */
    public function render_field_settings($field)
    {

        // return_format
        acf_render_field_setting(
            $field,
            array(
                'label'        => __('Return Format', 'acf'),
                'instructions' => __('Specify the value returned', 'acf'),
                'type'         => 'radio',
                'name'         => 'return_format',
                'layout'       => 'horizontal',
                'choices'      => array(
                    'value' => __('Value', 'acf'),
                    'label' => __('Label', 'acf'),
                    'array' => __('Both (Array)', 'acf'),
                ),
            )
        );

        acf_render_field_setting(
            $field,
            array(
                'label'        => __('Select Multiple', 'acf'),
                'instructions' => 'Allow content editors to select multiple values',
                'name'         => 'multiple',
                'type'         => 'true_false',
                'ui'           => 1,
            )
        );

        // To render field settings on other tabs in ACF 6.0+:
        // https://www.advancedcustomfields.com/resources/adding-custom-settings-fields/#moving-field-setting
    }

    /**
     * Renders the field settings used in the "Validation" tab.
     *
     * @since 6.0
     *
     * @param array $field The field settings array.
     * @return void
     */
    public function render_field_validation_settings($field)
    {
        acf_render_field_setting(
            $field,
            array(
                'label'        => __('Allow Null', 'acf'),
                'instructions' => '',
                'name'         => 'allow_null',
                'type'         => 'true_false',
                'ui'           => 1,
            )
        );
    }


    /**
     * Create the HTML interface for your field
     *
     * @param   $field - an array holding all the field's data
     *
     * @type    action
     * @since   3.6
     * @date    23/01/13
     */
    public function render_field($field)
    {

        // Change Field into a select.
        $field['type']        = 'select';
        $field['ui']          = 1;
        $field['ajax']        = 1;
        $field['multiple']    = 1;
        $field['placeholder'] = $this->placeholder;
        $field['choices']     = array();
        $field['ajax_action'] = 'pikari_cvent_acf_fields/fields/' . $this->name . '/query';

        // force value to array
        $field['value'] = acf_get_array($field['value']);

        // load speakers
        $query = $this->getDataById($field['value']);

        if ($query && $query->status === 200 && ! empty($query->data)) {
            foreach ($query->data as $data) {
                $field['choices'][$data['id']] = $this->set_option_text($data);
            }
        }

        acf_render_field($field);
    }

    /**
     * This function will return an array of select data from the REST API
     *
     * @since   0.0.1
     *
     * @param   array $query_params An array of query parameters.
     * @return  \WP_REST_Response
     */
    public function getSelectData(array $query_params = array()): \WP_REST_Response
    {
        // remove empty values from the query params.
        $query_params = array_filter($query_params);

        // $request = new \WP_REST_Request('GET', '/pikari-cvent-acf-fields/v1/speakers');
        $request = new \WP_REST_Request('GET', $this->endpoint);
        $request->set_query_params($query_params);

        $response = rest_do_request($request);

        // Log errors.
        if ($response->status !== 200) {
            error_log('Error: ' . $response->status . ' ' . $request->get_route());
            error_log('Data: ' . print_r($response->data, true));
        }

        // return.
        return $response;
    }

    /**
     * This function will return an array of speakers from the REST API based on the provided IDs.
     *
     * @since   0.0.1
     *
     * @param  array $ids An array of speaker UUIDs.
     * @return  $WP_REST_Response (array)
     */
    public function getDataById(array $ids = array())
    {
        // bail early if no ids.
        if (empty($ids)) {
            return false;
        }

        $request = new \WP_REST_Request('GET', $this->endpoint);

        $query_params = array_merge(
            $this->query_params,
            array(
                'orderby'       => 'include_asc',
                'include'       => $ids,
                'per_page'      => count($ids),
            )
        );

        $request->set_query_params($query_params);

        $response = rest_do_request($request);

        // Log errors.
        if ($response->status !== 200) {
            error_log('Error: ' . $response->status . ' ' . $request->get_route());
            error_log('Data: ' . print_r($response->data, true));
        }

        // return.
        return $response;
    }


    /**
     * description.
     *
     * @type    function
     * @date    24/12/04
     * @since   5.0.0
     *
     * @param   $post_id (int)
     * @return  $post_id (int)
     */

    public function ajax_query()
    {
        $nonce = acf_request_arg('nonce', '');
        $key   = acf_request_arg('field_key', '');

        // Back-compat for field settings.
        if (!acf_is_field_key($key)) {
            $nonce = '';
            $key   = '';
        }

        // validate
        /**
         *  In `acf_verify_ajax()`, the action is expected to be in this format: 'acf_field_' . $this->name . '_' . $key;
         * Because this field is ultimately rendered by the select field class, the nonce action used to create the
         * nonce is tied to 'select' instead of 'multiple_taxonomy'. So, here we set the action and then run our own
         * validation of the nonce.
         */
        $action = 'acf_field_select_' . $key;
        if (! wp_verify_nonce(sanitize_text_field($nonce), $action)) {
            wp_send_json_error();
            die();
        }

        // Modify Request args.
        if (isset($_POST['s'])) {
            $_POST['search'] = sanitize_text_field($_POST['s']);
        }

        // get choices.
        $response = $this->get_ajax_query($_POST);

        // return.
        acf_send_ajax_results($response);
    }


    /**
     * This function will return an array of data formatted for use in a select2 AJAX response
     *
     * @type    function
     * @date    15/10/2014
     * @since   5.0.9
     *
     * @param   $options (array)
     * @return  (array)
     */

    public function get_ajax_query($options = array())
    {
        // defaults.
        $options = acf_parse_args(
            $options,
            array(
                'post_id'   => 0,
                's'         => '',
                'field_key' => '',
                'paged'     => 1,
            )
        );

        // vars.
        $results   = array();
        $args      = array();
        $s         = false;

        // paged.
        $args['per_page'] = 20;
        // Our API uses an offset, not a page number.
        $args['offset']   = $options['paged'] == 1 ? 0 : $options['paged'] * $args['per_page'];

        // search.
        if ($options['search'] !== '') {
            // strip slashes (search may be integer).
            $s = wp_unslash(strval($options['search']));

            // update vars.
            $args['search'] = $s;
        }

        $args = array_merge($this->query_params, $args);

        $selectData = $this->getSelectData($args);

        // bail early if no speakers because WP_REST_RESPONSE status code is anything but 200 or empty data attribute.
        if ($selectData->status !== 200 || empty($selectData->data)) {
            return array(
                'results' => array(),
            );
        }

        // loop.
        foreach ($selectData->data as $data) {
            $results[] = array(
                'id'   => $data['id'],
                'text' => $this->set_option_text($data),
            );
        }


        // return.
        $response = array(
            'results' => $results,
            'limit'  => $args['per_page'],
        );

        return $response;
    }

    /**
     * This function will return the text for the select option.
     *
     * @param array $data The data to use to create the option text.
     * @return string|void
     */
    public function set_option_text(array $data)
    {
        _doing_it_wrong(
            'CventSelect::set_option_text',
            sprintf(__("Method '%s' must be overridden."), __METHOD__),
            '6.5.0'
        );
    }

    /**
     * Enqueues CSS and JavaScript needed by HTML in the render_field() method.
     *
     * Callback for admin_enqueue_script.
     *
     * @return void
     */
    public function input_admin_enqueue_scripts()
    {
        $url     = trailingslashit($this->env['url']);
        $version = $this->env['version'];

        wp_register_script(
            "pikari-cvent-acf-fields/field/{$this->name}",
            "{$url}assets/js/acf-field-{$this->name}.js",
            array( 'acf-input' ),
            $version
        );

        wp_register_style(
            "pikari-cvent-acf-fields/field/{$this->name}",
            "{$url}assets/css/acf-field-{$this->name}.css",
            array( 'acf-input' ),
            $version
        );

        wp_enqueue_script("pikari-cvent-acf-fields/field/{$this->name}");
        wp_enqueue_style("pikari-cvent-acf-fields/field/{$this->name}");
    }
}
