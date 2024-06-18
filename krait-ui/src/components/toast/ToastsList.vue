<script setup>
import { messages } from './useToast';
import Toast from './Toast.vue';
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
.toast-container {
  z-index: 9999;
}
</style>
