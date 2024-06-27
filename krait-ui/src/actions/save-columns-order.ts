import { Config, ApiClient } from '~/framework';
import BaseAction from './base-action';

interface IReorderColumnsOptions {}

interface IReorderColumnsResult {
  success: boolean;
}

export default class SaveColumnsOrder extends BaseAction<
  IReorderColumnsOptions,
  IReorderColumnsResult
> {
  async process(_options: IReorderColumnsOptions) {
    const orderedColumns: string[] = [];

    this.context.columns.value.forEach((column) => {
      if (this.context.visibleColumns.value.includes(column.name)) {
        orderedColumns.push(column.name);
      }
    });

    const url = Config.kraitUrl;
    url.pathname = `${url.pathname}/preview-configurations/${this.tableName}/columns/reorder`;

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