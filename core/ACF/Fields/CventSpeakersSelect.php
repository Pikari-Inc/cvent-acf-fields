<?php

/**
 * A custom ACF field for selecting speakers
 *
 * @since 0.0.1
 */

namespace Pikari\CventAcfFields\ACF\Fields;

class CventSpeakersSelect extends CventSelect
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'speaker_select';

        $this->label = __('CVENT Speakers Select', 'pikari-cvent-acf-fields');

        $this->description = __('Description goes here', 'pikari-cvent-acf-fields');

        $this->endpoint = '/cvent-blocks/v1/speakers';

        $this->placeholder = __('Select a speaker', 'pikari-cvent-acf-fields');

        $this->query_params = [
            '_fields'        => 'id,firstName,lastName',
            'orderby'        => 'lastName_asc',
            'search_columns' => ['firstName', 'lastName'],
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
        return $data['lastName'] . ', ' . $data['firstName'];
    }
}
