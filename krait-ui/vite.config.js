import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import dts from 'vite-plugin-dts';
import path from 'path';

// https://vitejs.dev/config/
export default defineConfig({
  build: {
    emptyOutDir: false,
    lib: {
      entry: path.resolve(__dirname, 'src/main.ts'),
      name: 'KraitUi',
      fileName: 'krait-ui',
    },
    rollupOptions: {
      external: ['vue', 'bootstrap', 'vue3-spinner', 'vuedraggable'],
      output: {
        globals: {
          Vue: 'vue',
        },
      },
    },
  },
  resolve: {
    alias: {
      '~': path.resolve(__dirname, './src'),
      '@components': path.resolve(__dirname, './src/components'),
    },
  },
  plugins: [vue(), dts()],
});
