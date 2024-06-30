import { getRecords } from "./responses.js";

const MOCKED_KRAIT_CONFIG = {
  kraitPath: 'krait',
  tablesPath: 'tables',
  useCsrf: true,
  csrfToken: 'test-csrf-token',
};

class Mocker {
  #target;
  #fetchInit;

  constructor(target, fetchInit) {
    this.#target = target;
    this.#fetchInit = fetchInit;
  }

  get url() {
    if (typeof this.#target === 'string') {
      return new URL(this.#target);
    } else if (this.#target instanceof Request) {
      return new URL(this.#target.url);
    }
  }

  get httpMethod() {
    if (this.#fetchInit?.method) {
      return this.#fetchInit?.method;
    } else if (this.#target instanceof Request) {
      return this.#target.method;
    } else {
      return 'GET';
    }
  }

  get previewConfiguration() {
    const ipp = this.url.searchParams.get('ipp');
    const page = this.url.searchParams.get('page');
    return {
      sort_column: this.url.searchParams.get('sort_column') ?? null,
      sort_direction: this.url.searchParams.get('sort_direction') ?? null,
      itemsPerPage: ipp ? parseInt(ipp) : 30,
      currentPage: page ? parseInt(page) : 1,
    }
  }

  #getRecordsResponse() {
    return getRecords(this.url, this.previewConfiguration);
  }

  #getKraitResponse() {
    return new Response('Mocked response.');
  }

  process() {
    if (this.url.pathname.startsWith('/' + MOCKED_KRAIT_CONFIG.tablesPath)) {
      return this.#getRecordsResponse();
    } else if (this.url.pathname.startsWith('/' + MOCKED_KRAIT_CONFIG.kraitPath)) {
      return this.#getKraitResponse();
    } else {
      return new Response();
    }
  }
}

const mockedFetch = async (target, fetchInit) => {
  const mocker = new Mocker(target, fetchInit);
  return mocker.process();
};

export const init = () => {
  window.Krait = MOCKED_KRAIT_CONFIG;
  window.fetch = mockedFetch;
}
