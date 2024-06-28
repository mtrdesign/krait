import { Config, ApiClient } from '~/framework';
import BaseAction from './base-action';

interface IHideColumnOptions {
  name: string;
}

interface IHideColumnResult {
  success: boolean;
}

/**
 * HidesColumn Action
 * Hides specific column from the table.
 *
 * @class
 * @extends BaseAction
 */
export default class HideColumn extends BaseAction<
  IHideColumnOptions,
  IHideColumnResult
> {
  /**
   * Hides column from the table state and save the configuration.
   *
   * @param {IHideColumnOptions} options - The column options.
   */
  async process(options: IHideColumnOptions) {
    const columnIndex = this.context.visibleColumns.value.indexOf(options.name);
    if (columnIndex !== -1) {
      this.context.visibleColumns.value.splice(columnIndex, 1);
    } else {
      this.context.visibleColumns.value.push(options.name);
    }

    const url = Config.kraitUrl;
    url.pathname = `${url.pathname}/preview-configurations/${this.tableName}/columns/visibility`;

    await ApiClient.fetch(
      url,
      {
        visible_columns: this.context.visibleColumns.value,
      },
      'POST',
    );
    return {
      success: true,
    };
  }
}
