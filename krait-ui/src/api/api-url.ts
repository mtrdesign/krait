const CURRENT_URL = new URL(window.location.href);
const API_URL = new URL(`${CURRENT_URL.origin}/api`);

interface IPagination {
  sortColumn?: string;
  sortDirection?: string;
  ipp?: number;
  page?: number;
}

interface IFilter {
  name: string;
  value: string;
}

class ApiUrl extends URL {
  private _pagination: IPagination = {};
  private _filters: IFilter[] = [];

  constructor(endpoint: string) {
    let url: URL;

    if (endpoint.startsWith('http://') || endpoint.startsWith('https://')) {
      url = new URL(endpoint);
    } else {
      url = new URL(API_URL);

      // Remove the start slash for consistency
      if (endpoint.startsWith('/')) {
        endpoint = endpoint.slice(1);
      }

      url.pathname = [url.pathname, endpoint].join('/');
    }

    super(url.toString());
  }

  get pagination(): IPagination {
    return this._pagination;
  }

  set apiParams({ sortColumn, sortDirection, ipp, page }: IPagination) {
    if (sortColumn) {
      this._pagination.sortColumn = sortColumn;
      this.searchParams.set('sort_column', sortColumn);
    }

    if (sortDirection) {
      this._pagination.sortDirection = sortDirection;
      this.searchParams.set('sort_direction', sortDirection);
    }

    if (ipp) {
      this._pagination.ipp = ipp;
      this.searchParams.set('ipp', ipp.toString());
    }

    if (page) {
      this._pagination.page = page;
      this.searchParams.set('page', page.toString());
    }
  }

  get filtersQuery(): IFilter[] {
    return this._filters;
  }

  set filtersQuery(query: string | IFilter[]) {
    let parsedFilters: IFilter[];
    if (typeof query === 'string') {
      parsedFilters = ApiUrl.parseStringQuery(query);
    } else {
      parsedFilters = query;
    }

    const filters = [];
    for (const { name, value } of parsedFilters) {
      if (!value) {
        continue;
      }

      this.searchParams.append(name, value);
      filters.push({ name, value: value });
    }

    this._filters = filters;
  }

  static parseStringQuery(query: string) {
    const parsedQuery: {
      name: string;
      value: string;
    }[] = [];
    const params = new URLSearchParams(query);
    params.forEach((value, name) => {
      parsedQuery.push({
        name,
        value,
      });
    });

    return parsedQuery;
  }
}

export default ApiUrl;
