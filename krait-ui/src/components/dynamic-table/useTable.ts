import { reactive, Ref, ref, UnwrapNestedRefs, UnwrapRef } from 'vue';
import { Table, Responses } from '~/types';

interface IState {
  isLoading: Ref<UnwrapRef<boolean>>;
  pagination: UnwrapNestedRefs<Table.IPagination> & {};
  links: Ref<UnwrapRef<Responses.ILinks>> & {};
  columns: Ref<UnwrapRef<Table.IColumn[]>>;
  records: Ref<UnwrapRef<Table.IRow[]>>;
  sorting: UnwrapNestedRefs<Table.ISorting> & {};
  visibleColumns: Ref<UnwrapRef<string[]>>;
}

const tables: Map<string, IState> = new Map();

const getState = (): IState => {
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

export default (tableName: string) => {
  const table = tables.get(tableName);
  if (table) {
    return table;
  }

  const state = getState();
  tables.set(tableName, state);
  return state;
};
