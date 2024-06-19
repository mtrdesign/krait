import ApiRequest from './api-request.js';
import ApiUrl from './api-url';

interface IFetchParams {
  method?: string;
  body?: BodyInit | null;
}

class ApiClient {
  public async fetch(
    url: ApiUrl,
    { method = 'GET', body = null }: IFetchParams,
  ) {
    const request = new ApiRequest(url, {
      method,
      body,
    });

    if (url.isInternal) {
      if (window.Krait.kraitApi.use_csrf) {
        request.csrfToken = window.Krait.csrfToken;
      }

      if (window.Krait.kraitApi.auth_token) {
        request.authToken = window.Krait.kraitApi.auth_token;
      }
    } else {
      if (window.Krait.resourceApi.use_csrf) {
        request.csrfToken = window.Krait.csrfToken;
      }

      if (window.Krait.resourceApi.auth_token) {
        request.authToken = window.Krait.resourceApi.auth_token;
      }
    }

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
