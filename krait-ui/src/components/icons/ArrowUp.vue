<script setup lang="ts">
withDefaults(
  defineProps<{
    width?: number;
    height?: number;
    color?: string;
    onClick?: () => void;
  }>(),
  {
    width: 24,
    height: 24,
    color: '#FFF',
    onClick: undefined,
  },
);
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
