import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  server: {
    port: 5173,
    strictPort: true,
    // 浏览器只访问 Vue 的同源 /api；代理负责连接 PHP，不需要开放任意跨域。
    proxy: { '/api': { target: process.env.API_PROXY_TARGET || 'http://127.0.0.1:18081' } },
  },
})
