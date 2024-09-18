import { IColumn, IRow } from './table';

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
  sort_column: string | null;
  sort_direction: string | null;
  columns_order: string[] | null;
  columns_width: { [key: string]: number } | null;
  visible_columns: string[] | null;
}

export interface ITableResponse {
  data: IRow[];
  links: ILinks;
  meta: IMeta;
  preview_configuration: IPreviewConfiguration | null;
  columns: IColumn[];
  selectable_rows: boolean;
  bulk_action_links: { [key: string]: string };
}
