<script setup lang="ts">
import {
  defineEmits,
  defineProps,
  onMounted,
  reactive,
  UnwrapNestedRefs,
} from 'vue';
import {ArrowDown, ArrowUp} from '@components/icons';
import { debounce } from "~/framework/utils";

interface IColumnState {
  xAxisCurrentCoords: any;
  xAxisNewCoords: any;
  rect: any;
  col: any;
  colWidth: any;
}

const emit = defineEmits(['sort', 'resize']);
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

const state: UnwrapNestedRefs<IColumnState> = reactive({
  xAxisCurrentCoords: null,
  xAxisNewCoords: null,
  rect: null,
  col: null,
  colWidth: 'auto',
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

const debouncedEmitResize = debounce((e, name, colWidth) => {
  emit('resize', e, name, colWidth);
}, 300);

const mouseMove = (e: MouseEvent) => {
  state.xAxisNewCoords = state.xAxisCurrentCoords - e.clientX;
  state.colWidth = Math.floor(
      state.rect.width - (state.xAxisCurrentCoords - e.clientX),
  );

  debouncedEmitResize(e, props.name, state.colWidth);
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

onMounted(() => {
  state.colWidth = props.width;
})

</script>

<template>
  <th
      :key="name"
      scope="col"
      :class="{ 'd-none': !isVisible, 'fixed-column': !isResizable }"
      :style="{ width: `${state.colWidth >= 50 ? state.colWidth : 50}px` }"
  >
    <div class="d-inline-block text-truncate pe-1" style="width: 95%">
      {{ title }}
    </div>
    <span v-if="isSortable" class="sort" style="z-index: 1">
      <ArrowDown
          :color="isActive ? '#0D6EFD' : '#adb5bd'"
          v-if="isActive && sortDirection === 'desc'"
          @click="() => emit('sort', name, 'asc')"
      ></ArrowDown>
      <ArrowUp
          :color="isActive ? '#0D6EFD' : '#adb5bd'"
          @click="() => emit('sort', name, 'desc')"
          v-else
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
