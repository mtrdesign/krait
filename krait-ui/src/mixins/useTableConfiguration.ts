import { Table } from '~/types';
import { DynamicTableProps } from '../../dist/src/types/table';
import { readonly, watch } from 'vue';

export const tableConfigurations: Map<string, Table.ITableConfiguration> =
  new Map();

const useTableConfiguration = (
  tableName: string,
  props?: DynamicTableProps,
): Table.ITableConfiguration => {
  const configuration = tableConfigurations.get(tableName);
  if (configuration && !props) {
    return configuration;
  }

  // Make props reactive or readonly
  const reactiveProps = readonly(props);

  tableConfigurations.set(tableName, reactiveProps);

  // Watch for changes in props
  watch(
    reactiveProps,
    (newProps) => {
      const updatedState = readonly(newProps);
      tableConfigurations.set(tableName, updatedState);
    },
    { deep: true }, // Use deep option to watch nested properties
  );

  return reactiveProps;
};

export default useTableConfiguration;
