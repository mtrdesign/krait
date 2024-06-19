const DEFAULT_HEADERS = {
  'content-type': 'application/json',
};

class ApiRequest extends Request {
  constructor(url: string | URL, init: RequestInit) {
    if (typeof init.method === 'undefined') {
      init.method = 'GET';
    } else if (
      ['POST', 'PUT'].includes(init.method) &&
      typeof init.body !== 'undefined'
    ) {
      init.body = ApiRequest.parseBody(init.body);
    }
    super(url, init);
    this.setDefaultHeaders();
  }

  private setDefaultHeaders() {
    for (const [name, value] of Object.entries(DEFAULT_HEADERS)) {
      this.headers.set(name, value);
    }
  }

  static parseBody(body: BodyInit | null) {
    if (typeof body !== 'string') {
      body = JSON.stringify(body);
    }

    return body;
  }

  set csrfToken(token: string) {
    this.headers.set('x-csrf-token', token);
  }

  set authToken(token: string) {
    this.headers.set('authorization', `Bearer ${token}`);
  }

  get csrfToken(): string | null {
    return this.headers.get('x-csrf-token');
  }
}

export default ApiRequest;
