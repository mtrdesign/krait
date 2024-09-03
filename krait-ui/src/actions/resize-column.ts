import {Config, ApiClient} from '~/framework';
import BaseAction from './base-action';

interface IResizeColumnOptions {
    name: string;
    width: number;
}

interface IResizeColumnResult {
    success: boolean;
}

/**
 * ResizeColumn Action
 * Updates the width of specific column.
 *
 * @class
 * @extends BaseAction
 */
export default class ResizeColumn extends BaseAction<
    IResizeColumnOptions,
    IResizeColumnResult
> {
    /**
     * Resized a table column and saves the configuration.
     *
     * @param {IResizeColumnOptions} options - The column options.
     */
    async process(options: IResizeColumnOptions) {
        const column = this.context.columns.value.find((column) => column.name === options.name);

        if (!column) {
            throw new Error(`Column ${options.name} not found.`);
        }

        await this.saveColumn(options.name, options.width);

        return {
            success: true,
        };
    }

    /**
     * Saves the configuration to the back-end.
     *
     * @param {string} name - The column name.
     * @param {number} width - The column width.
     * @private
     */
    private async saveColumn(name: string, width: number): Promise<void> {
        const url = Config.kraitUrl;
        const tablePath = encodeURIComponent(encodeURIComponent(this.tableName));
        url.pathname = `${url.pathname}/preview-configurations/${tablePath}/columns/resize`;
        console.log(tablePath);

        await ApiClient.fetch(url, {name, width}, 'POST');
    }
}
