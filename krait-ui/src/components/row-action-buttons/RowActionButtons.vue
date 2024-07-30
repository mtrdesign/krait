<script setup lang="ts">
import { Eye, Pencil, Trash } from '@components/icons';
import { useConfirmation, useDispatcher } from '~/mixins';
import { DeleteRecord, FetchRecords } from '~/actions';

const props = defineProps({
  tableName: {
    type: String,
    required: true,
  },
  actionLinks: {
    type: Object,
    required: true,
  },
});

const { dispatch } = useDispatcher(props.tableName);
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
    await dispatch<FetchRecords>(FetchRecords, {});
  }
};
</script>

<template>
  <template v-for="(url, action) in actionLinks" :key="key">
    <button
      @click="() => followLink(url)"
      class="btn btn-sm btn-success me-1"
      v-if="action == 'view'"
    >
      <Eye :width="18" />
    </button>
    <button
      @click="() => followLink(url)"
      class="btn btn-sm btn-info me-1"
      v-else-if="action == 'edit'"
    >
      <Pencil :width="18" />
    </button>
    <button
      @click="() => onDelete(url)"
      class="btn btn-sm btn-danger me-1"
      v-else-if="action == 'delete'"
    >
      <Trash width="18" />
    </button>
  </template>
</template>

<style scoped lang="scss"></style>
