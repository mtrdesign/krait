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

    for (const table of tables) {
      const componentName =
        table.__name.charAt(0).toLowerCase() + table.__name.slice(1);
      const elementName = componentName
        .replace(/([A-Z])/g, '-$1')
        .toLowerCase();
      app.component(elementName, table);
    }
  },
};
