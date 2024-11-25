class Router {
  #routes;

  constructor() {
    this.#routes = [];
  }

  get(pathname, handler) {
    this.#routes.push({
      pathname,
      method: 'GET',
      handler,
    });
  }

  post(pathname, handler) {
    this.#routes.push({
      pathname,
      method: 'POST',
      handler,
    });
  }

  put(pathname, handler) {
    this.#routes.push({
      pathname,
      method: 'PUT',
      handler,
    });
  }

  delete(pathname, handler) {
    this.#routes.push({
      pathname,
      method: 'DELETE',
      handler,
    });
  }

  match(request) {
    const url = new URL(request.url);
    return this.#routes.find((route) => {
      return route.pathname === url.pathname && route.method === request.method;
    });
  }

  get routes() {
    return this.#routes;
  }
}

const router = new Router();
export default router;
