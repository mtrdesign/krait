import { reactive, Ref, ref, UnwrapNestedRefs, UnwrapRef } from 'vue';
import { Table, Responses } from '~/types';
import { KraitUrls } from '~/api';

interface IState {
  isLoading: Ref<UnwrapRef<boolean>>;
  pagination: UnwrapNestedRefs<Table.IPagination> & {};
  links: Ref<UnwrapRef<Responses.ILinks>> & {};
  columns: Ref<UnwrapRef<Table.IColumn[]>>;
  records: Ref<UnwrapRef<Table.IRow[]>>;
  sorting: UnwrapNestedRefs<Table.ISorting> & {};
  visibleColumns: Ref<UnwrapRef<string[]>>;
  urls: KraitUrls.ITableUrls;
}

const tables: Map<string, IState> = new Map();

const getState = (tableName: string): IState => {
  const columns = ref<Table.IColumn[]>([]);
  const visibleColumns = ref<string[]>([]);
  const sorting = reactive<Table.ISorting>({
    sortBy: null,
    direction: null,
  });
  const pagination = reactive<Table.IPagination>({
    itemsPerPage: null,
    currentPage: null,
    totalItems: 0,
    links: [],
  });
  const records = ref<Table.IRow[]>([]);
  const isLoading = ref<boolean>(false);
  const links = ref<Responses.ILinks>({});
  const urls = KraitUrls.getTableUrls(tableName);

  return {
    columns,
    visibleColumns,
    isLoading,
    sorting,
    pagination,
    records,
    links,
    urls,
  };
};

export default (tableName: string) => {
  const table = tables.get(tableName);
  if (table) {
    return table;
  }

  const state = getState(tableName);
  tables.set(tableName, state);
  return state;
};
