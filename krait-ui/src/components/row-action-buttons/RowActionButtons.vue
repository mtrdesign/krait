<script setup lang="ts">
import { Eye, Pencil, Trash } from '@components/icons';
import {
  useConfirmation,
  useDispatcher,
  useTableConfiguration,
} from '~/mixins';
import { DeleteRecord, FetchRecords } from '~/actions';

const props = defineProps<{
  tableName: string;
  actionLinks: object;
}>();

const { dispatch } = useDispatcher(props.tableName);
const configuration = useTableConfiguration(props.tableName);
const { ask } = useConfirmation();

const followLink = (link: string) => {
  window.location.href = link;
};

const onDelete = async (url: string) => {
  const isConfirmed = await ask(
    'Are you sure that you want to delete this record?',
  );

  if (isConfirmed) {
    await dispatch<DeleteRecord>(DeleteRecord, { url });
    await dispatch<FetchRecords>(FetchRecords, {
      tableConfigurationProps: configuration,
    });
  }
};
</script>

<template>
  <template v-for="(url, action) in actionLinks" :key="action">
    <button
      v-if="action == 'view'"
      type="button"
      class="btn btn-sm btn-success me-1"
      @click="() => followLink(url)"
    >
      <Eye :width="18" />
    </button>
    <button
      v-else-if="action == 'edit'"
      type="button"
      class="btn btn-sm btn-info me-1"
      @click="() => followLink(url)"
    >
      <Pencil :width="18" />
    </button>
    <button
      v-else-if="action == 'delete'"
      type="button"
      class="btn btn-sm btn-danger me-1"
      @click="() => onDelete(url)"
    >
      <Trash :width="18" />
    </button>
  </template>
</template>

<style scoped lang="scss"></style>
