<script setup lang="ts">
import { useConfirmation } from '~/mixins';
import { Modal } from '@components/modal';

const { isOpened, question, agree, cancel } = useConfirmation();
</script>

<template>
  <Modal
    title="Confirmation"
    @close="cancel"
    @continue="agree"
    :is-opened="isOpened"
  >
    <template #body>
      {{ question }}
    </template>
  </Modal>
</template>

<style scoped lang="scss"></style>
