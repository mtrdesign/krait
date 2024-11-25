import { createColumn, createRandomCat } from './mocks.js';
import { faker } from '@faker-js/faker';

// Creating 100 cats 🐱
const cats = faker.helpers.multiple(createRandomCat, {
  count: 100,
});

// Creating the initial table structure
const tableStructure = {
  data: {
    preview_configuration: {
      uuid: '3b8dbcca-ee7f-41b9-bc20-2a5ab4e855fe',
      table_name: 'cats-table',
      sort_column: null,
      sort_direction: null,
      columns_order: null,
      columns_width: null,
      visible_columns: [
        'uuid',
        'first_name',
        'last_name',
        'username',
        'breed',
        'email',
        'avatar',
        'birthdate',
        'created_at',
      ],
    },
    columns: [
      createColumn({ name: 'uuid', label: 'ID' }),
      createColumn({ name: 'first_name', label: 'First Name' }),
      createColumn({ name: 'last_name', label: 'Last Name' }),
      createColumn({ name: 'username', label: 'Username' }),
      createColumn({ name: 'breed', label: 'Breed' }),
      createColumn({ name: 'email', label: 'Email' }),
      createColumn({ name: 'avatar', label: 'Avatar' }),
      createColumn({ name: 'birthdate', label: 'Birthdate' }),
      createColumn({ name: 'created_at', label: 'Created on' }),
    ],
    selectable_rows: false,
    bulk_action_links: [],
  },
};

export { cats, tableStructure };
