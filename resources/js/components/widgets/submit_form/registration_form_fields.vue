<script setup>
import InsertContent from '../insert_content.vue'
import {ref} from "vue";
const emit = defineEmits(['onPrivacyPolicyConfirmed'])

defineProps({
    "availableTypes": {
        type: Array,
        default: null,
    }
})

const registrationType = ref('email')
const isPrivacyPolicyConfirmed = ref(false)
const confirmPrivacyPolicy = (event) => {
    isPrivacyPolicyConfirmed.value = event.target.checked
    emit('onPrivacyPolicyConfirmed', isPrivacyPolicyConfirmed.value)
}

</script>

<template>
    <div class="field registration_fields">
        <div>
            <label for="uid_type">Contact type:</label>
            <select id="uid_type" name="uid_type" v-model="registrationType">
                <option value="email" :disabled="availableTypes && !availableTypes.includes('email')">
                    E-Mail address{{availableTypes && !availableTypes.includes('email') ? ' (unavailable here)' : ''   }}
                </option>
                <option value="telegram" :disabled="availableTypes && !availableTypes.includes('telegram')">
                    Telegram messenger{{availableTypes && !availableTypes.includes('telegram') ? ' (unavailable here)' : ''   }}
                </option>
            </select>
        </div>
        <div v-if="registrationType === 'email'" style="border: none">
            <label for="email_contact">Your E-mail:</label>
            <input type="email" id="email_contact" name="contact" placeholder="your-real@email.com" required>
        </div>
        <div v-if="registrationType === 'telegram'" style="border: none; font-style: italic;font-size: 14px;">
            <InsertContent>telegram_registration_choosing_warning</InsertContent>
        </div>
        <div v-if="registrationType === 'telegram'" style="border: none">
            <label for="telegram_contact">Your Telegram username or phone number:</label>
            <input type="text" id="telegram_contact" name="contact" placeholder="@my_telegram / +07778889911" maxlength="60" required>
        </div>
        <div style="border: none">
            <label for="name">Your name:</label>
            <input type="text" id="name" name="name" placeholder="Elon Mask" maxlength="60" required>
        </div>
        <div class="privacy_policy_sing_field">
            <input type="checkbox" id="privacy_policy" value="1" @change="confirmPrivacyPolicy">
            <label for="privacy_policy">
                <slot></slot>
            </label>
        </div>
    </div>
</template>

<style scoped>
.registration_fields select{
    color: #58cc02;
    font-weight: bold;
}
.privacy_policy_sing_field {
    margin-top: 10px;
    margin-bottom: 0 !important;
}
.registration_fields > div {
    margin-bottom: 20px;
}
</style>
