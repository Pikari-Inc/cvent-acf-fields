<?php

/**
 * A custom ACF field for selecting exhibitors
 *
 * @since 0.0.1
 */

namespace Pikari\CventAcfFields\ACF\Fields;

class CventExhibitorsSelect extends CventSelect
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'exhibitor_select';

        $this->label = __('CVENT Exhibitors Select', 'pikari-cvent-acf-fields');

        $this->description = __('Description goes here', 'pikari-cvent-acf-fields');

        $this->endpoint = '/cvent-blocks/v1/exhibitors';

        $this->placeholder = __('Select an exhibitor/sponsor', 'pikari-cvent-acf-fields');

        $this->query_params = [
            '_fields'        => 'id,name',
            'orderby'        => 'name_asc',
            'search_columns' => ['name'],
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
        return $data['name'];
    }
}
