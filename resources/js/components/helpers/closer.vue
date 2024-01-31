<script setup>
import router from '../../router.js'
import {useMultiLanguageStore} from "../../storages/multi_language_content"
const multiLanguageStore = useMultiLanguageStore()
const pageClose = () => {
    router.push(`/${multiLanguageStore.currentLanguage || ''}`)
}
</script>

<template>
    <div class="close" @click="pageClose">Close</div>
</template>

<style scoped>

</style>
