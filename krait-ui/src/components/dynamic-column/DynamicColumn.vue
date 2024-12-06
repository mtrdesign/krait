<script setup lang="ts">
import {
  defineEmits,
  defineProps,
  reactive,
  watch,
  UnwrapNestedRefs,
} from 'vue';
import { ArrowDown, ArrowUp } from '@components/icons';
import { debounce } from '~/framework/utils';

interface IColumnState {
  xAxisCurrentCoords: any;
  xAxisNewCoords: any;
  rect: any;
  col: any;
  colWidth: any;
}

const props = defineProps([
  'title',
  'name',
  'hideTitle',
  'isVisible',
  'isSortable',
  'isActive',
  'isResizable',
  'sortDirection',
  'width',
]);
const emit = defineEmits(['sort', 'resize']);
const state: UnwrapNestedRefs<IColumnState> = reactive({
  xAxisCurrentCoords: null,
  xAxisNewCoords: null,
  rect: null,
  col: null,
  colWidth: props.width, // Initialize with props.width
});

const toggleSort = () => {
  if (!props.isSortable) {
    return;
  }

  let nextDirection = 'asc';
  if (props.sortDirection === 'asc') {
    nextDirection = 'desc';
  }

  emit('sort', props.name, nextDirection);
};

const mouseMove = (e: MouseEvent) => {
  state.xAxisNewCoords = state.xAxisCurrentCoords - e.clientX;
  const newWidth = Math.max(
    50,
    Math.floor(state.rect.width - (state.xAxisCurrentCoords - e.clientX)),
  );
  state.colWidth = newWidth;
  emit('resize', e, props.name, newWidth);
};

const resizeCols = (event: MouseEvent) => {
  if (!event.target) {
    return;
  }

  state.xAxisCurrentCoords = event.clientX;
  state.col = event.target.parentNode;
  state.rect = state.col.getBoundingClientRect();
  document.addEventListener('mousemove', mouseMove);
  document.addEventListener('mouseup', resizeStop);
  event.preventDefault();
  event.stopPropagation();
};

const resizeStop = (): void => {
  document.removeEventListener('mousemove', mouseMove);
  document.removeEventListener('mouseup', resizeStop);
};

// Watch for width prop changes
watch(
  () => props.width,
  (newWidth) => {
    state.colWidth = newWidth;
  },
);
</script>

<template>
  <th
    :key="name"
    scope="col"
    :class="{ 'd-none': !isVisible, 'fixed-column': !isResizable }"
    :style="{ width: `${Math.max(50, state.colWidth)}px` }"
  >
    <div class="d-inline-block text-truncate pe-1" style="width: 95%">
      {{ title }}
    </div>
    <span v-if="isSortable" class="sort" style="z-index: 1">
      <ArrowDown
        v-if="isActive && sortDirection === 'desc'"
        :color="isActive ? '#0D6EFD' : '#adb5bd'"
        @click="() => emit('sort', name, 'asc')"
      ></ArrowDown>
      <ArrowUp
        v-else
        :color="isActive ? '#0D6EFD' : '#adb5bd'"
        @click="() => emit('sort', name, 'desc')"
      ></ArrowUp>
    </span>
    <div
      v-if="isResizable"
      class="col-resizer"
      @mousedown="resizeCols($event)"
    ></div>
  </th>
</template>

<style scoped lang="scss">
th {
  border-right: 1px solid #aaa;
  position: relative;
  width: 200px;

  &.fixed-column {
    width: 50px !important;
    border-right: none;
  }
}

.col-resizer {
  position: absolute;
  right: -10px;
  top: 0;
  width: 20px;
  height: 100%;
  background-color: transparent;
  cursor: col-resize;
}

.sort {
  position: absolute;
  right: 3px;
}
</style>
