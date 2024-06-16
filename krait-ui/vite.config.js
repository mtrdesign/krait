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
      formats: ['es'],
      name: 'Krait',
    },
    rollupOptions: {
      external: ['vue', 'bootstrap'],
      output: {
        globals: {
          Vue: 'vue',
        },
      },
    },
  },
  plugins: [vue(), dts()],
});
