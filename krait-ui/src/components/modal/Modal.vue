<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import { Modal as BSModal } from 'bootstrap';

const emit = defineEmits(['close', 'continue']);

const props = defineProps({
  isOpened: {
    type: Boolean,
    required: false,
    default: false,
  },
  title: {
    type: String,
    required: false,
    default: undefined,
  },
});

const modalEl = ref<HTMLDivElement>();
const modalBS = ref<BSModal>();

onMounted(() => {
  if (modalEl.value) {
    modalBS.value = new BSModal(modalEl.value);
  }
});

watch(
  () => props.isOpened,
  (isOpened) => {
    if (!modalBS.value) {
      return;
    }

    if (isOpened) {
      modalBS.value.show();
    } else {
      modalBS.value.hide();
    }
  },
);
</script>

<template>
  <!-- Modal -->
  <div class="modal fade" tabindex="-1" ref="modalEl">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" v-if="title">
          <h5 class="modal-title">{{ title }}</h5>
          <button type="button" class="btn-close" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <slot name="body"> </slot>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            @click="() => emit('close')"
          >
            Close
          </button>
          <button
            type="button"
            class="btn btn-primary"
            @click="() => emit('continue')"
          >
            Continue
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss"></style>
