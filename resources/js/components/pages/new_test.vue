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
    <article>
        <h2 class="major">Get your chance!</h2>
        <p>
            <InsertContent>test_preview</InsertContent>
            <br/>
        </p>
        <section>
            <SubmitForm
                :action="'add_study_plan_request'"
                :is_disabled="isDisabledForm"
                submit_button_name="Get my plan"
                :isVisibleSubmitButton="isShowedTest"
            >
                <CourseSelectorFormField urlParamName="course" @onSelect="showOrderCourseButton" />
                <div v-if="isVisibleOrderCourseButton && !isShowedTest" class="field show_test_button">
                    <button type="submit" class="primary" @click="showTestForm">
                        <InsertContent>first_free_class_order_button</InsertContent>
                    </button>
                </div>
                <h3 class="major test_title" v-if="isShowedTest">
                    Calculate your personal study plan
                </h3>
                <WeekDaysFormField v-if="isShowedTest">Week days</WeekDaysFormField>
                <div class="field" v-if="isShowedTest">
                    <label for="day_hours">How many hours per day a you ready to learn:</label>
                    <input type="number" id="day_hours" name="day_hours" min="1" max="24" placeholder="number" required>
                </div>
                <div class="field" v-if="isShowedTest">
                    <label>Your current level you think:</label>
                    <input type="radio" id="current_level_zero" name="current_level" value="zero" required>
                    <label for="current_level_zero">Zero</label>
                    <input type="radio" id="current_level_junior" name="current_level" value="junior">
                    <label for="current_level_junior">Junior</label>
                    <input type="radio" id="current_level_middle" name="current_level" value="middle">
                    <label for="current_level_middle">Middle</label>
                </div>
                <div class="field" v-if="isShowedTest">
                    <label for="age">Your age (full years count):</label>
                    <input type="number" id="age" name="age" min="7" max="70" placeholder="number" required>
                </div>
                <div class="field" v-if="isShowedTest">
                    <label for="details">Describe your the project that you want to make:</label>
                    <textarea name="details" id="details" minlength="10" maxlength="1500" placeholder="The name of the project?, what functions is it going to have etc?.." rows="3"></textarea>
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
