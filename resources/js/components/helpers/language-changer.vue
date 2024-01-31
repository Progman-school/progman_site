<script setup>
import { useMultiLanguageStore } from '../../storages/multi_language_content.js'
import {computed} from "vue";
import {useEventListener} from "../../storages/event_storage"

const eventListener = useEventListener()
const multiLanguageStore = useMultiLanguageStore()

multiLanguageStore.getCurrentLanguage()

const iClass = computed({
    get () {
        if (!multiLanguageStore.currentLanguage) {
            eventListener.call('cookie_alert:show', true)
            return 'rotate_circle'
        }
        return ''
    },
})
</script>

<template>
    <div id="langSwitcher" @click="multiLanguageStore.switchLanguage">
        <span>{{multiLanguageStore.currentLanguage || '-- --'}}</span>
        <font-awesome-icon icon="fa-solid fa-globe" :class="iClass"></font-awesome-icon>
    </div>
</template>

<style scoped>

.rotate_circle{
    animation: spin 3s linear infinite;
}

@keyframes spin{
    0%{ transform: rotate(-360deg) scale(1); }
    25%{ transform: rotate(360deg) scale(1.2); }
    50%{ transform: rotate(-360deg) scale(1); }
    75%{ transform: rotate(360deg) scale(1.2); }
    /*100%{ transform: rotate(-360deg)scale(1.2); }*/
}
</style>
