import { Table } from '~/types';

/**
 * Base Action class
 * Represents the structure of all actions.
 *
 * @class
 * @abstract
 */
export default abstract class BaseAction<
  OptionsT = undefined,
  ResultT = undefined,
> {
  protected context: Table.ITableContext;
  protected tableProps: Table.ITableConfiguration;
  protected tableName: string;

  constructor(
    context: Table.ITableContext,
    tableName: string,
    tableProps: Table.ITableConfiguration,
  ) {
    this.context = context;
    this.tableProps = tableProps;
    this.tableName = tableName;
  }

  abstract process(options: OptionsT): Promise<ResultT>;
}
