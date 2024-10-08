<script setup lang="ts">
import { FetchRecords, SaveRecordsPerPage } from '~/actions';
import { useDispatcher, useTable, useTableConfiguration } from '~/mixins';
import { watch } from 'vue';

const props = defineProps({
  tableName: {
    type: String,
    required: true,
  },
});

const { pagination, isLoading } = useTable(props.tableName);
const configuration = useTableConfiguration(props.tableName);
const { dispatch } = useDispatcher(props.tableName);

const fetchPaginationLink = async (url: string) => {
  await dispatch<FetchRecords>(FetchRecords, {
    url,
  });
};

const onChange = async () => {
  await dispatch<SaveRecordsPerPage>(SaveRecordsPerPage, {});
  await dispatch<FetchRecords>(FetchRecords, {
    tableConfigurationProps: configuration,
  });
};
</script>

<template>
  <div
    class="d-flex justify-content-between mt-3"
    v-if="pagination.totalItems && pagination.totalItems > 0"
  >
    <nav aria-label="Page navigation example">
      <ul class="pagination">
        <li class="page-item" v-for="link in pagination.links">
          <a
            class="page-link"
            :class="{
              active: link.active,
              disabled: (!link.url && !link.active) || isLoading,
            }"
            @click="() => (link.url ? fetchPaginationLink(link.url) : null)"
            v-html="link.label"
          >
          </a>
        </li>
      </ul>
    </nav>
    <div class="form-group">
      <select
        v-model="pagination.itemsPerPage"
        @change="onChange"
        class="form-control form-select form-select-sm"
        :disabled="isLoading"
      >
        <option value="30">30 records</option>
        <option value="50">50 records</option>
        <option value="100">100 records</option>
        <option value="250">250 records</option>
      </select>
    </div>
  </div>
</template>

<style scoped lang="scss"></style>
