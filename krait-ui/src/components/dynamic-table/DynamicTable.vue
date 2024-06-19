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
  actionsColumn: {
    type: Boolean,
    required: false,
    default: false,
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

const fetchRecords = async (
  href: string | null = null,
  setColumns: boolean = true,
) => {
  isLoading.value = true;

  const url = new ApiUrl(href ?? props.apiEndpoint, false);
  url.apiParams = {
    sortColumn: sorting.sortBy ?? undefined,
    sortDirection: sorting.direction ?? undefined,
    ipp: pagination.itemsPerPage ?? undefined,
  };

  if (props.filtersForm) {
    const form = document.querySelector<HTMLFormElement>(props.filtersForm);
    if (!form) {
      throw new Error('No filters form found.');
    }

    // Create a new FormData object
    const formData = new FormData(form);

    // Create an array to hold the name/value pairs
    const pairs = [];

    // Add each name/value pair to the array
    for (const [name, value] of formData) {
      if (value instanceof File) {
        continue;
      }
      pairs.push({ name, value });
    }

    url.filtersQuery = pairs;
  }

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
  if (setColumns) {
    columns.value = content.columns;
    visibleColumns.value = content.preview_configuration.visible_columns;
  }

  /// Set the sorting
  sorting.sortBy = content.preview_configuration.sort_columns_by.column;
  sorting.direction = content.preview_configuration.sort_columns_by.direction;

  /// Set the additional links
  links.value = content.links;

  isLoading.value = false;
};

onMounted(async () => {
  await fetchRecords();

  if (props.filtersForm) {
    const form = document.querySelector<HTMLFormElement>(props.filtersForm);
    if (!form) {
      throw new Error('No filters form found.');
    }

    form.addEventListener(
      'submit',
      (e) => {
        fetchRecords();
        e.preventDefault();
      },
      false,
    );
  }
});
</script>

<template>
  <ToastsList />
  <div class="table-responsive table-wrapper">
    <table ref="table" class="table table-hover table-striped dynamic-table">
      <THead :table-name="tableName" :actions-column="actionsColumn"></THead>
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
              <slot name="actions" :record="record"></slot>
            </td>
          </tr>
          <slot
            name="additionalRows"
            :record="record"
            :columns="columns"
            v-if="actionsColumn"
          >
          </slot>
        </template>
      </tbody>
    </table>
  </div>
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
            @click="fetchRecords(link.url ? link.url : undefined, false)"
            v-html="link.label"
          >
          </a>
        </li>
      </ul>
    </nav>
    <div class="form-group">
      <select
        v-model="pagination.itemsPerPage"
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

<style lang="scss">
.dynamic-table {
  table-layout: fixed;

  .cell {
    overflow: hidden;
    text-overflow: ellipsis;
  }
}
</style>
