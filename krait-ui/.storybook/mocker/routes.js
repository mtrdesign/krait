import Router from './router.js';
import * as Actions from './actions.js';

const TABLE_NAME = 'cats-table';

export const initRoutes = () => {
  // The table-related routes
  Router.get(`/tables/${TABLE_NAME}`, Actions.getTableData);
  Router.post('/krait/table/structure', Actions.getTableStructure);

  // The internal Krait routes
  Router.post(
    `/krait/preview-configurations/${TABLE_NAME}/columns/items-per-page`,
    Actions.setItemsPerPage,
  );
  Router.post(
    `/krait/preview-configurations/${TABLE_NAME}/columns/reorder`,
    Actions.reorderColumns,
  );
  Router.post(
    `/krait/preview-configurations/${TABLE_NAME}/columns/resize`,
    Actions.resizeColumns,
  );
  Router.post(
    `/krait/preview-configurations/${TABLE_NAME}/columns/sort`,
    Actions.sortColumns,
  );
  Router.post(
    `/krait/preview-configurations/${TABLE_NAME}/columns/visibility`,
    Actions.hideColumns,
  );
};
