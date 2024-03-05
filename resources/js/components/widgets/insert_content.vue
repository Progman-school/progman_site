<script setup>
import { useSlots, computed } from 'vue'
import { useMultiLanguageStore } from '../../storages/multi_language_content.js'
import InlinePreloader from './inline_preloader.vue'

defineProps({
    set_class: {
        type: String,
        default: ''
    }
})

const slots = useSlots()
const multiLanguageStore = useMultiLanguageStore();
const tag = slots.default()[0].children

const created = () => {
    multiLanguageStore.getContentByTag(tag)
}

const langContent = computed({
    get () {
        return multiLanguageStore.contentArray[tag] === undefined ? '' :
            multiLanguageStore.contentArray[tag][multiLanguageStore.currentLanguage]
    },
})
created()
</script>

<template>
    <InlinePreloader :is-visible="!langContent" transition-name="null" />
    <transition name="slide-fade" mode="out-in">
        <content :class="set_class" v-html="langContent" v-show="langContent"></content>
    </transition>
</template>

<style scoped>
content {
    display: inline-block;
}
</style>
