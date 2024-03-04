<script setup>
import Closer from "../widgets/closer.vue";
import InsertContent from '../widgets/insert-content.vue'
import SubmitForm from "../widgets/submit_form.vue"
import CourseSelectorFormItem from "../widgets/submit_form/course_selector_form_item.vue"
import RegistrationFormFields from "../widgets/submit_form/registration_form_fields.vue"
import {ref} from "vue";
import {useMultiLanguageStore} from "../../storages/multi_language_content";

const multiLanguageStore = useMultiLanguageStore()

const isShowedTestForm = ref(false)
const showTestForm = (event) => {
    event.preventDefault()
    isShowedTestForm.value = true
}

const isVisibleOrderCourseButton = ref(false)
const showOrderCourseButton = (data) => {
    isVisibleOrderCourseButton.value = data
}

const isDisabledForm = ref(true)
const changeFormDisability = (data) => {
    isDisabledForm.value = data
}

</script>

<template>
    <article id="test">
        <h2 class="major">Get your chance!</h2>
        <p>
            <InsertContent>test_preview</InsertContent>
            <br/>
        </p>
        <section>
            <SubmitForm :action="'add_request'" :submit_button_text="'Finish the test'" :is_disabled="isDisabledForm">
                <CourseSelectorFormItem urlParamName="course" @onSelect="showOrderCourseButton" />
                <div class="order_course_button" v-if="isVisibleOrderCourseButton && !isShowedTestForm">
                    <button type="submit" class="primary" @click="showTestForm">
                        <InsertContent>first_free_class_order_button</InsertContent>
                    </button>
                </div>
                <h3 class="major" v-if="isShowedTestForm">
                    <InsertContent>test_form_head_title</InsertContent>
                </h3>
                <InsertContent  v-if="isShowedTestForm" set_class="fields">test_for_registration</InsertContent>
                <RegistrationFormFields v-if="isShowedTestForm" @onPrivacyPolicyConfirmed="changeFormDisability">
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
