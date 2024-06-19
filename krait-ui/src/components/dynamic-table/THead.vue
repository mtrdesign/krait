<script setup lang="ts">
import { ref } from 'vue';
import Draggable from 'vuedraggable';
import useTable from '@components/dynamic-table/useTable';
import DynamicColumn from '@components/dynamic-table/DynamicColumn.vue';
import { useRequest } from '~/api';

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

const { columns, visibleColumns, sorting, urls } = useTable(props.tableName);
const { ApiUrl, apiClient } = useRequest();

const dragging = ref(false);

const reorderColumn = async ({ moved }: { moved: boolean }) => {
  if (!moved) {
    return;
  }

  const data: {
    columns: string[];
  } = {
    columns: [],
  };

  columns.value.forEach((column) => {
    if (visibleColumns.value.includes(column.name)) {
      data.columns.push(column.name);
    }
  });

  await apiClient.fetch(urls.reorderColumnsUrl, {
    method: 'POST',
    body: JSON.stringify(data),
  });
};

// @typescript-eslint/no-explicit-any
const resizeColumn = async (e: any, column_name: string, width: number) => {
  const column = columns.value.find((column) => {
    if (column.name === column_name) {
      column.width = width;
      return true;
    }
  });
  if (!column) {
    return;
  }

  await apiClient.fetch(urls.resizeColumnsUrl, {
    method: 'POST',
    body: JSON.stringify({
      name: column.name,
      width: column.width!,
    }),
  });
};
</script>

<template>
  <thead>
    <tr>
      <draggable
        v-model="columns"
        tag="transition-group"
        :scroll-sensitivity="150"
        @start="dragging = true"
        @change="(args) => reorderColumn(args)"
        @end="dragging = false"
      >
        <template #item="{ element }">
          <DynamicColumn
            :name="element.name"
            :title="element.label"
            :is-visible="visibleColumns.includes(element.name)"
            :is-sortable="
              typeof element.sortable === 'undefined' || element.sortable
            "
            :is-active="sorting.sortBy === element.name"
            :is-resizable="!element.fixed"
            :sort-direction="sorting.direction"
            :hide-title="element.hidden"
            :width="element.width ?? 100"
            @resize="resizeColumn"
          ></DynamicColumn>
        </template>
      </draggable>
      <th
        class="text-nowrap"
        scope="col"
        :style="`width: 100px`"
        v-if="actionsColumn"
      ></th>
    </tr>
  </thead>
</template>

<style scoped lang="scss"></style>
