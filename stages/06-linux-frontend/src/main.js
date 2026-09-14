import { createApp } from 'vue'
import App from './App.vue'
import './style.css'

// .vue 模板由 Vite 编译；不要把 template 字符串交给仅含运行时的 Vue。
createApp(App).mount('#app')
