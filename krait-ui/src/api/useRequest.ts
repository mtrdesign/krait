import { MessageTypes, useToast } from '@components/toast';
import ApiUrl from './api-url';
import Client from './client';

const apiClient = new Client();

export default () => {
  const { showMessage } = useToast();

  const fetchApi = async (
    url: ApiUrl,
    method = 'GET',
    body: BodyInit | null = null,
    parseResponse = true,
    updateHistory = false,
    followRedirect = true,
  ) => {
    try {
      const response = await apiClient.fetch(url, {
        method,
        body,
      });

      if (response.redirected && followRedirect) {
        window.location.href = response.url;
        return response;
      }

      if (updateHistory) {
        const currentUrl = new URL(window.location.href);
        const historyState = `${currentUrl.pathname}${url.search}`;
        history.replaceState({}, '', historyState);
      }

      return parseResponse ? response.json() : response;
    } catch (e) {
      console.error(e);
      showMessage('Something went wrong, please contact the administrators.', {
        type: MessageTypes.Danger,
      });
    }

    return null;
  };

  return {
    fetchApi,
    ApiUrl,
    apiClient,
  };
};
