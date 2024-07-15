<script setup lang="ts">
import { onMounted } from 'vue';
import { useDispatcher, useTable } from '~/mixins';
import { ToastsList } from '@components/toast';
import { THead } from '@components/thead';
import { Pagination } from '@components/pagination';
import { ColumnsSelectionDropdown } from '@components/columns-selection-dropdown';
import { FetchRecords } from '~/actions';
import { RowActionButtons } from '@components/row-action-buttons';
import ForbiddenScreen from './ForbiddenScreen.vue';

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

const { columns, isLoading, records, visibleColumns, isAuthorized } = useTable(
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

const refreshTable = async () => {
  await dispatch<FetchRecords>(FetchRecords, {});
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
  <div class="d-flex justify-content-end mb-3" v-if="isAuthorized">
    <ColumnsSelectionDropdown
      :table-name="tableName"
    ></ColumnsSelectionDropdown>
  </div>
  <div class="table-responsive table-wrapper" ref="wrapper">
    <template v-if="isAuthorized">
      <div class="table-responsive table-wrapper">
        <table
          ref="table"
          class="table table-hover table-striped dynamic-table"
          :class="{ 'table-secondary': isLoading }"
        >
          <THead
            :table-name="tableName"
            :actions-column="actionsColumn"
          ></THead>
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
                  <slot
                    name="actions"
                    :record="record"
                    :refreshTable="refreshTable"
                  >
                    <RowActionButtons
                      :table-name="tableName"
                      :action-links="record.action_links"
                    />
                  </slot>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
      <Pagination :table-name="tableName" />
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
