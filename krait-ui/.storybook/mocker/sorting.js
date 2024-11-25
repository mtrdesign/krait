import { isNumber } from './util.js';

export const sort = ({ data, url }) => {
  const column = url.searchParams.get('sort_column');
  const direction = url.searchParams.get('sort_direction');

  if (!column || !direction) {
    return data;
  }

  let sortedData = [...data];

  if (direction === 'asc') {
    sortedData.sort((a, b) => {
      if (typeof a[column] === 'string') {
        return a[column].localeCompare(b[column]);
      }
      return a[column] - b[column];
    });
  } else {
    sortedData.sort((a, b) => {
      if (typeof a[column] === 'string') {
        return b[column].localeCompare(a[column]);
      }

      return b[column] - a[column];
    });
  }

  return sortedData;
};
