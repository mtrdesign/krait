import { reactive, ref } from 'vue';
import { Table, Responses } from '~/types';

export const tables: Map<string, Table.ITableContext> = new Map();

const getState = (tableName: string): Table.ITableContext => {
  const currentURL = new URL(window.location.href);

  const columns = ref<Table.IColumn[]>([]);
  const visibleColumns = ref<string[]>([]);
  const sorting = reactive<Table.ISorting>({
    sortBy: currentURL.searchParams.get('sort_column'),
    direction: currentURL.searchParams.get('sort_direction'),
  });

  const ipp = currentURL.searchParams.get('ipp');
  const pagination = reactive<Table.IPagination>({
    itemsPerPage: ipp ? parseInt(ipp) : null,
    currentPage: null,
    totalItems: 0,
    links: [],
  });
  const records = ref<Table.IRow[]>([]);
  const isLoading = ref<boolean>(false);
  const links = ref<Responses.ILinks>({});

  return {
    columns,
    visibleColumns,
    isLoading,
    sorting,
    pagination,
    records,
    links,
  };
};

export default (tableName: string): Table.ITableContext => {
  const table = tables.get(tableName);
  if (table) {
    return table;
  }

  const state = getState(tableName);
  tables.set(tableName, state);
  return state;
};
