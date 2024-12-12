import { Config, ApiClient } from '~/framework';
import BaseAction from './base-action';

interface IReorderColumnsResult {
  success: boolean;
}

/**
 * SaveColumnOrder Action
 * Saves the current columns order.
 *
 * @class
 * @extends BaseAction
 */
export default class SaveColumnsOrder extends BaseAction<
  undefined,
  IReorderColumnsResult
> {
  /**
   * Saves the columns order to the back-end.
   */
  async process() {
    const orderedColumns: string[] = [];

    this.context.columns.value.forEach((column) => {
      if (this.context.visibleColumns.value.includes(column.name)) {
        orderedColumns.push(column.name);
      }
    });

    const url = Config.kraitUrl;
    const tablePath = encodeURIComponent(encodeURIComponent(this.tableName));
    url.pathname = `${url.pathname}/preview-configurations/${tablePath}/columns/reorder`;

    await ApiClient.fetch(
      url,
      {
        columns: orderedColumns,
      },
      'POST',
    );

    return {
      success: true,
    };
  }
}
