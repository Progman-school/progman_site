<script setup>
import Closer from "../widgets/closer.vue";
import InsertContent from '../widgets/insert-content.vue'
import SubmitForm from "../widgets/submit_form.vue"
import CourseSelectorFormField from "../widgets/submit_form/course_selector_form_field.vue"
import RegistrationFormFields from "../widgets/submit_form/registration_form_fields.vue"
import {ref} from "vue";
import {useMultiLanguageStore} from "../../storages/multi_language_content";

const multiLanguageStore = useMultiLanguageStore()

const isShowedTest = ref(false)
const showTestForm = (event) => {
    event.preventDefault()
    isShowedTest.value = true
}

const isVisibleOrderCourseButton = ref(false)
const showOrderCourseButton = (data) => {
    isVisibleOrderCourseButton.value = data
}

const isDisabledForm = ref(true)
const changeFormDisability = (data) => {
    isDisabledForm.value = !data
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
            <SubmitForm
                :action="'add_request'"
                :submit_button_text="'Finish the test'"
                :is_disabled="isDisabledForm"
                submit_button_name="Finish the test"
                :isVisibleSubmitButton="isShowedTest"
            >
                <CourseSelectorFormField urlParamName="course" @onSelect="showOrderCourseButton" />
                <div v-if="isVisibleOrderCourseButton && !isShowedTest" class="field show_test_button">
                    <button type="submit" class="primary" @click="showTestForm">
                        <InsertContent>first_free_class_order_button</InsertContent>
                    </button>
                </div>
                <h3 class="major test_title" v-if="isShowedTest">
                    <InsertContent>test_form_head_title</InsertContent>
                </h3>
                <InsertContent  v-if="isShowedTest" set_class="fields">test_for_registration</InsertContent>
                <RegistrationFormFields v-if="isShowedTest" @onPrivacyPolicyConfirmed="changeFormDisability">
                    <InsertContent>test_privacy_policy_link</InsertContent>
                </RegistrationFormFields>
            </SubmitForm>
        </section>
        <Closer />
    </article>
</template>

<style scoped>
.show_test_button {
    text-align: center;
}
.test_title{
    margin-top: 20px;
}
</style>
