import ApiUrl from './api-url';

export interface ITableUrls {
  sortColumnsUrl: ApiUrl;
  reorderColumnsUrl: ApiUrl;
  resizeColumnsUrl: ApiUrl;
  hideColumnsUrl: ApiUrl;
}

export const getTableUrls = (tableName: string): ITableUrls => {
  const sortColumnsUrl = new ApiUrl(
    `preview-configurations/${tableName}/columns/sort`,
    true,
  );
  const reorderColumnsUrl = new ApiUrl(
    `preview-configurations/${tableName}/columns/reorder`,
    true,
  );
  const resizeColumnsUrl = new ApiUrl(
    `preview-configurations/${tableName}/columns/resize`,
    true,
  );
  const hideColumnsUrl = new ApiUrl(
    `preview-configurations/${tableName}/columns/visibility`,
    true,
  );

  return {
    sortColumnsUrl,
    reorderColumnsUrl,
    resizeColumnsUrl,
    hideColumnsUrl,
  };
};
