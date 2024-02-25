<script setup>
import Closer from "../widgets/closer.vue";
import InsertContent from '../widgets/insert-content.vue'
import mixins from "../../mixins.js";
import { useEventListener } from "../../storages/event_storage.js"
import {ref} from "vue";
import {useMultiLanguageStore} from "../../storages/multi_language_content";
import {usePreloadedDataStorage} from "../../storages/preloaded_content_storage";
import router from "../../router";
const multiLanguageStore = useMultiLanguageStore()
const eventListener = useEventListener()
const preloadedData = usePreloadedDataStorage()
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

const showEmailField = ref('email')
const changeRegistrationType = (event) => {
    showEmailField.value = event.target.value
}

const chosenCourse = ref(null)
const urlCourseId = ref(null)
const isShowedTestForm = ref(false)
const isPrivacyPolicyConfirmed = ref(false)

const showTestForm = (event) => {
    event.preventDefault()
    isShowedTestForm.value = true
}

const chooseCourse = (event) => {
    setCourse(event.target.value)
}

const confirmPrivacyPolicy = (event) => {
    isPrivacyPolicyConfirmed.value = event.target.checked
}

function setCourse(courseId) {
    if (courseId) {
        chosenCourse.value = preloadedData.courses[courseId]
        router.push(`test?course=${courseId}`)
        chosenCourse.value.hours = 0
        for (let technology of chosenCourse.value.technologies) {
            chosenCourse.value.hours += technology.pivot.hours
        }
    } else {
        chosenCourse.value = null
        router.push(`test`)
    }
}
preloadedData.getCoursesList().then(() => {
    urlCourseId.value = router.currentRoute.value.query.course ?? ""
    if (router.currentRoute.value.query.course) {
        setCourse(router.currentRoute.value.query.course)
    }
})

</script>

<template>
    <article id="test">
        <h2 class="major">Get your chance!</h2>
        <p>
            <InsertContent>test_preview</InsertContent>
            <br/>
        </p>
        <section>
            <form id="test_form" @submit="saveTestData">
                <content class="fields">
                    <div class="field">
                        <label for="course">
                            Your course:
                        </label>
                        <select id="course" name="course_id" :class="urlCourseId ? 'chosen_course' : ''" v-model="urlCourseId" @change="chooseCourse">
                            <option v-if="!urlCourseId" value="" selected disabled>Select course</option>
                            <option v-for="course in preloadedData.courses" :value=course.id :title=course.level :selected="urlCourseId === course.id">
                                {{course.name}}
                            </option>
                        </select>
                        <div class="field_details" v-if="chosenCourse">
                            <h4>Details:</h4>
                            <div>
                                <b>Start from:&nbsp;&nbsp;{{chosenCourse.level}}</b>
                                <b class="longer_param">Type:&nbsp;&nbsp;{{chosenCourse.type}}</b>
                                <b>Hours:&nbsp;&nbsp;~{{chosenCourse.hours}}</b>
                            </div>
                            <p>
                                {{chosenCourse['description_' + multiLanguageStore.currentLanguage] || 'No description :('}}
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
                <div class="order_course_button" v-if="chosenCourse && !isShowedTestForm">
                    <button type="submit" class="primary" @click="showTestForm">
                        <InsertContent>first_free_class_order_button</InsertContent>
                    </button>
                </div>
                <h3 class="major" v-if="isShowedTestForm">
                    <InsertContent>test_form_head_title</InsertContent>
                </h3>
                <InsertContent  v-if="isShowedTestForm" set_class="fields">test_for_registration</InsertContent>
                <content class="fields" v-if="isShowedTestForm">
                    <div class="field">
                        <label for="uid_type">
                            Type of registration:
                        </label>
                        <select id="uid_type" name="uid_type" @change="changeRegistrationType">
                            <option value="email" selected>E-Mail address</option>
                            <option value="telegram">Telegram messenger</option>
                        </select>
                        <br/>
                        <div v-if="showEmailField === 'email'" style="border: none">
                            <label for="email_contact">Your E-mail:</label>
                            <input type="email" id="email_contact" name="contact" placeholder="your-real@email.com" required>
                        </div>
                        <div v-if="showEmailField === 'telegram'" style="border: none">
                            <b><InsertContent>telegram_registration_choosing_warning</InsertContent></b>
                        </div>
                        <div v-if="showEmailField === 'telegram'" style="border: none">
                            <label for="telegram_contact">Your Telegram username or phone number:</label>
                            <input type="text" id="telegram_contact" name="contact" placeholder="@my_telegram / +07778889911" maxlength="60" required>
                        </div>
                        <div style="border: none">
                            <label for="name">Your name:</label>
                            <input type="text" id="name" name="name" placeholder="Elon Mask" maxlength="60" required>
                        </div>
                        <div class="field">
                            <input type="checkbox" id="privacy_policy" value="1" @change="confirmPrivacyPolicy">
                            <label for="privacy_policy">
                                <InsertContent>test_privacy_policy_link</InsertContent>
                            </label>
                        </div>
                    </div>
                </content>
                <ul class="actions" v-if="isShowedTestForm">
                    <li><button type="submit" class="primary" :disabled="!isPrivacyPolicyConfirmed">Finish the test</button></li>
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
    padding: 2px 10px;
    border: none;
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    background: #9ef68633;
}
.field_details > div > b {
    padding: 0 5px;
}

@media (max-width: 760px) {
    .field_details > div > b[class="longer_param"] {
        order: 3;
    }
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
.order_course_button {
    text-align: center;
    margin-bottom: 20px;
}
.chosen_course {
    color: #58cc02ff;
    font-weight: bold;
}

</style>
