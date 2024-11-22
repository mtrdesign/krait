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

const resizeColumn = async (_e: MouseEvent, name: string, width: number) => {
  await dispatch<ResizeColumn>(ResizeColumn, {
    name,
    width,
  });
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
        class="text-nowrap"
        scope="col"
        :style="`width: 35px`"
        v-if="isSelectableRows"
      >
        <SelectAllCheckbox :table-name="props.tableName"></SelectAllCheckbox>
      </th>
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
            :is-sortable="element.sortable"
            :is-active="sorting.sortBy === element.name"
            :is-resizable="!element.fixed"
            :sort-direction="sorting.direction"
            :hide-title="element.hideLabel"
            :width="element.width ?? 100"
            @resize="resizeColumn"
            @sort="sortColumn"
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
