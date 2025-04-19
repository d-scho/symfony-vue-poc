import { fileURLToPath, URL } from "node:url";

import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import vueDevTools from "vite-plugin-vue-devtools";

// https://vite.dev/config/
export default defineConfig({
  plugins: [vue(), vueDevTools()],
  resolve: {
    alias: {
      "@": fileURLToPath(new URL("./src", import.meta.url)),
    },
  },
  server: {
    host: "0.0.0.0",
    port: 3000,
    strictPort: true,
    allowedHosts: ["symfony-vue-poc.ddev.site"],
  },
  build: {
    rollupOptions: {
      input: fileURLToPath(new URL("./src/app.ts", import.meta.url)),
      output: {
        entryFileNames: `public/[name]-[hash].js`,
        chunkFileNames: `public/[name]-[hash].js`,
        assetFileNames: `public/[name]-[hash].[ext]`,
      },
    },
    outDir: "./build",
    emptyOutDir: true,
    manifest: 'internal-manifest.json',
  },
});
