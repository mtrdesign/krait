import { Config, ApiClient } from '~/framework';
import BaseAction from './base-action';

interface IResizeColumnOptions {
  name: string;
  width: number;
}

interface IResizeColumnResult {
  success: boolean;
}

export default class ResizeColumn extends BaseAction<
  IResizeColumnOptions,
  IResizeColumnResult
> {
  async process(options: IResizeColumnOptions) {
    const column = this.context.columns.value.find((column) => {
      if (column.name === options.name) {
        column.width = options.width;
        return true;
      }
    });

    if (!column) {
      throw new Error(`Column ${options.name} not found.`);
    }

    await this.saveColumn(options.name, options.width);

    return {
      success: true,
    };
  }

  private async saveColumn(name: string, width: number): Promise<void> {
    const url = Config.kraitUrl;
    url.pathname = `${url.pathname}/preview-configurations/${this.tableName}/columns/resize`;

    await ApiClient.fetch(url, { name, width }, 'POST');
  }
}
