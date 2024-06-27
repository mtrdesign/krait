<script setup lang="ts">
import { onMounted } from 'vue';
import { PulseLoader } from 'vue3-spinner';
import { useDispatcher, useTable } from '~/mixins';
import { ToastsList } from '@components/toast';
import { THead } from '@components/thead';
import { Pagination } from '@components/pagination';
import { ColumnsSelectionDropdown } from '@components/columns-selection-dropdown';
import { FetchRecords } from '~/actions';

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

const { columns, isLoading, pagination, records, visibleColumns } = useTable(
  props.tableName,
);
const { dispatch } = useDispatcher(props.tableName);

const initFiltersListener = () => {
  if (!props.filtersForm) {
    return;
  }
  const form = document.querySelector<HTMLFormElement>(props.filtersForm);
  if (!form) {
    throw new Error('No filters form found.');
  }

  form.addEventListener(
    'submit',
    (e) => {
      dispatch<FetchRecords>(FetchRecords, {
        filtersForm: form,
      });
      e.preventDefault();
    },
    false,
  );
};

onMounted(async () => {
  await dispatch<FetchRecords>(FetchRecords, {
    isInitialFetch: true,
  });

  initFiltersListener();
});
</script>

<template>
  <ToastsList />
  <div class="d-flex justify-content-end mb-3">
    <ColumnsSelectionDropdown
      :table-name="tableName"
    ></ColumnsSelectionDropdown>
  </div>
  <div class="table-responsive table-wrapper" ref="wrapper">
    <div class="table-responsive table-wrapper">
      <div
        class="d-flex justify-content-start"
        :class="{ invisible: !isLoading }"
      >
        <PulseLoader
          :loading="true"
          color="#0d6efd"
          size="10px"
          margin="2px"
          radius="100%"
        />
      </div>
      <table
        ref="table"
        class="table table-hover table-striped dynamic-table"
        :class="{ 'table-secondary': isLoading }"
      >
        <THead :table-name="tableName" :actions-column="actionsColumn"></THead>
        <tr v-if="records.length === 0 && !isLoading">
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
    <Pagination :table-name="tableName" />
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
