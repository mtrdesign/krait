import { sanityPath } from './utils';

const CURRENT_LOCATION = new URL(window.location.href);

export default class Config {
  static _kraitPath: string | undefined;
  static _tablesPath: string | undefined;
  static _csrfToken: string | undefined;

  static get kraitPath(): string {
    if (Config._kraitPath) {
      return Config._kraitPath;
    }

    let path: string;
    if (window.Krait.kraitPath) {
      path = sanityPath(window.Krait.kraitPath);
    } else {
      path = '/krait';
    }

    Config._kraitPath = path;
    return path;
  }

  static get tablesPath(): string {
    if (Config._tablesPath) {
      return Config._tablesPath;
    }

    let path: string;
    if (window.Krait.tablesPath) {
      path = sanityPath(window.Krait.tablesPath);
    } else {
      path = '/tables';
    }

    Config._tablesPath = path;
    return path;
  }

  static get useCsrfToken(): boolean {
    return window.Krait.useCsrf ?? false;
  }

  static get csrfToken(): string {
    if (Config._csrfToken) {
      return Config._csrfToken;
    }

    const token: string | undefined = window.Krait.csrfToken;
    if (!token) {
      throw new Error('CSRF token has not been passed.');
    }

    Config._csrfToken = token;
    return token;
  }

  static get kraitUrl(): URL {
    const url = new URL(CURRENT_LOCATION.origin);
    url.pathname = Config.kraitPath;
    return url;
  }

  static get tablesUrl(): URL {
    const url = new URL(CURRENT_LOCATION);
    url.pathname = Config.tablesPath;
    return url;
  }
}
