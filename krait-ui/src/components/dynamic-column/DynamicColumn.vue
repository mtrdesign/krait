<script setup lang="ts">
import { defineEmits, defineProps, onMounted, reactive, UnwrapNestedRefs } from "vue";
import { ArrowDown, ArrowUp } from '@components/icons';

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

const mouseMove = (e: MouseEvent) => {
  state.xAxisNewCoords = state.xAxisCurrentCoords - e.clientX;
  state.colWidth = Math.floor(
    state.rect.width - (state.xAxisCurrentCoords - e.clientX),
  );
  emit('resize', e, props.name, state.colWidth);
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
</script>

<template>
  <th
    :key="name"
    scope="col"
    :class="{ 'd-none': !isVisible, 'fixed-column': !isResizable }"
    :style="{ width: `${width >= 50 ? width : 50}px` }"
  >
    <div class="d-flex justify-content-between text-truncate head-content">
      <div
        class="flex-grow-1"
        :class="{ 'cursor-pointer': isSortable }"
        @click="toggleSort()"
      >
        <span v-if="!hideTitle">
          {{ title }}
        </span>
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
        <!--        <a-->
        <!--          v-if="isActive && sortDirection === 'desc'"-->
        <!--          :class="{ active: isActive }"-->
        <!--          href="javascript:void(0)"-->
        <!--          @click="emit('sort', name, 'asc')"-->
        <!--        >-->
        <!--          <ArrowDown></ArrowDown>-->
        <!--          <i class="fa fa-angle-down"></i>-->
        <!--        </a>-->
        <!--        <a-->
        <!--          v-else-->
        <!--          href="javascript:void(0)"-->
        <!--          :class="{ active: isActive }"-->
        <!--          @click="emit('sort', name, 'desc')"-->
        <!--        >-->
        <!--          &lt;!&ndash;          <ArrowUp color="#0D6EFD"></ArrowUp>&ndash;&gt;-->
        <!--          <i class="fa fa-angle-up"></i>-->
        <!--        </a>-->
      </span>
    </div>
    <div
      v-if="isResizable"
      class="col-resizer"
      @mousedown="resizeCols($event)"
    ></div>
  </th>
</template>

<style scoped lang="scss">
th {
  background: red;
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
  right: 7px;
}

.head-content {
  margin-right: 15px;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>
