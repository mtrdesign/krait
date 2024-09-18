import { reactive, ref } from 'vue';
import { Table, Responses } from '~/types';

export const tables: Map<string, Table.ITableContext> = new Map();

/**
 * Generates a new fresh table state.
 *
 * @return {Table.ITableContext} - The fresh table state.
 */
const getState = (): Table.ITableContext => {
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
  const isAuthorized = ref<boolean>(true);
  const queryParameters = ref<Table.IQueryParameters>({});
  const isSelectableRows = ref<boolean>(false);
  const bulkActionLinks = ref<{ [key: string]: string }>({});
  const selectedRows = ref<string[] | number[]>([]);

  return {
    columns,
    visibleColumns,
    isLoading,
    sorting,
    pagination,
    records,
    links,
    isAuthorized,
    queryParameters,
    isSelectableRows,
    bulkActionLinks,
    selectedRows,
  };
};

/**
 * Returns the table state if it's already initialized
 * or a new fresh state.
 *
 * @param {string} tableName - The table name.
 *
 * @return {Table.ITableContext} - The table state.
 */
const useTable = (tableName: string): Table.ITableContext => {
  const table = tables.get(tableName);
  if (table) {
    return table;
  }

  const state = getState();
  tables.set(tableName, state);
  return state;
};

export default useTable;
