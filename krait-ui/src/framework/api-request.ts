import { HTTP } from '~/types';

const DEFAULT_HEADERS = {
  'content-type': 'application/json',
  accept: 'application/json',
};

interface IHeaders {
  [key: string]: string | number;
}

/** Class representing an Krait API request. */
class ApiRequest extends Request {
  /**
   * Create an API Request.
   *
   * @param {string|URL} url - The target API URL to be fetched.
   * @param {any} body - The request body.
   * @param {string} method - The request method.
   * @param {IHeaders} [headers={}] - The additional headers (if there are any).
   */
  constructor(
    url: string | URL,
    body: HTTP.ApiRequestBody = null,
    method: string = 'GET',
    headers: IHeaders = {},
  ) {
    let data: string | null;
    if (body && typeof body !== 'string') {
      data = JSON.stringify(body);
    } else {
      data = body;
    }

    super(url, {
      method,
      body: data,
    });
    this.setDefaultHeaders();
    this.setAdditionalHeaders(headers);
  }

  /**
   * Sets the default headers.
   * @private
   */
  private setDefaultHeaders() {
    for (const [name, value] of Object.entries(DEFAULT_HEADERS)) {
      this.headers.set(name, value);
    }
  }

  /**
   * Sets the additional headers passed to the request.
   * @param headers
   * @private
   */
  private setAdditionalHeaders(headers: IHeaders) {
    for (const [name, value] of Object.entries(headers)) {
      let headerValue: string;
      if (typeof value === 'number') {
        headerValue = String(value);
      } else {
        headerValue = value;
      }

      this.headers.set(name, headerValue);
    }
  }

  /**
   * Sets the CSRF token header.
   * @param {string} token - The CSRF token value.
   */
  set csrfToken(token: string) {
    this.headers.set('x-csrf-token', token);
  }
}

export default ApiRequest;
