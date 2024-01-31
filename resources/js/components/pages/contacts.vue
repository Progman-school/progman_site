<script setup>
import {ref} from 'vue'

import Closer from '../helpers/closer.vue'
import InsertContent from '../helpers/insert-content.vue'
import {useMultiLanguageStore} from "../../storages/multi_language_content"
const multiLanguageStore = useMultiLanguageStore();
const telegramLink = ref("")
multiLanguageStore.getContentByTag("telegram_manager_account").then(insertData => {
    telegramLink.value = `tg://resolve?domain=${insertData[multiLanguageStore.currentLanguage]}`
})
</script>

<template>
    <article id="contact">
        <h2 class="major">Contacts</h2>
        <ul class="my_social">
            <InsertContent>links_contacts</InsertContent>
        </ul>
        <hr />
        <div style="margin: 12px 0; text-align: center">
            <router-link :to="'start'" >
                <strong>
                    <InsertContent>go_to_start_link</InsertContent>
                </strong>
            </router-link>
        </div>
        <hr />
        <div style="text-align: center">
            <p>
                <InsertContent>footer_contacts</InsertContent>
            </p>
            <a :href="telegramLink">
                <button class="primary">
                    <font-awesome-icon icon="fab fa-telegram"></font-awesome-icon>
                    Connect by Telegram
                </button>
            </a>
        </div>
        <Closer />
    </article>
</template>

<style scoped>

</style>
