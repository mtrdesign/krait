import { cats, tableStructure } from './db.js';
import { getLinks, getMeta, paginate } from './pagination.js';
import { sort } from './sorting.js';

export const getTableStructure = (req) => {
  return new Response(JSON.stringify(tableStructure), {
    headers: {
      'content-type': 'application/json',
    },
  });
};

export const getTableData = (req) => {
  const url = new URL(req.url);

  const tableData = sort({ data: cats, url });
  const currentPage = parseInt(url.searchParams.get('page') ?? 1);
  const ipp = parseInt(url.searchParams.get('ipp') ?? 30);
  const [paginatedData, totalPages] = paginate(tableData, ipp, currentPage);
  const pageLinks = getLinks(currentPage, url, totalPages);
  const pageMeta = getMeta({
    url,
    currentPage,
    totalPages,
    totalRecords: tableData.length,
    ipp,
    pageLinks,
  });

  const data = {
    data: {
      ...paginatedData,
    },
    links: {
      ...pageLinks,
    },
    meta: {
      ...pageMeta,
    },
  };
  return new Response(JSON.stringify(data), {
    headers: {
      'content-type': 'application/json',
    },
  });
};

export const setItemsPerPage = (req) => {
  return new Response();
};

export const reorderColumns = (req) => {
  return new Response();
};

export const resizeColumns = (req) => {
  return new Response();
};

export const sortColumns = (req) => {
  return new Response();
};

export const hideColumns = (req) => {
  return new Response();
};
