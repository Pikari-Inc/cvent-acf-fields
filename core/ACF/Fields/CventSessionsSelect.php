<?php

/**
 * A custom ACF field for selecting sessions
 *
 * @since 0.0.1
 */

namespace Pikari\CventAcfFields\ACF\Fields;

class CventSessionsSelect extends CventSelect
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'session_select';

        $this->label = __('CVENT Sessions Select', 'pikari-cvent-acf-fields');

        $this->description = __('Description goes here', 'pikari-cvent-acf-fields');

        $this->endpoint = '/cvent-blocks/v1/sessions';

        $this->placeholder = __('Select a session', 'pikari-cvent-acf-fields');

        $this->query_params = [
            '_fields'        => 'id,title',
            'orderby'        => 'title_asc',
            'search_columns' => ['title'],
        ];

        parent::__construct();
    }

    /**
     * Set the option text for the field.
     *
     * @param array $data The data for the option.
     *
     * @return string
     */
    public function set_option_text(array $data): string
    {
        return $data['title'];
    }
}
