import { Config, ApiClient } from '~/framework';
import BaseAction from './base-action';

interface ISortColumnOptions {
  name: string;
  direction: string;
}

interface ISortColumnResult {
  success: boolean;
}

export default class SortColumn extends BaseAction<
  ISortColumnOptions,
  ISortColumnResult
> {
  async process(options: ISortColumnOptions) {
    this.context.sorting.sortBy = options.name;
    this.context.sorting.direction = options.direction;

    const url = new URL(Config.kraitUrl);
    url.pathname = `${url.pathname}/preview-configurations/${this.tableName}/columns/sort`;

    await ApiClient.fetch(
      url,
      { name: options.name, direction: options.direction },
      'POST',
    );

    return {
      success: true,
    };
  }
}
