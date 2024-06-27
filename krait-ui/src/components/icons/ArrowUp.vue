<script setup lang="ts">
const props = defineProps({
  width: {
    type: Number,
    required: false,
    default: 24,
  },
  height: {
    type: Number,
    required: false,
    default: 24,
  },
  color: {
    type: String,
    required: false,
    default: '#FFF',
  },
  onClick: {
    type: Function,
    required: false,
    default: undefined,
  },
});
</script>

<template>
  <svg
    :width="`${width}px`"
    :height="`${height}px`"
    viewBox="0 0 24 24"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
    :class="{ clickable: typeof onClick !== 'undefined' }"
    @click="() => (typeof onClick !== 'undefined' ? onClick() : null)"
  >
    <path
      d="M12 5V19M12 5L6 11M12 5L18 11"
      :stroke="color"
      stroke-width="2"
      stroke-linecap="round"
      stroke-linejoin="round"
    />
  </svg>
</template>

<style scoped lang="scss">
.clickable {
  cursor: pointer;
}
</style>
