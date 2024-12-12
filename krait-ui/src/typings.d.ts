import { KraitConfig } from '~/types';

declare global {
  interface Window {
    Krait: Partial<KraitConfig.IGlobalConfig>;
  }

  type Dictionary<T> = {
    [key: string]: T;
  };

  type OptionalDictionary<T> = {
    [key: string]: T | undefined;
  };
}
