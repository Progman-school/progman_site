<script setup>
import { ref } from 'vue'
import mixins from "../../mixins";
import {useEventListener} from "../../storages/event_storage";
import router from "../../router";
const eventListener = useEventListener()
const isSupportMessengerOpen = ref(false)
const sendSupportMessage = (event) => {
    event.preventDefault()
    const formData = new FormData(event.target)
    formData.append("current_url", router.currentRoute.value.path)
    mixins.methods.postAPI(
        'support_request_message',
        formData,
        (response) => {
            eventListener.call('popup_alert:show', {
                title: response.status,
                text: response.data,
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
                <h3>Support</h3>
            </div>
            <div class="close" @click="isSupportMessengerOpen = false"></div>
        </div>
        <div class="support_messenger__body">
            <div class="support_messenger__body__message">
                <p>How can we help you?</p>
            </div>
            <form class="support_messenger__form" @submit="sendSupportMessage">
                <div>
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Your contact email" required>
                </div>
                <div>
                    <label for="message">Message</label>
                    <textarea name="message" id="message" cols="30" rows="5" placeholder="Your text" minlength="5" maxlength="2000" required></textarea>
                </div>
                <div>
                    <button type="submit">Send</button>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
    .support_messenger {
        position: fixed;
        z-index: 3;
        bottom: 10px;
        right: 10px;
        width: 300px;
        height: 360px;
        background: rgba(72, 255, 0, .05);
        border: rgba(255, 255, 255, .2) 1px solid;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        letter-spacing: 0.2rem;
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
    .support_messenger__form button {
        margin-top: 5px;
    }
    .support_floating_button {
        position: fixed;
        z-index: 3;
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
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }

    .support_floating_button:hover{
        color: #58cc02;
        border-color: rgba(255, 255, 255, 0.8);
        border-style: dashed;
    }

    .support_floating_button div {
        font-weight: bold;
        font-size: 25px;
        animation: flip-with-rotate 5s ease-in-out infinite;
    }

    @keyframes flip-with-rotate {
        0% {
            transform: rotateY(0);
        }

        15% {
            color: #58cc02;
            transform: rotateY(180deg);
        }
        30% {
            transform: rotateY(0);
        }
        100% {
            transform: rotateY(0);
        }
    }
</style>
