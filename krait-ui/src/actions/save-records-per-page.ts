import { Config, ApiClient } from '~/framework';
import BaseAction from './base-action';

interface ISaveRecordsPerPageResult {
  success: boolean;
}

/**
 * SaveRecordsPerPage Action
 * Saves the current records per page configuration.
 *
 * @class
 * @extends BaseAction
 */
export default class SaveRecordsPerPage extends BaseAction<
  undefined,
  ISaveRecordsPerPageResult
> {
  /**
   * Saves the columns order to the back-end.
   */
  async process() {
    const url = Config.kraitUrl;
    const tablePath = encodeURIComponent(encodeURIComponent(this.tableName));
    url.pathname = `${url.pathname}/preview-configurations/${tablePath}/columns/items-per-page`;

    await ApiClient.fetch(
      url,
      {
        items_per_page: this.context.pagination.itemsPerPage,
      },
      'POST',
    );

    return {
      success: true,
    };
  }
}
