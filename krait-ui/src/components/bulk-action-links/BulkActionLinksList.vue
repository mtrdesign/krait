<script setup lang="ts">
import { useConfirmation, useDispatcher, useTable } from '~/mixins';
import { DeleteRecord, FetchRecords } from '~/actions';

const props = defineProps({
  tableName: {
    type: String,
    required: true,
  },
});

const { isLoading, bulkActionLinks, selectedRows, isSelectableRows } = useTable(
  props.tableName,
);
const { dispatch } = useDispatcher(props.tableName);
const { ask } = useConfirmation();

const bulkDelete = async () => {
  if (selectedRows.value.length <= 0) return;

  const isConfirmed = await ask(
    'Are you sure that you want to delete those record?',
  );

  if (isConfirmed && bulkActionLinks.value.delete) {
    await dispatch<DeleteRecord>(DeleteRecord, {
      url: bulkActionLinks.value.delete,
      body: { data: selectedRows.value },
    });
    await dispatch<FetchRecords>(FetchRecords, {});
  }
};
</script>

<template>
  <div v-if="isSelectableRows">
    <button
      v-if="bulkActionLinks?.delete"
      class="btn btn-danger"
      :disabled="selectedRows.length == 0 || isLoading"
      @click="bulkDelete"
    >
      Delete selected
    </button>
  </div>
</template>
