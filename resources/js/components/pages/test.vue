<script>
import closer from "../helpers/closer.vue";
import insertContent from '../helpers/insert-content.vue'

export default {
    name: "test",
    components: {
        closer,
        insertContent,
    },
}
</script>

<script setup>
import mixins from "../../mixins.js";
import { useEventListener } from "../../storages/event_storage.js"
const eventListener = useEventListener()
import { useMultiLanguageStore } from '../../storages/multi_language_content.js'
const multiLanguageStore = useMultiLanguageStore()

const showBotLogin = (loginData) => {
    multiLanguageStore.contentArray['alert_text'] = loginData.alert_text

    eventListener.call('popup_alert:show', {
        title: '{{test_passed_alert_title}}',
        text: '{{alert_text}}',
        href: 'tg://resolve?domain=progman_signup_bot&start=' + loginData.hash,
        url: null,
        button: '{{test_passed_alert_tg_button}}',
    });
    document.getElementById('test_form').reset()
}

const saveTestData = (form) => {
    form.preventDefault()
    const formData = new FormData(form.target)
    mixins.methods.postAPI(
        'savetest',
        formData,
        showBotLogin
    )
}
</script>

<template>
    <article id="test">
        <h2 class="major">Your opportunity test!</h2>
        <p>
            <insert-content>test_preview</insert-content>
            <br/>
        </p>
        <section>
            <h3 class="major">Answer the question:</h3>
            <form id="test_form" @submit="saveTestData">
                <insert-content set_class="fields">test_for_registration</insert-content>
                <ul class="actions">
                    <li><button type="submit" class="primary">Finish the test</button></li>
                </ul>
            </form>
        </section>
        <closer />
    </article>
</template>

<style scoped>

</style>
