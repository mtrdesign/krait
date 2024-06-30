import * as records from '../mocks/data.json';

// @TODO: Refactor the mocking part...
const getPaginationLinks = (url, previewConfiguration, pagesCount) => {
  const links = [];
  for (let i = 0; i < pagesCount; i++) {
    const paginationUrl = new URL(url);
    paginationUrl.searchParams.set('page', String(i + 1));
    links.push({
      url: paginationUrl.toString(),
      label: i + 1,
      active: i === previewConfiguration.currentPage - 1,
    })
  }

  return links;
};

export const getRecords = (url, previewConfiguration) => {
  let data = records.data;
  const totalCount = data.length;
  const pagesCount = Math.ceil(totalCount / previewConfiguration.itemsPerPage);

  if (previewConfiguration.sort_column && previewConfiguration.sort_direction) {
    if (previewConfiguration.sort_direction === 'asc') {
      data = data.sort((a, b) => a[previewConfiguration.sort_column].localeCompare(b[previewConfiguration.sort_column]))
    } else {
      data = data.sort((a, b) => -1 * a[previewConfiguration.sort_column].localeCompare(b[previewConfiguration.sort_column]))
    }
  }

  const start = (previewConfiguration.currentPage - 1) * previewConfiguration.itemsPerPage;
  const end = start + previewConfiguration.itemsPerPage;

  const response = {
    columns: records.columns,
    data: data.slice(start, end),
    meta: {
      current_page: previewConfiguration.currentPage,
      from: 1,
      last_page: pagesCount,
      links: getPaginationLinks(url, previewConfiguration, pagesCount),
      path: window.location.href,
      per_page: previewConfiguration.itemsPerPage,
      to: pagesCount,
      total: records.data.length,
    },
    links: [],
  };

  return new Response(JSON.stringify(response), {
    headers: {
      'content-type': 'application/json'
    }
  });
};
