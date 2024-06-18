import { IPaginationButton } from './responses';

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
  label: string;
  name: string;
  hidden?: boolean;
  sortable?: boolean;
  fixed?: boolean;
  width?: number;
}
