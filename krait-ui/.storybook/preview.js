/** @type { import('@storybook/vue3').Preview } */

// This should be implemented in the Main Laravel application,
//      adding it here just for the preview purposes
import '../node_modules/bootstrap/dist/css/bootstrap.css';
import * as records from './mocks/records.json';

const recordsJSON = JSON.stringify(records);

const preview = {
  parameters: {
    controls: {
      matchers: {
        color: /(background|color)$/i,
        date: /Date$/,
      },
    },
  },
};

const mockFetch = () => {
  window.fetch = async (target, fetchInit) => {
    let response;
    let url;

    if (typeof target === 'string') {
      url = new URL(target);
    } else if (target instanceof Request) {
      url = new URL(target.url);
    }

    if (url.pathname === '/api/records') {
      response = new Response(recordsJSON, {
        headers: {
          'content-type': 'application/json',
        },
      });
    } else {
      response = new Response(
        JSON.stringify({
          success: true,
        }),
      );
    }

    console.log('Requesting -> ', url);
    return response;
  };
};

const initCsrf = () => {
  const csrfMetaEl = document.createElement('meta');
  csrfMetaEl.setAttribute('name', 'csrf-token');
  csrfMetaEl.setAttribute('content', 'test-token');
  document.head.appendChild(csrfMetaEl);
};

const initConfig = () => {
  window.Krait = {
    routeUri: '/current-route',
    routes: {
      reorderColumns: 'https://test.com/api/reorder-columns',
      resizeColumn: 'https://test.com/api/resize-column',
    },
  };
};

mockFetch();
initCsrf();
initConfig();

export default preview;
