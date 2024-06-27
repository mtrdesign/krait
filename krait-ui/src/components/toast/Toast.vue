<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Toast } from 'bootstrap';

defineProps({
  message: {
    type: String,
    required: true,
  },
  type: {
    type: String,
    required: false,
    default: 'primary',
  },
  timing: {
    type: Number,
    required: false,
    default: 10_000,
  },
});

const domEl = ref<HTMLDivElement | null>(null);

onMounted(() => {
  if (!domEl.value) {
    throw new Error('No Toast DOM element found.)');
  }

  const toast = new Toast(domEl.value);
  toast.show();
});
</script>

<template>
  <div
    ref="domEl"
    :class="`toast align-items-center bg-${type} border-0`"
    role="alert"
    aria-live="assertive"
    aria-atomic="true"
    :data-bs-delay="timing"
  >
    <div class="d-flex">
      <div class="toast-body">
        {{ message }}
      </div>
      <button
        type="button"
        class="btn-close btn-close-white me-2 m-auto"
        data-bs-dismiss="toast"
        aria-label="Close"
      ></button>
    </div>
  </div>
</template>

<style scoped lang="scss"></style>
