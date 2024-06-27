import ApiRequest from './api-request.js';
import Config from './config';

class ApiClient {
  public async fetch(...args: ConstructorParameters<typeof ApiRequest>) {
    const request = new ApiRequest(...args);
    if (Config.useCsrfToken) {
      request.csrfToken = Config.csrfToken;
    }

    const response = await fetch(request);
    if (response.status < 200 || response.status >= 400) {
      const errorText = await response.text();
      console.warn(
        `Request to ${request.url} failed with status code: ${response.status}`,
      );
      throw new Error(errorText);
    }

    return response;
  }
}

const apiClient = new ApiClient();

export default apiClient;
