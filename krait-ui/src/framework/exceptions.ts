abstract class FetchingError extends Error {
  protected _response: Response;
  constructor(response: Response) {
    super(
      `Fetching to ${response.url} has failed with status code: ${response.status}.`,
    );
    this._response = response;
  }

  get response(): Response {
    return this._response;
  }
}

export class UnauthorizedError extends FetchingError {}

export class ServerError extends FetchingError {}
