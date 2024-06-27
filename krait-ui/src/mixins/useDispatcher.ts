import { Table } from '~/types';
import { BaseAction } from '~/actions';
import { tables } from './useTable';
import useToast, { MessageTypes } from './useToast';

export default (tableName: string) => {
  const { showMessage } = useToast();

  const state = tables.get(tableName);
  if (!state) {
    throw new Error(`Table ${tableName} has not been initialized.`);
  }

  const dispatch = async function <T extends BaseAction>(
    actionClass: { new (_context: Table.ITableContext, _tableName: string): T },
    options: Parameters<T['process']>[0],
  ): Promise<ReturnType<T['process']> | null> {
    try {
      const action = new actionClass(state, tableName);
      return await action.process(options);
    } catch (e) {
      console.error(e);
      showMessage('Something went wrong, please contact the administrators.', {
        type: MessageTypes.Danger,
      });
      return null;
    }
  };

  return {
    dispatch,
  };
};
