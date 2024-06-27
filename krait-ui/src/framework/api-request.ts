const DEFAULT_HEADERS = {
  'content-type': 'application/json',
};

interface IHeaders {
  [key: string]: string | number;
}

class ApiRequest extends Request {
  constructor(
    url: string | URL,
    body: any = null,
    method: string = 'GET',
    headers: IHeaders = {},
  ) {
    if (body !== null && typeof body !== 'string') {
      body = JSON.stringify(body);
    }

    super(url, {
      method,
      body,
    });
    this.setDefaultHeaders();
    this.setAdditionalHeaders(headers);
  }

  private setDefaultHeaders() {
    for (const [name, value] of Object.entries(DEFAULT_HEADERS)) {
      this.headers.set(name, value);
    }
  }

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

  set csrfToken(token: string) {
    this.headers.set('x-csrf-token', token);
  }
}

export default ApiRequest;
