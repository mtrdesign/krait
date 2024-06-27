import { KraitConfig } from '~/types';

declare global {
  interface Window {
    Krait: Partial<KraitConfig.IGlobalConfig>;
  }
}
