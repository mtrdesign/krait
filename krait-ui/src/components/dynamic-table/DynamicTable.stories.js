import DynamicTable from './DynamicTable.vue';

export const ActionsData = {
  tableName: 'test',
  apiEndpoint: '/records',
};

export default {
  component: DynamicTable,
  title: 'DynamicTable',
  tags: ['autodocs'],
  excludeStories: /.*Data$/,
  args: {
    ...ActionsData,
  },
};

export const Default = {
  args: {
    ...ActionsData,
  },
};
