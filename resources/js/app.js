import './bootstrap'

import router from './router.js'
import { createApp } from 'vue'
import app from './app.vue'
import { createPinia } from 'pinia'
import { useMultiLanguageStore } from './storages/multi_language_content.js'

/* import font awesome */
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faGlobe, faCircleQuestion, faMagicWandSparkles, faFileShield, faHandHoldingDollar, faSackDollar, faThumbsUp, faPeopleRoof} from '@fortawesome/free-solid-svg-icons'
import { faTelegram } from '@fortawesome/free-brands-svg-icons'
library.add(faGlobe, faTelegram, faCircleQuestion, faMagicWandSparkles, faFileShield, faHandHoldingDollar, faSackDollar, faThumbsUp, faPeopleRoof)

const pinia = createPinia()
const vueApp = createApp(app)
vueApp.component('font-awesome-icon', FontAwesomeIcon)
vueApp.use(pinia)
vueApp.use(router)

router.isReady().then(() => {
    const multiLanguageStore = useMultiLanguageStore()
    let language = router.currentRoute.value.params.lang
    if (language) {
        multiLanguageStore.changeLanguageTo(language)
    } else {
        multiLanguageStore.getCurrentLanguageFromApi()
    }
    vueApp.mount("#wrapper")
})
