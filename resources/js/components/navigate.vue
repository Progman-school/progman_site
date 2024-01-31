<script setup>
import { useMultiLanguageStore } from '../storages/multi_language_content.js'
import router from "../router";
const multiLanguageStore = useMultiLanguageStore()
multiLanguageStore.getCurrentLanguage()

const links = router.getRoutes()

</script>

<template>
    <nav>
        <ul>
            <template v-for="link in links">
                <li v-if="link.name">
                    <router-link :to="link.path.replace(':lang?', multiLanguageStore.currentLanguage)">{{link.name}}</router-link>
                </li>
            </template>
        </ul>
    </nav>
</template>

<script>

export default {
    name: "navigate",
}
</script>
