<script setup>
import Closer from "../widgets/closer.vue"
import InsertContent from '../widgets/insert_content.vue'
import SubmitForm from "../widgets/submit_form.vue"
import CourseSelectorFormField from "../widgets/submit_form/course_selector_form_field.vue"
import RegistrationFormFields from "../widgets/submit_form/registration_form_fields.vue"
import {ref} from "vue"
import {useMultiLanguageStore} from "../../storages/multi_language_content"
import WeekDaysFormField from "../widgets/submit_form/weekday_checkbox_set_form_item.vue"
import {useCourseStorage} from "../../storages/course_storage";
const courseStorage = useCourseStorage()


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

/**
 * Returns the sum of a and b
 * @param {FormData} formFields
 */
function processTest(formFields) {
    let hours = 0
    for (let technology of courseStorage.courses[formFields.get('course_id')].technologies) {
        hours += technology.pivot.hours
    }
    let weekDaysCount = 0
    for (let fieldEntry of formFields.entries()) {
        if (fieldEntry[0].includes('weekday__')) {
            weekDaysCount++
        }
    }

    const score = countDays(
        hours,
        formFields.get('day_hours'),
        weekDaysCount,
        formFields.get('current_level'),
        formFields.get('age'),
        true
    )

    const yourselfScore = countDays(
        hours,
        formFields.get('day_hours'),
        weekDaysCount,
        formFields.get('current_level'),
        formFields.get('age'),
        false
    )

    formFields.append('score', score)
    formFields.append('yourself_score', yourselfScore)
    formFields.append('result', yearsMonthsWeeksDays(score))
    formFields.append('yourself_result', yearsMonthsWeeksDays(yourselfScore))
    formFields.append('c', formFields.get('day_hours')[0] + '01020110')
    formFields.append('topic','study_plan_counter')
    formFields.append('result_message', "<h2>Result!</h2><p>Yes! YOu got it!</p>")
    return formFields
}

function yearsMonthsWeeksDays(daysNumber) {
    const years = Math.floor(daysNumber / 365);
    const months = Math.floor((daysNumber % 365) / 30);
    const weeks = Math.floor(((daysNumber % 365) % 30) / 7);
    const days = ((daysNumber % 365) % 30) % 7;

    let result = ''
    if (years > 0) {
        result += years + (years > 1 ? 'years' : 'year') + ', '
    }
    if (months > 0) {
        result += months + (months > 1 ? 'months' : 'month') + ', '
    }
    if (weeks > 0) {
        result += weeks + (weeks > 1 ? 'weeks' : 'week') + ', '
    }
    return result + days + (days > 1 ? 'days' : 'day')
}

function countDays(takesHours, hoursPerDay, daysPerWeek, level, age, withHelp = false) {
    if (hoursPerDay > 2) {
        hoursPerDay -= (hoursPerDay * 0.32)
    } else if (hoursPerDay > 5) {
        hoursPerDay -= (hoursPerDay * 0.41)
    }
    else if (hoursPerDay > 6) {
        hoursPerDay -= (hoursPerDay * 0.5)
    }

    let days = Math.ceil(takesHours / Math.floor(hoursPerDay * daysPerWeek)) * 7

    days += 1

    switch (level) {
        case 'zero':
            days *= 1.4
            break
        case 'junior':
            days *= 1.2
            break
        case 'middle':
            days *= 1
            break
    }

    days = Math.ceil(days)

    if (age < 14) {
        days *= 1.6
    } else if (age < 18) {
        days *= 1.2
    }
    else if (age > 60) {
        days *= 1.8
    }
    else if (age > 45) {
        days *= 1.4
    }
    else if (age > 35) {
        days *= 1.2
    }

    days = Math.ceil(days)
    if (withHelp) {
        return days - 3
    }

    switch (level) {
        case 'zero':
            days = (days + 7) * 2.8
            break
        case 'junior':
            days = (days + 3) * 2.2
            break
        case 'middle':
            days = days * 1.45
            break
    }
    return Math.ceil(days)
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
                :action="'process_test'"
                :is_disabled="isDisabledForm"
                submit_button_name="Get my plan"
                :isVisibleSubmitButton="isShowedTest"
                :preserveFunction="processTest"
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
                    <input type="number" id="day_hours" name="day_hours" min="1" max="14" placeholder="number" required>
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
                    <input type="number" id="age" name="age" min="7" max="100" placeholder="number" required>
                </div>
                <div class="field" v-if="isShowedTest">
                    <label for="details">Describe your the project that you want to make:</label>
                    <textarea name="details" id="details" minlength="10" maxlength="1500" placeholder="The name of the project?, what functions is it going to have etc?.." rows="3"></textarea>
                </div>
                <RegistrationFormFields v-if="isShowedTest" :available-types="['email']" @onPrivacyPolicyConfirmed="changeFormDisability">
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
