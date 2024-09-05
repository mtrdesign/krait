<script setup lang="ts">
import {onMounted} from 'vue';
import {useDispatcher, useTable} from '~/mixins';
import {ToastsList} from '@components/toast';
import {THead} from '@components/thead';
import {Pagination} from '@components/pagination';
import {ColumnsSelectionDropdown} from '@components/columns-selection-dropdown';
import {FetchRecords} from '~/actions';
import {RowActionButtons} from '@components/row-action-buttons';
import ForbiddenScreen from './ForbiddenScreen.vue';
import ConfirmationDialog from '@components/confirmation-dialog/ConfirmationDialog.vue';

const props = defineProps({
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
  apiQueryParameters: {
    type: Object,
    required: false,
    default: {},
  },
});

const {
  columns,
  isLoading,
  records,
  visibleColumns,
  isAuthorized,
  queryParameters,
} = useTable(props.apiEndpoint);
const {dispatch} = useDispatcher(props.apiEndpoint);
queryParameters.value = props.apiQueryParameters;

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
  if (!props.filtersForm) {
    await dispatch<FetchRecords>(FetchRecords, {});
    return;
  }

  const form = document.querySelector<HTMLFormElement>(props.filtersForm);
  if (!form) {
    throw new Error('No filters form found.');
  }

  await dispatch<FetchRecords>(FetchRecords, {
    filtersForm: form,
  });
};

onMounted(async () => {
  await dispatch<FetchRecords>(FetchRecords, {
    isInitialFetch: true,
  });
  initFiltersListener();
});
</script>

<template>
  <ToastsList/>
  <ConfirmationDialog/>
  <div class="d-flex justify-content-end mb-3" v-if="isAuthorized">
    <ColumnsSelectionDropdown
        :table-name="apiEndpoint"
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
              :table-name="apiEndpoint"
              :actions-column="actionsColumn"
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
                      :table-name="apiEndpoint"
                      :action-links="record.action_links"
                  />
                </slot>
              </td>
            </tr>
          </template>
          </tbody>
        </table>
      </div>
      <Pagination :table-name="apiEndpoint"/>
    </template>
    <ForbiddenScreen v-else/>
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
