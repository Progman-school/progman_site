import './bootstrap'

import router from './router.js'
import { createApp } from 'vue'
import app from './app.vue'
import { createPinia } from 'pinia'

const pinia = createPinia()
const vueApp = createApp(app)
vueApp.use(pinia).use(router).mount("#wrapper")

