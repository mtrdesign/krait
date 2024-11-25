export const paginate = (data, ipp, currentPage) => {
  if (!data || data.length === 0) return [];

  // Calculate total number of pages
  const totalPages = Math.ceil(data.length / ipp);

  const start = (currentPage - 1) * ipp;
  return [data.slice(start, start + ipp), totalPages];
};

export const getLinks = (currentPage, url, totalPages) => {
  const firstURL = new URL(url);
  firstURL.searchParams.set('page', '1');
  const lastURL = new URL(url);
  lastURL.searchParams.set('page', String(totalPages));

  let prevURL = null;
  if (currentPage > 1) {
    prevURL = new URL(url);
    prevURL.searchParams.set('page', String(currentPage - 1));
  }

  let nextURL = null;
  if (currentPage < totalPages) {
    nextURL = new URL(url);
    nextURL.searchParams.set('page', String(currentPage + 1));
  }

  return {
    first: firstURL.toString(),
    last: lastURL.toString(),
    prev: prevURL ? prevURL.toString() : null,
    next: nextURL ? nextURL.toString() : null,
  };
};

export const getMeta = ({
  url,
  currentPage,
  totalPages,
  totalRecords,
  ipp,
  pageLinks,
}) => {
  const pageButtons = [];

  for (let i = 0; i < totalPages; i++) {
    const pageURL = new URL(url);
    pageURL.searchParams.set('page', String(i + 1));
    pageButtons.push({
      url: pageURL.toString(),
      label: String(i + 1),
      active: currentPage === i + 1,
    });
  }

  const links = [
    {
      url: pageLinks['prev'],
      label: '&laquo; Previous',
      active: !!pageLinks['prev'],
    },
    ...pageButtons,
    {
      url: pageLinks['next'],
      label: 'Next &raquo;',
      active: !!pageLinks['next'],
    },
  ];

  return {
    current_page: currentPage,
    last_page: totalPages,
    path: `${url.origin}/${url.pathname}`,
    per_page: ipp,
    from: currentPage ? (currentPage - 1) * ipp : 1,
    to: currentPage * ipp,
    total: totalRecords,
    links,
  };
};
