<script setup>
import { ref } from 'vue'
import mixins from "../../mixins";
import {useEventListener} from "../../storages/event_storage";
import {useMultiLanguageStore} from "../../storages/multi_language_content"
import InsertContent from '../widgets/insert-content.vue'

const multiLanguageStore = useMultiLanguageStore();
const eventListener = useEventListener()
const isSupportMessengerOpen = ref(false)
const telegramLink = ref("")
const supportMessengerEmailPlaceholder = ref("")
const supportMessengerMessagePlaceholder = ref("")

multiLanguageStore.getContentByTag("telegram_manager_account").then(insertData => {
    telegramLink.value = `tg://resolve?domain=${insertData[multiLanguageStore.currentLanguage]}`
})
multiLanguageStore.getContentByTag("support_messenger_email_placeholder").then(insertData => {
    supportMessengerEmailPlaceholder.value = insertData
})
multiLanguageStore.getContentByTag("support_messenger_message_placeholder").then(insertData => {
    supportMessengerMessagePlaceholder.value = insertData
})

const sendSupportMessage = (event) => {
    event.preventDefault()
    const currentUrl = window.location.href
    const formData = new FormData(event.target)
    formData.append("current_url", currentUrl)
    mixins.methods.postAPI(
        'support_request_message',
        formData,
        (response) => {
            eventListener.call('popup_alert:show', {
                title: response.status,
                text: response.data.replace("\n", "<br />")
            });
            event.target.reset()
            isSupportMessengerOpen.value = false
        })
    return false
}
</script>
<template class="">
    <div class="support_floating_button" v-show="!isSupportMessengerOpen" @click="isSupportMessengerOpen = true">
        <div>?</div>
        <span>HELP</span>
    </div>
    <div class="support_messenger" v-show="isSupportMessengerOpen">
        <div class="support_messenger__header">
            <div class="support_messenger__header__title">
                <h3><InsertContent>support_messenger_title</InsertContent></h3>
            </div>
            <div class="close" @click="isSupportMessengerOpen = false"></div>
        </div>
        <div class="support_messenger__body">
            <div class="support_messenger__body__message">
                <p><InsertContent>support_messenger_text</InsertContent></p>
            </div>
            <form class="support_messenger__form" @submit="sendSupportMessage">
                <div>
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" :placeholder="supportMessengerEmailPlaceholder[multiLanguageStore.currentLanguage]" required>
                </div>
                <div>
                    <label for="message">Message</label>
                    <textarea name="message" id="message" cols="30" rows="5" :placeholder="supportMessengerMessagePlaceholder[multiLanguageStore.currentLanguage]" minlength="5" maxlength="2000" required></textarea>
                </div>
                <div>
                    <button type="submit" title="Send the message">
                        <InsertContent>support_messenger_button</InsertContent>
                    </button>
                    <a :href="telegramLink" @click="isSupportMessengerOpen = false" title="Go to Telegram chat">
                        <button type="button" class="primary">
                            <font-awesome-icon icon="fab fa-telegram"></font-awesome-icon>
                            Telegram
                        </button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
    .support_messenger {
        position: fixed;
        z-index: 5;
        bottom: 10px;
        right: 10px;
        width: 300px;
        height: 360px;
        background: rgba(51, 175, 1, 0.1);
        border: rgba(255, 255, 255, .2) 1px solid;
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(5px);
    }
    .support_messenger__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #000;
    }
    .support_messenger__header .close:before{
        top: 5px
    }
    .support_messenger__header__title h3 {
        margin: 0;
    }
    .support_messenger__body {
        padding: 10px;
    }
    .support_messenger__body__message p {
        color: #58cc02;
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .support_messenger__form input textarea {
        width: 100%;
        border: 1px solid white;
        padding: 10px;
    }
    .support_messenger__form input{
        font-weight: bold;
    }
    .support_messenger__form textarea {
        height: 100px;
        resize: none;
    }
    .support_messenger__form label {
        margin-bottom: 1px;
    }
    .support_messenger__form > div {
        margin-bottom: 10px;
    }
    .support_messenger__form > div:last-child {
        display: flex;
        justify-content: space-between;
    }
    .support_messenger__form > div:last-child button:first-child {
        background-color: snow !important;
        color: #1b1f22 !important;
        font-weight: bold;
    }

    .support_messenger__form > div:last-child button:last-child {
        background-color: transparent !important;
        color: white !important;
        letter-spacing: normal;
        font-weight: normal;
    }

    .support_messenger__form button {
        margin-top: 5px;
    }
    .support_floating_button {
        position: fixed;
        z-index: 5;
        bottom: 20px;
        right: 20px;
        background: rgba(72, 255, 0, .05);
        border: rgba(255, 255, 255, .2) 1px solid;
        cursor: pointer;
        width: 82px;
        height: 82px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        letter-spacing: 0.2rem;
    }

    .support_floating_button:hover{
        color: #58cc02;
        border-color: rgba(255, 255, 255, 0.8);
        border-style: dashed;
    }

    .support_floating_button div {
        padding: 2px 5px;
        font-weight: bold;
        font-size: 25px;
        animation: flip-with-rotate 5s ease-in-out infinite;
    }

    @keyframes flip-with-rotate {
        0% {
            transform: perspective(10px) scaleX(1);
        }

        15% {
            color: #58cc02;
            transform: perspective(10px) scaleX(-1);
        }
        30% {
            transform: perspective(10px) scaleX(1);
        }
        100% {
            transform: perspective(10px) scaleX(1);
        }
    }
</style>
