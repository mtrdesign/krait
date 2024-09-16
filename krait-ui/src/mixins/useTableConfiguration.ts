import { Table } from '~/types';
import { DeepReadonly, readonly, watch } from 'vue';

export const tableConfigurations: Map<
  string,
  DeepReadonly<Table.ITableConfiguration>
> = new Map();

const useTableConfiguration = (
  tableName: string,
  props?: Table.ITableConfiguration,
): DeepReadonly<Table.ITableConfiguration> => {
  const configuration = tableConfigurations.get(tableName);

  if (configuration && !props) {
    return configuration;
  }

  // Make props reactive or readonly
  const reactiveProps = readonly(props);

  tableConfigurations.set(
    tableName,
    reactiveProps as DeepReadonly<Table.ITableConfiguration>,
  );

  // Watch for changes in props
  watch(
    reactiveProps,
    (newProps) => {
      const updatedState = readonly(newProps);
      tableConfigurations.set(
        tableName,
        updatedState as DeepReadonly<Table.ITableConfiguration>,
      );
    },
    { deep: true }, // Use deep option to watch nested properties
  );

  return reactiveProps as DeepReadonly<Table.ITableConfiguration>;
};

export default useTableConfiguration;
