<script setup>
import Closer from "../widgets/closer.vue"
import InsertContent from '../widgets/insert-content.vue'
import SubmitForm from "../widgets/submit_form.vue"
import CourseSelectorFormField from "../widgets/submit_form/course_selector_form_field.vue"
import RegistrationFormFields from "../widgets/submit_form/registration_form_fields.vue"
import {ref} from "vue"
import {useMultiLanguageStore} from "../../storages/multi_language_content"
import WeekDaysFormField from "../widgets/submit_form/weekday_checkbox_set_form_item.vue"

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
        <h2 class="major">Get consultation</h2>
        <p>
            How does it happen? You can ask me a question, and I will answer it.
            I will not give you a ready-made solution, but I will help you to find it.
            I will not do your work for you, but I will help you to do it yourself.
            I will not give you a fish, but I will teach you how to fish!
        </p>
        <section>
            <SubmitForm
                :action="'add_request'"
                :submit_button_text="'Finish the test'"
                :is_disabled="isDisabledForm"
                submit_button_name="Finish the test"
                :isVisibleSubmitButton="true"
            >
                <div class="field">
                    <label for="city">Where are you from?</label>
                    <input type="text" name="city" id="city" minlength="3" placeholder="Your current city (country into the brackets)" required/>
                </div>
                <WeekDaysFormField>What days is better for you:</WeekDaysFormField>
                <div class="field" v-if="isShowedTest">
                    <label>How many hours do you need:</label>
                    <input type="radio" id="hours_1" name="hours" value=1 required>
                    <label for="hours_1">One</label>
                    <input type="radio" id="hours_2" name="hours" value=2>
                    <label for="hours_2">Two</label>
                </div>
                <div class="field">
                    <label for="age">Your age (full years count):</label>
                    <input type="number" id="age" name="age" min="6" max="70" placeholder="number" required>
                </div>
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
    color: #58cc02ff;
    margin-top: 20px;
}
</style>

