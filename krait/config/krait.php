<?php

return [
    'debug' => false,
    'tables_namespace' => 'App\\Tables\\',
    'tables_directory' => app_path('Tables'),
    'krait_path' => 'krait',
    'tables_path' => 'tables',
    'use_csrf' => true,
    'default_items_per_page' => 30,
    'middleware' => ['web', 'auth'],
    'components_module' => 'components/tables',
];
