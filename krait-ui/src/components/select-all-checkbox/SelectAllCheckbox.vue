<script setup lang="ts">
import { ref, watch } from 'vue';
import { useTable } from '~/mixins';

const props = defineProps<{
  tableName: string;
}>();

const { selectedRows, records } = useTable(props.tableName);
const checkBoxInput = ref<HTMLInputElement | null>(null);
const isAllSelected = ref(false);
const isIndeterminate = ref(false);

const toggleSelectAll = () => {
  if (isIndeterminate.value || !isAllSelected.value) {
    selectedRows.value = records.value.map((record) => record.uuid);
  } else {
    selectedRows.value = [];
  }
};

watch(
  selectedRows,
  (newSelectedRows) => {
    if (checkBoxInput.value) {
      if (newSelectedRows.length === records.value.length) {
        isAllSelected.value = true;
        isIndeterminate.value = false;
        checkBoxInput.value.indeterminate = false;
      } else {
        isAllSelected.value = false;
        isIndeterminate.value = newSelectedRows.length > 0;
        checkBoxInput.value.indeterminate = isIndeterminate.value;
      }
    }
  },
  {
    deep: true,
  },
);
</script>

<template>
  <input
    ref="checkBoxInput"
    type="checkbox"
    :checked="isAllSelected"
    @click="toggleSelectAll"
  />
</template>
