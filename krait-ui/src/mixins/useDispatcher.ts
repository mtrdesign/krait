import { Table } from '~/types';
import { BaseAction } from '~/actions';
import { tables } from './useTable';
import useToast, { MessageTypes } from './useToast';

interface IUseDispatcher {
  dispatch: <T extends BaseAction>(
    actionClass: {
      new (_context: Table.ITableContext, _tableName: string): T;
    },
    options: Parameters<T['process']>[0],
  ) => Promise<ReturnType<T['process']> | null>;
}

/**
 * Return the dispatcher state and helpers.
 *
 * @param {string} tableName - The events-related table.
 *
 * @return {IUseDispatcher} - The Table dispatcher helpers and state.
 */
const useDispatcher = (tableName: string): IUseDispatcher => {
  const { showMessage } = useToast();

  const state = tables.get(tableName);
  if (!state) {
    throw new Error(`Table ${tableName} has not been initialized.`);
  }

  /**
   * Dispatches a new action.
   *
   * @param {Object} actionClass - The target action class.
   * @param {Object} options - The action properties.
   *
   * @return {Promise<any>} - The action result.
   */
  const dispatch: IUseDispatcher['dispatch'] = async function (
    actionClass,
    options,
  ) {
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

export default useDispatcher;
