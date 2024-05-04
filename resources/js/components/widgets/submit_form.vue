<script setup>
import mixins from "../../mixins";
import { useEventListener } from "../../storages/event_storage.js"
import router from "../../router";

const props = defineProps({
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
    },
    preserveFunction: {
        type: Function,
        default: null
    }
})

const eventListener = useEventListener()
const submitForm = (event) => {
    event.preventDefault()
    let formData = new FormData(event.target)
    if (props.preserveFunction) {
        formData = props.preserveFunction(formData)
    }
    mixins.methods.postAPI(
        props.action,
        formData,
        (result) => {
            if (result.status === 'OK') {
                eventListener.call('popup_alert:show', {
                    title: result.data.status ?? 'Info!',
                    text: result.data?.alert_text ?? '',
                    href: result.data?.href_link ?? null,
                    url: result.data?.url_link ?? null,
                    button: result.data?.alert_button_name ?? 'Ok',
                });
            } else {
                eventListener.call('popup_alert:show', {
                    title: result.status ?? 'Error!',
                    text: result.error ?? 'Unexpected error!',
                    button: 'Ok',
                });
            }
            event.target.reset()
            props.is_disabled = true
            router.replace({query: null})
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
