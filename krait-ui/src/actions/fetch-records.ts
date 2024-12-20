import { Table, Responses } from '~/types';
import { Config, ApiClient } from '~/framework';
import BaseAction from './base-action';
import { UnauthorizedError } from '~/framework/exceptions';

interface IFetchRecordsOptions {
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
  /**
   * Prepares the URL and fetches the records.
   *
   * @param {IFetchRecordsOptions} options - The column options.
   */
  async process(options: IFetchRecordsOptions) {
    let url: URL;
    if (options.url) {
      url = new URL(options.url);
    } else {
      url = this.generateUrl();
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
   * @param {URL} url - The request url
   * @private
   */
  private setFilters(url: URL): void {
    if (!this.tableProps.filtersForm) {
      throw new Error('No filters query provided');
    }

    const form = document.querySelector<HTMLFormElement>(
      this.tableProps.filtersForm,
    );

    if (!form) {
      throw new Error('No filters form found.');
    }

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
    const { data, meta, links } =
      (await response.json()) as Responses.ITableDataResponse;

    this.context.records.value = data;
    this.context.pagination.currentPage = meta.current_page;
    this.context.pagination.itemsPerPage = meta.per_page;
    this.context.pagination.totalItems = meta.total;
    this.context.pagination.links = meta.links;

    this.context.links.value = links;
  }

  /**
   * Generates a new FetchRecords url.
   *
   * @private
   */
  private generateUrl(): URL {
    const url = Config.tablesUrl;
    url.pathname = `${url.pathname}/${this.tableName}`;
    if (this.tableProps?.filtersForm) {
      this.setFilters(url);
    }
    this.setSorting(this.context.sorting, url);
    this.setPagination(this.context.pagination, url);

    if (this.tableProps?.apiQueryParameters) {
      for (const parameter in this.tableProps.apiQueryParameters) {
        url.searchParams.set(
          parameter,
          String(this.tableProps.apiQueryParameters[parameter]),
        );
      }
    }

    return url;
  }
}
