import { ILinks, IPaginationButton } from './responses';
import { Ref, UnwrapNestedRefs, UnwrapRef } from 'vue';

export interface ISorting {
  sortBy: string | null;
  direction: string | null;
}

export interface IRow {
  [key: string]: string | number | boolean | IRow;
}

export interface IPagination {
  itemsPerPage: number | null;
  totalItems: number | null;
  currentPage: number | null;
  links: IPaginationButton[];
}

export interface IColumn {
  name: string;
  label: string;
  hideLabel: boolean;
  datetime: boolean;
  sortable: boolean;
  fixed: boolean;
  classes: string | null;
  width?: number;
}

export interface IQueryParameters {
  [key: string]: string | number;
}

export interface ITableContext {
  isLoading: Ref<UnwrapRef<boolean>>;
  pagination: UnwrapNestedRefs<IPagination> & {};
  links: Ref<UnwrapRef<ILinks>> & {};
  columns: Ref<UnwrapRef<IColumn[]>>;
  records: Ref<UnwrapRef<IRow[]>>;
  sorting: UnwrapNestedRefs<ISorting> & {};
  visibleColumns: Ref<UnwrapRef<string[]>>;
  isAuthorized: Ref<boolean>;
  queryParameters: Ref<IQueryParameters>;
}

export interface ITableConfiguration {
  filtersForm: string;
  actionsColumn: boolean;
  apiQueryParameters: any;
  apiEndpoint: string;
}
