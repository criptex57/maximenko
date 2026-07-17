import { defineConfig } from 'vite';
import { resolve } from 'node:path';

export default defineConfig({
  base: './',
  build: {
    outDir: 'assets/dist',
    emptyOutDir: true,
    sourcemap: false,
    cssCodeSplit: false,
    rollupOptions: {
      input: resolve(import.meta.dirname, 'assets/src/js/app.js'),
      output: {
        entryFileNames: 'app.js',
        assetFileNames: (assetInfo) => assetInfo.names?.some((name) => name.endsWith('.css')) ? 'app.css' : 'assets/[name][extname]',
        chunkFileNames: 'chunks/[name]-[hash].js'
      }
    }
  }
});

