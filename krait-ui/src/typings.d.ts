import { KraitConfig } from './types';

declare global {
  interface Window {
    Krait: KraitConfig.IGlobalConfig;
  }
}
