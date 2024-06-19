export interface IGlobalConfig {
  apiBaseUrl: string;
  internalApiPath: string;

  kraitApi: {
    use_csrf: boolean;
    auth_token: string | null;
  };
  resourceApi: {
    use_csrf: boolean;
    auth_token: string | null;
  };

  csrfToken: string;
}
