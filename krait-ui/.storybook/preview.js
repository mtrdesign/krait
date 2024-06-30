/** @type { import('@storybook/vue3').Preview } */

// This should be implemented in the Main Laravel application,
//      adding it here just for the preview purposes
import '../node_modules/bootstrap/dist/css/bootstrap.css';
import '../node_modules/@fortawesome/fontawesome-free/css/all.css';
import * as records from './mocks/records.json';
import { init } from "./mocker/mocker.js";

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

// const mockFetch = () => {
//   window.fetch = async (target, fetchInit) => {
//     let response;
//     let url;
//
//     if (typeof target === 'string') {
//       url = new URL(target);
//     } else if (target instanceof Request) {
//       url = new URL(target.url);
//     }
//
//     if (url.pathname === '/tables/test') {
//       response = new Response(recordsJSON, {
//         headers: {
//           'content-type': 'application/json',
//         },
//       });
//     } else {
//       response = new Response(
//         JSON.stringify({
//           success: true,
//         }),
//       );
//     }
//
//     console.log('Requesting -> ', url.toString());
//     return response;
//   };
// };
//
// const initCsrf = () => {
//   const csrfMetaEl = document.createElement('meta');
//   csrfMetaEl.setAttribute('name', 'csrf-token');
//   csrfMetaEl.setAttribute('content', 'test-token');
//   document.head.appendChild(csrfMetaEl);
// };
//
// const initConfig = () => {
//   window.Krait = {
//     resourceApiPath: '/api',
//     internalApiPath: '/krait/api',
//
//     kraitApi: {
//       use_csrf: true,
//       auth_token: null,
//     },
//     resourceApi: {
//       use_csrf: true,
//       auth_token: null,
//     },
//
//     csrfToken: 'test-csrf-token',
//   };
// };

// mockFetch();
// initCsrf();
// initConfig();

init();

export default preview;
