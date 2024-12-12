<script setup lang="ts">
import { onMounted } from 'vue';
import { useDispatcher, useTable, useTableConfiguration } from '~/mixins';
import { ToastsList } from '@components/toast';
import { THead } from '@components/thead';
import { Pagination } from '@components/pagination';
import { ColumnsSelectionDropdown } from '@components/columns-selection-dropdown';
import { FetchRecords, FetchStructure } from '~/actions';
import { RowActionButtons } from '@components/row-action-buttons';
import ForbiddenScreen from './ForbiddenScreen.vue';
import ConfirmationDialog from '@components/confirmation-dialog/ConfirmationDialog.vue';
import { BulkActionLinksList } from '@components/bulk-action-links';
import { Table } from '~/types';

const props = withDefaults(
  defineProps<{
    apiEndpoint: string;
    filtersForm?: string;
    actionsColumn?: boolean;
    apiQueryParameters?: object;
  }>(),
  {
    actionsColumn: false,
    filtersForm: undefined,
    apiQueryParameters: () => ({}),
  },
);

const {
  columns,
  isLoading,
  records,
  visibleColumns,
  isAuthorized,
  isSelectableRows,
  selectedRows,
} = useTable(props.apiEndpoint);
const configuration = useTableConfiguration(
  props.apiEndpoint,
  props as Table.ITableConfiguration,
);
const { dispatch } = useDispatcher(props.apiEndpoint);

const initFiltersListener = () => {
  if (!configuration.filtersForm) {
    return;
  }

  // getting the form as a HTMLFormElement
  const form = document.querySelector<HTMLFormElement>(
    configuration.filtersForm,
  );

  if (!form) {
    throw new Error('No filters form found.');
  }

  form.addEventListener(
    'submit',
    (e) => {
      dispatch<FetchRecords>(FetchRecords, {});
      e.preventDefault();
    },
    false,
  );
};

const refreshTable = async () => {
  await dispatch<FetchRecords>(FetchRecords, {});
};

const toggleRowSelection = (recordUuid: string) => {
  if (selectedRows.value.includes(recordUuid)) {
    selectedRows.value = selectedRows.value.filter(
      (uuid) => uuid !== recordUuid,
    );
  } else {
    selectedRows.value.push(recordUuid);
  }
};

onMounted(async () => {
  const fetchStructurePromise = dispatch<FetchStructure>(FetchStructure, {});
  const fetchRecordsPromise = dispatch<FetchRecords>(FetchRecords, {});

  await Promise.all([fetchStructurePromise, fetchRecordsPromise]);
  initFiltersListener();
});
</script>

<template>
  <ToastsList />
  <ConfirmationDialog />
  <div v-if="isAuthorized" class="d-flex justify-content-end mb-3 gap-2">
    <BulkActionLinksList :tableName="apiEndpoint" />
    <ColumnsSelectionDropdown :tableName="apiEndpoint" />
  </div>
  <div ref="wrapper" class="table-responsive table-wrapper">
    <template v-if="isAuthorized">
      <div class="table-responsive table-wrapper">
        <table
          ref="table"
          class="table table-hover table-striped dynamic-table"
          :class="{ 'table-secondary': isLoading }"
        >
          <THead
            :tableName="apiEndpoint"
            :actionsColumn="actionsColumn"
            @refreshTable="refreshTable"
          ></THead>
          <tr v-if="records.length === 0 && !isLoading">
            <td colspan="100%">
              <div class="alert alert-secondary">No records found</div>
            </td>
          </tr>
          <tbody>
            <template v-for="record in records" :key="record.uuid">
              <tr>
                <td v-if="isSelectableRows">
                  <input
                    type="checkbox"
                    :value="record.uuid"
                    :checked="selectedRows.includes(record.uuid)"
                    @change="toggleRowSelection(record.uuid)"
                  />
                </td>
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
                  <slot
                    name="actions"
                    :record="record"
                    :refreshTable="refreshTable"
                  >
                    <RowActionButtons
                      :tableName="apiEndpoint"
                      :actionLinks="record.action_links"
                    />
                  </slot>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
      <Pagination :tableName="apiEndpoint" />
    </template>
    <ForbiddenScreen v-else />
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
