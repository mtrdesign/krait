<?php

return [
    'debug' => false,
    'tables_directory' => app_path('Tables'),



    'table_definition_classes_directory' => app_path('Tables'),
    'table_controllers_directory' => app_path('Http/Controllers/Tables'),
    'table_components_directory' => resource_path('js/components/tables'),

    'krait_path' => 'krait',
    'tables_path' => 'tables',
    'use_csrf' => true,
    'default_items_per_page' => 30,
    'middleware' => ['web', 'auth'],
    'components_module' => 'components/tables',
    'date_format' => 'm/d/Y, g:i a',
];
