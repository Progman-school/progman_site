<script setup>
import InsertContent from '../insert-content.vue'
import {ref} from "vue";
const emit = defineEmits(['onPrivacyPolicyConfirmed'])

const showEmailField = ref('email')
const changeRegistrationType = (event) => {
    showEmailField.value = event.target.value
}

const isPrivacyPolicyConfirmed = ref(false)
const confirmPrivacyPolicy = (event) => {
    isPrivacyPolicyConfirmed.value = event.target.checked
    emit('onPrivacyPolicyConfirmed', isPrivacyPolicyConfirmed.value)
}

</script>

<template>
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
        <div v-if="showEmailField === 'telegram'" style="border: none; font-style: italic;font-size: 14px;">
            <InsertContent>telegram_registration_choosing_warning</InsertContent>
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
                <slot></slot>
            </label>
        </div>
    </div>
</template>

<style scoped>

</style>
