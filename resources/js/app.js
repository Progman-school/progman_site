import './bootstrap'

import router from './router.js'
import { createApp } from 'vue'
import app from './app.vue'
import { createPinia } from 'pinia'
/* import font awesome */
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faGlobe } from '@fortawesome/free-solid-svg-icons'
// import {  } from '@fortawesome/free-regular-svg-icons'
import { faTelegram } from '@fortawesome/free-brands-svg-icons'

library.add(faGlobe)
library.add(faTelegram)

const pinia = createPinia()
const vueApp = createApp(app)
vueApp.component('font-awesome-icon', FontAwesomeIcon)
vueApp.use(pinia).use(router).mount("#wrapper")

