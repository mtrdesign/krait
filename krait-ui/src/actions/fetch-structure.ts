import { ApiClient } from '~/framework';
import BaseAction from './base-action';
import { Responses } from '~/types';
import { IColumn } from '~/types/table';
import { IPreviewConfiguration } from '~/types/responses';
import { UnauthorizedError } from '~/framework/exceptions';

interface IFetchStructureOptions {}

interface IFetchStructureResult {
  success: boolean;
}

/**
 * FetchStructure Action
 * Fetches the table's structure.
 *
 * @class
 * @extends BaseAction
 */
export default class FetchStructure extends BaseAction<
  IFetchStructureOptions,
  IFetchStructureResult
> {
  /**
   * Gets the table's structure
   *
   * @param {IFetchRecordsOptions} options - The column options.
   */
  async process(options: IFetchStructureOptions) {
    const url = new URL(window.origin);
    url.pathname = `krait/table/structure`;

    try {
      const response = await ApiClient.fetch(
        url,
        {
          table: this.tableName,
        },
        'POST',
      );
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
    return {
      success: true,
    };
  }

  /**
   * Parses the back-end response and updates the table structure.
   *
   * @param {Response} response - The back-end response.
   * @private
   */
  private async parseResponse(response: Response) {
    const { data } = await response.json();

    const {
      columns,
      preview_configuration,
      selectable_rows,
      bulk_action_links,
    } = data as Responses.ITableStructureResponse;

    this.setColumns(columns);
    this.setSorting(preview_configuration);
    this.setVisibleColumns(preview_configuration);
    this.setColumnWidth(preview_configuration);
    this.setIsSelectableRows(selectable_rows);
    this.setBulkActionLinks(bulk_action_links);
  }

  private setBulkActionLinks(
    bulkActions: Responses.ITableStructureResponse['bulk_action_links'],
  ) {
    this.context.bulkActionLinks.value = bulkActions;
  }

  private setIsSelectableRows(value: boolean) {
    this.context.isSelectableRows.value = value;
  }

  private setColumns(columns: IColumn[]) {
    this.context.columns.value = columns;
  }

  private setVisibleColumns(
    preview_configuration: IPreviewConfiguration | null,
  ) {
    const visible_columns = preview_configuration?.visible_columns;

    this.context.visibleColumns.value = !!visible_columns
      ? visible_columns
      : this.context.columns.value.map((column) => column.name);
  }

  private setColumnWidth(preview_configuration: IPreviewConfiguration | null) {
    if (!preview_configuration?.columns_width) return;

    const columnsWidth = preview_configuration.columns_width;

    Object.entries(columnsWidth).forEach(([column, width]) => {
      const targetColumn = this.context.columns.value.find(
        (col) => col.name === column,
      );
      if (targetColumn) {
        targetColumn.width = width;
      }
    });
  }

  private setSorting(preview_configuration: IPreviewConfiguration | null) {
    if (!preview_configuration) return;

    const { sortBy, direction } = this.context.sorting;

    if (sortBy === null && direction === null) {
      const { sort_column, sort_direction } = preview_configuration;
      this.context.sorting.sortBy = sort_column;
      this.context.sorting.direction = sort_direction;
    }
  }
}
