<script setup lang="ts">
import { useToast } from '~/mixins';
import Toast from './Toast.vue';

const { messages } = useToast();
</script>

<template>
  <div class="toast-container position-fixed top-0 end-0 p-3">
    <Toast
      v-for="(message, index) in messages"
      :key="index"
      :message="message.content"
      :timing="message.timing"
      :type="message.type"
    ></Toast>
  </div>
</template>

<style scoped lang="scss">
// @TODO: thing of a better way to ensure that the element is on top.
.toast-container {
  z-index: 9999;
}
</style>
