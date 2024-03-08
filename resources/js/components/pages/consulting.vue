<script setup>
import Closer from "../widgets/closer.vue"
import InsertContent from '../widgets/insert_content.vue'
import SubmitForm from "../widgets/submit_form.vue"
import RegistrationFormFields from "../widgets/submit_form/registration_form_fields.vue"
import {ref, computed} from "vue"
import {useMultiLanguageStore} from "../../storages/multi_language_content"
import WeekDaysFormField from "../widgets/submit_form/weekday_checkbox_set_form_item.vue"
import CouponDiscounter from "../widgets/coupon_discounter.vue"

const COUPON_TYPE = 'consulting'

const multiLanguageStore = useMultiLanguageStore()

const amountHours = ref(1)
const hourPrice = ref()

const isDisabledForm = ref(true)
const changeFormDisability = (data) => {
    isDisabledForm.value = !data
}

const valueRestriction = (event) => {
    if (Number(event.target.value) <= Number(event.target.min)) {
        event.target.value = Number(event.target.min)
    }
    if (Number(event.target.value) >= Number(event.target.max)) {
        event.target.value = Number(event.target.max)
    }
}

multiLanguageStore.getContentByTag("full_private_course_price").then(insertData => {
    hourPrice.value = insertData[multiLanguageStore.currentLanguage]
})
</script>

<template>
    <article>
        <h2 class="major">Get consultation</h2>
        <p>
            How does it happen? You can ask me a question, and I will answer it.
            I will not give you a ready-made solution, but I will help you to find it.
            I will not do your work for you, but I will help you to do it yourself.
            I will not give you a fish, but I will teach you how to fish!
        </p>
        <section>
            <SubmitForm
                :action="'add_consulting_request'"
                :is_disabled="isDisabledForm"
                submit_button_name="Send request"
                :isVisibleSubmitButton="true"
            >
                <div class="field">
                    <label>How many hours do you need:</label>
                    <div class="price_builder">
                        <div>
                            <div>
                                <input type="radio" id="hours_1" name="hours" value=1 v-model="amountHours" required>
                                <label for="hours_1">One</label>
                            </div>
                            <div>
                                <input type="radio" id="hours_2" name="hours" v-model="amountHours" value=2>
                                <label for="hours_2">Two</label>
                            </div>
                            <div v-if="amountHours < 3">
                                <input type="radio" id="hours_more" name="hours" value="3" v-model="amountHours">
                                <label for="hours_more">More</label>
                            </div>
                            <div v-if="amountHours >= 3">
                                <label for="hours_number">Your number</label>
                                <input type="number" id="hours_number" name="hours" value=3 min=2 max=50 v-model="amountHours" @input="valueRestriction">
                            </div>
                        </div>
                        <CouponDiscounter :couponType="COUPON_TYPE" :unit-amount="amountHours" :unit-price="hourPrice" />
                    </div>
                </div>
                <WeekDaysFormField>What days is better for you:</WeekDaysFormField>
                <div class="field">
                    <label for="city">Where are you from?</label>
                    <input type="text" name="city" id="city" minlength="3" placeholder="Your current city (country into the brackets)" required/>
                </div>
                <div class="field">
                    <label for="details">Cover message:</label>
                    <textarea name="details" id="details" minlength="10" maxlength="500" placeholder="Any additional wishes or questions (optional)" rows="3"></textarea>
                </div>
                <RegistrationFormFields @onPrivacyPolicyConfirmed="changeFormDisability">
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
.price_builder {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.price_builder > div {
    display: flex;
    flex-direction: column;
    align-items: center;
}
.price_builder > div:first-child {
    align-items: start;
    gap: 20px;
}
.coupon_status {
    text-align: center;
}
.price_builder input[name="coupon"] {
    text-align: center;
}
</style>

