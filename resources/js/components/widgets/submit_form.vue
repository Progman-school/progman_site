<script setup>
import mixins from "../../mixins";
import { useEventListener } from "../../storages/event_storage.js"

defineProps({
    action: {
        type: String,
        default: null
    },
    submit_button_name: {
        type: String,
        default: 'Send'
    },
    isVisibleSubmitButton: {
        type: Boolean,
        default: false
    },
    is_disabled: {
        type: Boolean,
        default: true
    }
})

const eventListener = useEventListener()
const submitForm = (form) => {
    form.preventDefault()
    const formData = new FormData(form.target)
    mixins.methods.postAPI(
        'add_request',
        formData,
        () => {
            eventListener.call('popup_alert:show', {
                title: preLoginResult.data.alert_title ?? 'Info!',
                text: preLoginResult.data.alert_text ?? '',
                href: preLoginResult.data.href_link ?? null,
                url: preLoginResult.data.url_link ?? null,
                button: preLoginResult.data.alett_button_name ?? 'Ok',
            });
            form.reset()
        }
    )
}

</script>
<template>
    <form @submit="submitForm">
        <content class="fields">
            <slot></slot>
        </content>
        <ul class="actions" v-show="isVisibleSubmitButton">
            <li>
                <button type="submit" class="primary" :disabled="is_disabled">
                    {{submit_button_name}}
                </button>
            </li>
        </ul>
    </form>
</template>

<style scoped>
.actions {
    margin-top: 20px;
    margin-bottom: 40px;
}
</style>
