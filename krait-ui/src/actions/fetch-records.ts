import { Table, Responses } from '~/types';
import { Config, ApiClient } from '~/framework';
import BaseAction from './base-action';
import { UnauthorizedError } from '~/framework/exceptions';

interface IFetchRecordsOptions {
  filtersForm?: HTMLFormElement;
  isInitialFetch?: boolean;
  url?: string;
}

interface IFetchRecordsResult {
  success: boolean;
}

/**
 * FetchRecords Action
 * Fetches all table records and updates the table context.
 *
 * @class
 * @extends BaseAction
 */
export default class FetchRecords extends BaseAction<
  IFetchRecordsOptions,
  IFetchRecordsResult
> {
  private isInitialFetch: boolean = false;

  /**
   * Prepares the URL and fetches the records.
   *
   * @param {IFetchRecordsOptions} options - The column options.
   */
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

    try {
      const response = await ApiClient.fetch(url);
      await this.parseResponse(response);
    } catch (error) {
      if (error instanceof UnauthorizedError) {
        this.context.isAuthorized.value = false;
        return {
          success: true,
        };
      }

      throw error;
    }

    const currentUrl = new URL(window.location.href);
    const historyState = `${currentUrl.pathname}${url.search}`;
    history.replaceState({}, '', historyState);

    return {
      success: true,
    };
  }

  /**
   * Sets the filters query to the request url using
   * the passed form.
   *
   * @param {HTMLFormElement} form - The filters form element.
   * @param {URL} url - The request url
   * @private
   */
  private setFilters(form: HTMLFormElement, url: URL): void {
    const filtersForm = new FormData(form);
    for (const [name, value] of filtersForm) {
      if (value instanceof File || value === '') {
        continue;
      }
      url.searchParams.append(name, value);
    }
  }

  /**
   * Sets the sorting query to the request url.
   *
   * @param {Table.ISorting} sorting - The sorting data.
   * @param {URL} url - The request url.
   * @private
   */
  private setSorting(sorting: Table.ISorting, url: URL): void {
    if (sorting.sortBy) {
      url.searchParams.set('sort_column', sorting.sortBy);
    }

    if (sorting.direction) {
      url.searchParams.set('sort_direction', sorting.direction);
    }
  }

  /**
   * Sets the pagination query to the request url.
   *
   * @param {Table.IPagination} pagination - The Pagination data.
   * @param {URL} url - The request url.
   * @private
   */
  private setPagination(pagination: Table.IPagination, url: URL): void {
    if (pagination.itemsPerPage) {
      url.searchParams.set('ipp', pagination.itemsPerPage.toString());
    }

    if (pagination.currentPage) {
      url.searchParams.set('page', pagination.currentPage.toString());
    }
  }

  /**
   * Parses the back-end response and updates the table state.
   *
   * @param {Response} response - The back-end response.
   * @private
   */
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

      // @TODO: Refactor in a better way
      if (preview_configuration?.columns_width) {
        for (const column in preview_configuration?.columns_width) {
          const targetColumn = this.context.columns.value.find(col => col.name == column);
          if (targetColumn) {
            targetColumn.width = preview_configuration.columns_width[column];
          }
        }
      }

      if (
        preview_configuration &&
        this.context.sorting.sortBy === null &&
        this.context.sorting.direction === null
      ) {
        this.context.sorting.sortBy = preview_configuration.sort_column;
        this.context.sorting.direction = preview_configuration.sort_direction;
      }
    }

    this.context.links.value = links;
  }

  /**
   * Generates a new FetchRecords url.
   *
   * @param {IFetchRecordsOptions} options - The fetch options.
   * @private
   */
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
