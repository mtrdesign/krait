import { Config, ApiClient } from '~/framework';
import BaseAction from './base-action';

interface IHideColumnOptions {
  name: string;
}

interface IHideColumnResult {
  success: boolean;
}

export default class HideColumn extends BaseAction<
  IHideColumnOptions,
  IHideColumnResult
> {
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
