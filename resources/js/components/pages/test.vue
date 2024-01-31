<script setup>
import Closer from "../helpers/closer.vue";
import InsertContent from '../helpers/insert-content.vue'
import mixins from "../../mixins.js";
import { useEventListener } from "../../storages/event_storage.js"
import {ref} from "vue";
import {usePreloadedDataStorage} from "../../storages/preloaded_content_storage";
import {useMultiLanguageStore} from "../../storages/multi_language_content";
const multiLanguageStore = useMultiLanguageStore()
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

const chosenCourse = ref(null)
const chooseCourse = (event) => {
    chosenCourse.value = preloadedData.courses[event.target.value]
    chosenCourse.value.description = chosenCourse.value['description_' + multiLanguageStore.currentLanguage]
}

</script>

<template>
    <article id="test">
        <h2 class="major">Your opportunity test!</h2>
        <p>
            <InsertContent>test_preview</InsertContent>
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
                        <select id="course" name="course_id" @change="chooseCourse">
                            <option value="" selected disabled>Choose the course</option>
                            <option v-for="course in preloadedData.courses" :value=course.id :title=course.level>
                                {{course.name}}
                            </option>
                        </select>
                        <div class="field_details" v-if="chosenCourse">
                            <h4>Details:</h4>
                            <div>
                                <b>Level:&nbsp;&nbsp;{{chosenCourse.level}}</b>
                                <b>Type:&nbsp;&nbsp;{{chosenCourse.type}}</b>
                            </div>
                            <p>
                                {{chosenCourse.description || 'No description :('}}
                            </p>
                            <h6>Technologies:</h6>
                            <ul>
                                <li v-for="technology in chosenCourse.technologies" :title=technology.description>
                                    <b>{{technology.name}}</b> (~{{technology.pivot.hours}}h)
                                </li>
                            </ul>
                        </div>
                    </div>
                </content>
                <InsertContent set_class="fields">test_for_registration</InsertContent>
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
                        <div v-if="showEmailField" style="border: none">
                            <label for="name">Your name:</label>
                            <input type="text" id="name" name="name" placeholder="Elon Mask" maxlength="60" required>
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
        <Closer />
    </article>
</template>

<style scoped>

.field_details {
    padding: 0 !important;
    border: none !important;
}

.field_details > h4 {
    margin-top: 20px;
}

.field_details > div {
    padding: 0;
    border: none;
    display: flex;
    justify-content: space-around;
}
.field_details > p {
    margin: 20px 0;
    font-style: italic;
}

.field_details ul {
    border: none;
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-gap: 3px 20px;
    margin: 0;
}

.field_details ul li {
    border: none;
    padding-left: 2px;
}

</style>
