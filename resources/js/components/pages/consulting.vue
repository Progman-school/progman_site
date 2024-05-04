<script setup>
import Closer from "../widgets/closer.vue"
import InsertContent from '../widgets/insert_content.vue'
import SubmitForm from "../widgets/submit_form.vue"
import RegistrationFormFields from "../widgets/submit_form/registration_form_fields.vue"
import {onMounted, ref} from "vue"
import {useMultiLanguageStore} from "../../storages/multi_language_content"
import WeekDaysFormField from "../widgets/submit_form/weekday_checkbox_set_form_item.vue"
import CouponDiscounter from "../widgets/submit_form/coupon_discounter.vue"
import {useProductStorage} from "../../storages/product_storage"

const multiLanguageStore = useMultiLanguageStore()
const productStorage = useProductStorage()

const productAmount = ref(1)
const product = ref()

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

function setRequestParams(formData) {
    formData.append('product_id', product.value.id)
    return formData
}

onMounted(() => {
    productStorage.getProductByName('Personal consulting').then((productData) => {
        product.value = productData
    })
})

</script>

<template>
    <article>
        <h2 class="major">Personal consulting</h2>
        <p>
            <insert-content>consulting_page_description</insert-content>
        </p>
        <p>
            {{product["description_" + multiLanguageStore?.currentLanguage] ?? ''}}
        </p>
        <section>
            <SubmitForm
                action="add_request"
                :is_disabled="isDisabledForm"
                submit_button_name="Send request"
                :isVisibleSubmitButton="true"
                :preserve-function="setRequestParams"
            >
                <div class="field">
                    <label><insert-content>consulting_page_hours_label</insert-content></label>
                    <div class="price_builder">
                        <div>
                            <div>
                                <input type="radio" id="hours_1" name="quantity" value=1 v-model="productAmount" required checked :disabled="productAmount >= 3">
                                <label for="hours_1">One</label>
                            </div>
                            <div>
                                <input type="radio" id="hours_2" name="quantity" v-model="productAmount" value=2 :disabled="productAmount >= 3">
                                <label for="hours_2">Two</label>
                            </div>
                            <div v-if="productAmount < 3">
                                <input type="radio" id="hours_more" name="quantity" value="3" v-model="productAmount">
                                <label for="hours_more">More</label>
                            </div>
                            <div v-if="productAmount >= 3">
                                <label for="hours_number">Your number (max 16)</label>
                                <input type="number" id="hours_number" name="quantity" value=3 min=2 max=16 v-model="productAmount" @input="valueRestriction" required>
                            </div>
                        </div>
                        <CouponDiscounter :couponTypeId="product?.coupon_type?.id" :unit-amount="productAmount" :unit-price="product?.unit_price" />
                    </div>
                </div>
                <WeekDaysFormField><insert-content>consulting_page_weekdays_label</insert-content></WeekDaysFormField>
                <div class="field">
                    <label for="city"><insert-content>consulting_page_city_label</insert-content></label>
                    <input type="text" name="city" id="city" minlength="3" placeholder="Your current city (country into the brackets)" required/>
                </div>
                <div class="field">
                    <label for="details"><insert-content>consulting_page_message_label</insert-content>*</label>
                    <textarea name="details" id="details" maxlength="500" placeholder="Any additional wishes or questions (optional)" rows="3"></textarea>
                </div>
                <RegistrationFormFields @onPrivacyPolicyConfirmed="changeFormDisability">
                    <InsertContent>registration_form_privacy_policy_link</InsertContent>
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

