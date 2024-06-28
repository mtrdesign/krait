import { Table } from '~/types';

/**
 * Base Action class
 * Represents the structure of all actions.
 *
 * @class
 * @abstract
 */
export default abstract class BaseAction<OptionsT = any, ResultT = any> {
  protected context: Table.ITableContext;
  protected tableName: string;

  constructor(context: Table.ITableContext, tableName: string) {
    this.context = context;
    this.tableName = tableName;
  }

  abstract process(options: OptionsT): Promise<ResultT>;
}
