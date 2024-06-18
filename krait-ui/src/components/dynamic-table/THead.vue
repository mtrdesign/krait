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
  selectable: {
    type: Boolean,
    required: false,
    default: false,
  },
});

const { columns, visibleColumns, sorting } = useTable(props.tableName);
const { ApiUrl, apiClient } = useRequest();

const dragging = ref(false);

const reorderColumn = async ({ moved }: { moved: boolean }) => {
  if (!moved) {
    return;
  }

  const url = new ApiUrl(window.Krait.routes.reorderColumns);
  const data: {
    route_uri: string;
    table_name: string;
    display_order_settings: string[];
  } = {
    route_uri: window.Krait.routeUri,
    table_name: props.tableName,
    display_order_settings: [],
  };

  columns.value.forEach((column) => {
    if (visibleColumns.value.includes(column.name)) {
      data['display_order_settings'].push(column.name);
    }
  });

  // @TODO! Improve the fetching here.
  await apiClient.fetch(url, {
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

  const url = new ApiUrl(window.Krait.routes.resizeColumn);

  // @TODO! Improve the fetching here.
  await apiClient.fetch(url, {
    method: 'POST',
    body: JSON.stringify({
      route_uri: window.Krait.routeUri,
      table_name: props.tableName,
      column_name: column.name,
      column_width: column.width!,
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
      <th class="text-nowrap" scope="col" :style="`width: 100px`"></th>
    </tr>
  </thead>
</template>

<style scoped lang="scss"></style>
