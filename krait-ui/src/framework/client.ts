import ApiRequest from './api-request.js';
import Config from './config';
import { ServerError, UnauthorizedError } from '~/framework/exceptions';

/**
 * API Client Class
 * Handles all the API requests fetching.
 */
class ApiClient {
  /**
   * Fetches an API URL.
   *
   * @param {Array} args - The ApiRequest object parameters.
   * @param {string} args[0] - The request url.
   * @param {any} args[1] - The request body.
   * @param {string} args[2] - The request method.
   * @param {IHeaders} args[3] - The additional headers (if there are any).
   *
   * @return {Promise<Response>} - The corresponding response.
   * @throws {Error} - The response contains an "error" status code.
   */
  public async fetch(
    ...args: ConstructorParameters<typeof ApiRequest>
  ): Promise<Response> {
    const request = new ApiRequest(...args);
    if (Config.useCsrfToken) {
      request.csrfToken = Config.csrfToken;
    }

    const response = await fetch(request);
    if (response.status >= 200 && response.status < 300) {
      return response;
    } else if ([401, 403].includes(response.status)) {
      throw new UnauthorizedError(response);
    } else {
      throw new ServerError(response);
    }
  }
}

const apiClient = new ApiClient();
export default apiClient;
