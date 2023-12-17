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

const showBotLogin = (preLoginResult) => {
    eventListener.call('popup_alert:show', {
        title: '{{test_passed_alert_title}}',
        text: preLoginResult.data.alert_text,
        href: `tg://resolve?domain=${preLoginResult.data.telegram_bot_login}&confirm=${preLoginResult.data.hash}`,
        url: null,
        button: '{{test_passed_alert_tg_button}}',
    });
    document.getElementById('test_form').reset()
}

const saveTestData = (form) => {
    form.preventDefault()
    const formData = new FormData(form.target)
    mixins.methods.postAPI(
        'add_request',
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
                <label for="course">
                    Choose the course that you are interesting in:
                </label>
                <select id="course" name="course_id">
                    <option value="1" selected>WEB full-stack</option>
                    <option value="2" selected>Different..</option>
                </select>
                <insert-content set_class="fields">test_for_registration</insert-content>
                <label for="uid_type">
                    Select where would you like to receive your test results and advices:
                </label>
                <select id="uid_type" name="uid_type">
                    <option value="telegram" selected>Telegram</option>
                    <option value="email" selected>E-Mail</option>
                </select>
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
