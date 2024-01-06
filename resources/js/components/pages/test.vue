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
import {ref} from "vue";
import {usePreloadedDataStorage} from "../../storages/preloaded_content_storage";
const eventListener = useEventListener()
const preloadedData = usePreloadedDataStorage()
preloadedData.getCoursesList();
const showLoginAlert = (preLoginResult) => {
    eventListener.call('popup_alert:show', {
        title: '{{test_passed_alert_title}}',
        text: preLoginResult.data.alert_text,
        href: preLoginResult.data.hash_link,
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
        showLoginAlert
    )
}

const showEmailField = ref(true)
const changeRegistrationType = (event) => {
    showEmailField.value = event.target.value === "email"
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
                <content class="fields">
                    <div class="field">
                        <label for="course">
                            Choose the course that you are interesting in:
                        </label>
                        <select id="course" name="course_id">
                            <option v-for="course in preloadedData.courses" :value=course.id :title=course.level>
                                {{course.name}}
                            </option>
                        </select>
                    </div>
                </content>
                <insert-content set_class="fields">test_for_registration</insert-content>
                <content class="fields">
                    <div class="field">
                        <label for="uid_type">
                            Select where would you like to receive your test results and advices:
                        </label>
                        <select id="uid_type" name="uid_type" @change="changeRegistrationType">
                            <option value="email" selected>E-Mail address</option>
                            <option value="telegram">Telegram messenger</option>
                        </select>
                        <br/>
                        <div v-if="showEmailField" style="border: none">
                            <label for="email">Your E-mail:</label>
                            <input type="email" id="email" name="email" placeholder="your-real@email.com" required>
                        </div>
                        <div v-if="!showEmailField" style="border: none">
                            <b>Important! Make sure that you have installed Telegram messenger on this device, or choose Email</b>
                        </div>
                    </div>
                </content>
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
