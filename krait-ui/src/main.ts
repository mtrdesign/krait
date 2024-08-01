import { App } from 'vue';
import { DynamicTable } from '~/components';

interface IOptions {
  tables: {
    __name: string;
  }[];
}

export default {
  install: (app: App, { tables }: IOptions) => {
    app.component('DynamicTable', DynamicTable);

    for (const [key, component] of Object.entries(tables)) {
      const componentName = key.charAt(0).toLowerCase() + key.slice(1);
      const elementName = componentName
        .replace(/([A-Z])/g, '-$1')
        .toLowerCase();
      app.component(elementName, component);
    }
  },
};
