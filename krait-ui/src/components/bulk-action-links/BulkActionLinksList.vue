<script setup lang="ts">
import { useConfirmation, useDispatcher, useTable } from '~/mixins';
import { DeleteRecord, FetchRecords } from '~/actions';
import { Trash } from '@components/icons';

const props = defineProps<{
  tableName: string;
}>();

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
  <div>
    <div v-if="isSelectableRows">
      <button
        v-if="bulkActionLinks?.delete"
        type="button"
        class="btn btn-danger d-flex gap-1"
        :disabled="selectedRows.length == 0 || isLoading"
        @click="bulkDelete"
      >
        <Trash :width="18" />
        Delete selected
      </button>
    </div>
  </div>
</template>
