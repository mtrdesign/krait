import ApiRequest from './api-request.js';
import ApiUrl from './api-url';

const API_BASE_ENDPOINT_PREFIX = '/api';
// const API_URL = new URL(window.location.href);
const API_URL = new URL('https://test.com');
API_URL.pathname = API_BASE_ENDPOINT_PREFIX;

interface IFetchParams {
  method?: string;
  body?: BodyInit | null;
}

class ApiClient {
  private _csrfToken: string | undefined;

  get csrfToken(): string {
    if (typeof this._csrfToken !== 'undefined') {
      return this._csrfToken;
    }

    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (csrfMeta === null) {
      throw new Error('CSRF meta dom element has not been found.');
    }
    const csrfToken = csrfMeta.getAttribute('content');
    if (csrfToken === null) {
      throw new Error('CSRF meta dom element is empty.');
    }
    return csrfToken;
  }

  public async fetch(
    url: ApiUrl,
    { method = 'GET', body = null }: IFetchParams,
  ) {
    const request = new ApiRequest(url, {
      method,
      body,
    });
    request.csrfToken = this.csrfToken;

    const response = await fetch(request);
    if (response.status < 200 || response.status >= 400) {
      const errorText = await response.text();
      console.warn(
        `Request to ${request.url} failed with status code: ${response.status}`,
      );
      console.error(errorText);
      throw new Error(errorText);
    }

    return response;
  }
}

export default ApiClient;
