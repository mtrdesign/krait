<script setup lang="ts">
import { ref } from 'vue';
import Draggable from 'vuedraggable';
import { useDispatcher, useTable } from '~/mixins';
import { DynamicColumn } from '@components/dynamic-column';
import { ResizeColumn, SortColumn, SaveColumnsOrder } from '~/actions';
import { SelectAllCheckbox } from '@components/select-all-checkbox';

const props = defineProps({
  tableName: {
    type: String,
    required: true,
  },
  actionsColumn: {
    type: Boolean,
    required: false,
    default: false,
  },
});

const emit = defineEmits(['refreshTable']);

const { columns, visibleColumns, sorting, isSelectableRows } = useTable(
  props.tableName,
);
const { dispatch } = useDispatcher(props.tableName);

const dragging = ref<boolean>(false);
const reorderColumn = async ({ moved }: { moved: boolean }) => {
  if (!moved) {
    return;
  }

  await dispatch<SaveColumnsOrder>(SaveColumnsOrder, {});
};

const columnWidths = ref<Record<string, number>>({});

// Update the resizeColumn function to store the width
const resizeColumn = async (_e: MouseEvent, name: string, width: number) => {
  columnWidths.value[name] = width;
  await dispatch<ResizeColumn>(ResizeColumn, {
    name,
    width,
  });
};

// Get the current width for a column
const getColumnWidth = (columnName: string, defaultWidth: number) => {
  return columnWidths.value[columnName] ?? defaultWidth;
};

const sortColumn = async (name: string, direction: string) => {
  await dispatch<SortColumn>(SortColumn, {
    name,
    direction,
  });

  emit('refreshTable');
};
</script>

<template>
  <thead>
    <tr>
      <th
        v-if="isSelectableRows"
        class="text-nowrap"
        scope="col"
        style="width: 35px"
      >
        <SelectAllCheckbox :tableName="props.tableName"></SelectAllCheckbox>
      </th>
      <Draggable
        v-model="columns"
        tag="transition-group"
        :scrollSensitivity="150"
        @start="dragging = true"
        @change="(args) => reorderColumn(args)"
        @end="dragging = false"
      >
        <template #item="{ element }">
          <DynamicColumn
            :name="element.name"
            :title="element.label"
            :isVisible="visibleColumns.includes(element.name)"
            :isSortable="element.sortable"
            :isActive="sorting.sortBy === element.name"
            :isResizable="!element.fixed"
            :sortDirection="sorting.direction"
            :hideTitle="element.hideLabel"
            :width="getColumnWidth(element.name, element.width ?? 100)"
            @resize="resizeColumn"
            @sort="sortColumn"
          ></DynamicColumn>
        </template>
      </Draggable>
      <th
        v-if="actionsColumn"
        class="text-nowrap"
        scope="col"
        style="width: 100px"
      ></th>
    </tr>
  </thead>
</template>

<style scoped lang="scss"></style>
