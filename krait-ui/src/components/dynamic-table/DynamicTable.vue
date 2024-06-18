<script setup lang="ts">
import { onMounted } from 'vue';
import { ToastsList } from '@components/toast';
import THead from '@components/dynamic-table/THead.vue';
import useTable from './useTable';
import { useRequest } from '~/api';
import { Responses } from '~/types';

const props = defineProps({
  tableName: {
    type: String,
    required: true,
  },
  apiEndpoint: {
    type: String,
    required: true,
  },
  filtersForm: {
    type: String,
    required: false,
    default: undefined,
  },
});

const { fetchApi, ApiUrl } = useRequest();
const {
  columns,
  isLoading,
  sorting,
  pagination,
  records,
  visibleColumns,
  links,
} = useTable(props.tableName);

const fetchRecords = async (href = null) => {
  isLoading.value = true;

  const url = new ApiUrl(href ?? props.apiEndpoint);
  url.apiParams = {
    sortColumn: sorting.sortBy ?? undefined,
    sortDirection: sorting.direction ?? undefined,
    ipp: pagination.itemsPerPage ?? undefined,
  };
  // url.filtersQuery = $(props.filtersForm).serializeArray();

  const response = await fetchApi(url, 'GET', null, false, true, true);
  const content = (await response.json()) as Responses.ITableResponse;

  // Set the main records
  records.value = content.data;

  /// Set pagination
  pagination.currentPage = content.meta.current_page;
  pagination.itemsPerPage = content.preview_configuration.sort_columns_by
    ? content.preview_configuration.sort_columns_by.ipp
    : 30;
  pagination.totalItems = content.meta.total;
  pagination.links = content.meta.links;

  /// Set the columns
  columns.value = content.columns;
  visibleColumns.value = content.preview_configuration.visible_columns;

  /// Set the sorting
  sorting.sortBy = content.preview_configuration.sort_columns_by.column;
  sorting.direction = content.preview_configuration.sort_columns_by.direction;

  /// Set the additional links
  links.value = content.links;

  isLoading.value = false;
};

onMounted(async () => {
  await fetchRecords();
});

const deleteRecord = async () => {
  alert('Deleting....');
};
</script>

<template>
  <ToastsList />
  <div class="table-responsive table-wrapper">
    <table ref="table" class="table table-hover table-striped dynamic-table">
      <THead :table-name="tableName"></THead>
      <tr v-if="pagination.totalItems === 0 && !isLoading">
        <td colspan="100%">
          <div class="alert alert-secondary">No records found</div>
        </td>
      </tr>
      <tbody>
        <template v-for="record in records" :key="record.uuid">
          <tr>
            <td
              v-for="column in columns"
              :key="column.name"
              class="text-nowrap overflow-hidden text-align-middle align-middle"
              :class="{
                'd-none': !visibleColumns.includes(column.name),
              }"
            >
              <slot name="row" :record="record" :column="column">
                {{ record[column.name] ?? 'N/A' }}
              </slot>
            </td>
            <td class="text-nowrap align-middle">
              <div class="d-flex justify-content-end">
                <slot name="actions" :record="record">
                  <a
                    v-if="record.create_link_url"
                    :href="record.create_link_url as string"
                    class="btn btn-secondary btn-xs me-1"
                    target="_blank"
                  >
                    <i class="fa fa-link"></i>
                  </a>
                  <a
                    v-if="record.update_url"
                    :href="record.update_url as string"
                    class="btn btn-primary btn-xs me-1"
                  >
                    <i class="fa fa-pencil"></i>
                  </a>
                  <a
                    v-else-if="record.preview_url"
                    :href="record.preview_url as string"
                    class="btn btn-primary btn-xs me-1"
                  >
                    <i class="fa-solid fa-eye"></i>
                  </a>
                </slot>
                <button
                  v-if="record.delete_url"
                  class="btn btn-danger btn-xs"
                  @click="deleteRecord(record)"
                >
                  <i class="fa fa-trash"></i>
                </button>
              </div>
            </td>
          </tr>
          <slot name="additionalRows" :record="record" :columns="columns">
          </slot>
        </template>
      </tbody>
    </table>
  </div>
</template>

<style lang="scss">
.dynamic-table {
  table-layout: fixed;

  .cell {
    overflow: hidden;
    text-overflow: ellipsis;
  }
}
</style>
