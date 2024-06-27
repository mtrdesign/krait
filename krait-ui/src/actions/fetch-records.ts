import { Table, Responses } from '~/types';
import { Config, ApiClient } from '~/framework';
import BaseAction from './base-action';

interface IFetchRecordsOptions {
  filtersForm?: HTMLFormElement;
  isInitialFetch?: boolean;
  url?: string;
}

interface IFetchRecordsResult {
  success: boolean;
}

export default class FetchRecords extends BaseAction<
  IFetchRecordsOptions,
  IFetchRecordsResult
> {
  private isInitialFetch: boolean = false;

  async process(options: IFetchRecordsOptions) {
    if (options.isInitialFetch) {
      this.isInitialFetch = options.isInitialFetch;
    }

    let url: URL;
    if (options.url) {
      url = new URL(options.url);
    } else {
      url = this.generateUrl(options);
    }

    const response = await ApiClient.fetch(url);
    await this.parseResponse(response);

    const currentUrl = new URL(window.location.href);
    const historyState = `${currentUrl.pathname}${url.search}`;
    history.replaceState({}, '', historyState);

    return {
      success: true,
    };
  }

  private setFilters(form: HTMLFormElement, url: URL): void {
    const filtersForm = new FormData(form);
    for (const [name, value] of filtersForm) {
      if (value instanceof File || value === '') {
        continue;
      }
      url.searchParams.append(name, value);
    }
  }

  private setSorting(sorting: Table.ISorting, url: URL): void {
    if (sorting.sortBy) {
      url.searchParams.set('sort_column', sorting.sortBy);
    }

    if (sorting.direction) {
      url.searchParams.set('sort_direction', sorting.direction);
    }
  }

  private setPagination(pagination: Table.IPagination, url: URL): void {
    if (pagination.itemsPerPage) {
      url.searchParams.set('ipp', pagination.itemsPerPage.toString());
    }

    if (pagination.currentPage) {
      url.searchParams.set('page', pagination.currentPage.toString());
    }
  }

  private async parseResponse(response: Response) {
    const { data, meta, columns, preview_configuration, links } =
      (await response.json()) as Responses.ITableResponse;

    this.context.records.value = data;
    this.context.pagination.currentPage = meta.current_page;
    this.context.pagination.itemsPerPage = meta.per_page;
    this.context.pagination.totalItems = meta.total;
    this.context.pagination.links = meta.links;

    if (this.isInitialFetch) {
      this.context.columns.value = columns;

      if (preview_configuration?.visible_columns) {
        this.context.visibleColumns.value =
          preview_configuration?.visible_columns;
      } else {
        this.context.visibleColumns.value = columns.map(
          (column) => column.name,
        );
      }

      if (preview_configuration && this.context.sorting.sortBy === null && this.context.sorting.direction === null) {
        this.context.sorting.sortBy = preview_configuration.sort_column;
        this.context.sorting.direction = preview_configuration.sort_direction;
      }
    }

    this.context.links.value = links;
  }

  private generateUrl(options: IFetchRecordsOptions): URL {
    const url = Config.tablesUrl;
    url.pathname = `${url.pathname}/${this.tableName}`;

    if (options.filtersForm) {
      this.setFilters(options.filtersForm, url);
    }
    this.setSorting(this.context.sorting, url);
    this.setPagination(this.context.pagination, url);

    return url;
  }
}
