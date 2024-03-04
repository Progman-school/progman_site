<script setup>
import Closer from "../widgets/closer.vue";
import InsertContent from '../widgets/insert-content.vue'
import SubmitForm from "../widgets/submit_form.vue"
import CourseInfoSelectionForForm from "../widgets/submit_form/course_info_selection_for_form.vue"
import RegistrationFormFields from "../widgets/submit_form/registration_form_fields.vue"


import mixins from "../../mixins.js";
import { useEventListener } from "../../storages/event_storage.js"
import {ref} from "vue";
import {useMultiLanguageStore} from "../../storages/multi_language_content";
const multiLanguageStore = useMultiLanguageStore()

const eventListener = useEventListener()

const saveTestData = (form) => {
    form.preventDefault()
    const formData = new FormData(form.target)
    mixins.methods.postAPI(
        'add_request',
        formData,
        () => {
            eventListener.call('popup_alert:show', {
                title: '{{test_passed_alert_title}}',
                text: preLoginResult.data.alert_text,
                href: preLoginResult.data.hash_link,
                url: null,
                button: '{{test_passed_alert_tg_button}}',
            });
            document.getElementById('test_form').reset()
        }
    )
}

const isShowedTestForm = ref(false)
const showTestForm = (event) => {
    event.preventDefault()
    isShowedTestForm.value = true
}

const chosenCourse = ref(null)


</script>

<template>
    <article id="test">
        <h2 class="major">Get your chance!</h2>
        <p>
            <InsertContent>test_preview</InsertContent>
            <br/>
        </p>
        <section>
            <SubmitForm :method="saveTestData" submit_button_text="Finish the test" is_disabled="!isPrivacyPolicyConfirmed">
                <CourseInfoSelectionForForm chosenCourse="chosenCourse" />
                <div class="order_course_button" v-if="chosenCourse && !isShowedTestForm">
                    <button type="submit" class="primary" @click="showTestForm">
                        <InsertContent>first_free_class_order_button</InsertContent>
                    </button>
                </div>
                <h3 class="major" v-if="isShowedTestForm">
                    <InsertContent>test_form_head_title</InsertContent>
                </h3>
                <InsertContent  v-if="isShowedTestForm" set_class="fields">test_for_registration</InsertContent>
                <RegistrationFormFields v-if="isShowedTestForm" v-model:isPrivacyPolicyConfirmed="isPrivacyPolicyConfirmed">
                    <InsertContent>test_privacy_policy_link</InsertContent>
                </RegistrationFormFields>
            </SubmitForm>
        </section>
        <Closer />
    </article>
</template>

<style scoped>
.order_course_button {
    text-align: center;
    margin-bottom: 20px;
}
</style>
