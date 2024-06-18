import { IRow } from './table';

export interface ILinks {
  [key: string]: string | null;
}

export interface IPaginationButton {
  url: string | null;
  label: string;
  active: boolean;
}

export interface IMeta {
  current_page: number;
  from: number;
  last_page: number;
  links: IPaginationButton[];
  path: string;
  per_page: number;
  to: number;
  total: number;
}

export interface ISortColumnsBy {
  ipp: number;
  column: string;
  direction: string;
}

export interface IPreviewConfiguration {
  uuid: string;
  visible_columns: string[];
  display_order_settings: string[];
  sort_columns_by: ISortColumnsBy;
}

export interface IColumn {
  label: string;
  name: string;
  hidden?: boolean | undefined;
  sortable?: boolean | undefined;
  fixed?: boolean | undefined;
  width?: number;
}

export interface ITableResponse {
  data: IRow[];
  links: ILinks;
  meta: IMeta;
  preview_configuration: IPreviewConfiguration;
  columns: IColumn[];
}
