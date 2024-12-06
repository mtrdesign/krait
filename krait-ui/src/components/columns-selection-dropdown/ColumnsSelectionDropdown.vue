<script setup lang="ts">
import { useDispatcher, useTable } from '~/mixins';
import { HideColumn } from '~/actions';
import { Settings } from '@components/icons';

const props = defineProps({
  tableName: {
    type: String,
    required: true,
  },
});

const { columns, visibleColumns, isLoading } = useTable(props.tableName);
const { dispatch } = useDispatcher(props.tableName);

const toggleColumn = async (e: MouseEvent, columnName: string) => {
  if (!e.target) {
    return;
  }
  if (e.target.tagName !== 'INPUT') {
    e.stopPropagation();
    e.preventDefault();
  }

  await dispatch<HideColumn>(HideColumn, {
    name: columnName,
  });
};
</script>

<template>
  <div class="dropdown">
    <button
      id="columns-selection-dropdown"
      class="btn btn-sm btn-secondary"
      type="button"
      data-bs-toggle="dropdown"
      aria-expanded="false"
      :disabled="isLoading"
    >
      <Settings :width="18" />
    </button>
    <ul
      class="dropdown-menu allow-focus py-1"
      aria-labelledby="columns-selection-dropdown"
      style="z-index: 9999900"
    >
      <div
        v-if="isLoading"
        class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center"
      >
        <PulseLoader
          :loading="isLoading"
          color="#498fff"
          size="10px"
          margin="2px"
          radius="100%"
        />
      </div>
      <li v-for="(column, index) in columns" :key="column.name">
        <label
          :for="`${column.name}--checkbox`"
          class="form-check-label text-nowrap d-block px-2 py-1 dropdown-item fs-85"
          @click="toggleColumn($event, column.name)"
        >
          <input
            :id="`${column.name}--checkbox`"
            type="checkbox"
            :checked="visibleColumns.includes(column.name)"
            class="form-check-input"
            :disabled="isLoading"
          />
          {{ column.label }}
        </label>
      </li>
    </ul>
  </div>
</template>

<style lang="scss" scoped>
.dropdown-menu {
  max-height: 500px;
  overflow-y: scroll;
  z-index: 1002;

  li {
    &.active {
      label {
        background-color: #cbcbff;
        font-weight: bold;

        &:hover,
        &:focus {
          background-color: #b8b8ff;
        }
      }
    }

    label {
      cursor: pointer;
      clear: both;
      transition: background-color 0.4s ease;

      &:hover,
      &:focus {
        background-color: #f5f5f5;
      }
    }

    input {
      margin: 0 5px;
      top: 2px;
      position: relative;
    }
  }
}

.fs-85 {
  font-size: 0.85rem;
}
</style>
