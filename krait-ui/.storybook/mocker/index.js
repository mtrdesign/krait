import Router from './router.js';
import { initRoutes } from './routes.js';

const initCsrf = () => {
  const csrfMetaEl = document.createElement('meta');
  csrfMetaEl.setAttribute('name', 'csrf-token');
  csrfMetaEl.setAttribute('content', 'test-token');
  document.head.appendChild(csrfMetaEl);
};

const init = () => {
  initCsrf();
  initRoutes();
  console.info('Krait Sample Mocker initialized!🥳');
};

window.fetch = async (target, fetchInit) => {
  // Parse all input types to a Request object
  const incomingRequest = new Request(target, fetchInit);
  console.info(
    `Sending ${incomingRequest.method} request to `,
    incomingRequest.url,
  );

  const route = Router.match(incomingRequest);
  if (!route) {
    console.error(`URL ${incomingRequest.url} is not mocked!`);
    return new Response('Not Found!', { status: 404 });
  }

  return route['handler'](incomingRequest);
};

export default {
  init,
};
