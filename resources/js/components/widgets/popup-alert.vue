<script setup>
import {ref} from 'vue'
import { useEventListener } from "../../storages/event_storage.js"
import { useMultiLanguageStore } from '../../storages/multi_language_content.js'

defineOptions({
    name: 'popup-alert',
    inheritAttrs: false,
})

const eventListener = useEventListener()
const multiLanguageStore = useMultiLanguageStore()

const show = ref(false)
const props = ref([])

const created = () => {
    eventListener.add('popup_alert:show', (data) => {
        for (let key in data) {
            multiLanguageStore.replaceContent(data[key], false).then(
                data => {props.value[key] = data}
            )
        }
        modalOpen()
    })
}

const modalClose = () => {
    show.value = false
}
function scroling() {
    window.scrollTo(0, 0)
    if (window.scrollY > 0) {
        requestAnimationFrame(scroling)
    }
}
const modalOpen = () => {
    // window.scrollBy(0, 0);
    show.value = true
    scroling()
}
created()
</script>

<template>
    <transition name="slide-fade" mode="out-in">
        <div class="popup_cover" v-show="show" @click.self="modalClose">
            <div id="popup_alert_box" ref="alertBox">
                <div class="popup_close" @click="modalClose"></div>
                <div class="popup_content">
                    <strong>{{props.title}}</strong>
                    <div class="alert_elements">
                        <p v-html="props.text"></p>
                        <div class="popup_keys_box" v-if="props.template" v-html="props.template"></div>
                        <div class="popup_keys_box" v-if="props.button">
                            <router-link class="button_links" :to="props.url" v-if="props.url">
                                <button @click="modalClose">{{props.button}}</button>
                            </router-link>
                            <a class="button_links" :href="props.href" v-if="props.href">
                                <button @click="modalClose">{{props.button}}</button>
                            </a>
                            <button v-if="!props.href && !props.url && props.button"  @click="modalClose">{{props.button}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<style scoped>
    #popup_alert_box {
        position: relative;
        width: 50%;
        margin: 0 auto;
        top:130px;
        border: white 1px solid;
        border-radius: 10px;
        padding: 55px 15px 20px 15px;
        background: rgb(37 72 64 / 55%);;
    }
    #popup_alert_box strong {
        position: absolute;
        background: rgba(15, 15, 15, .4);
        width: 94%;
        padding: 2px 15px;
        top: 12px;
        font-size: 20px;
        text-transform: uppercase;
        border-radius: 10px;
        border-top: white 1px solid;

    }
    #popup_alert_box p {
        padding: 15px 5px 0 10px;
    }
    .popup_keys_box {
        margin-top: 20px;
        width: 100%;
        text-align: center;
    }
    .popup_keys_box button {
        width: 80%;
    }
    .popup_cover {
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        position: absolute;
        z-index: 4;
        background: rgb(18 28 33 / 60%);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }
    .popup_close {
        display: block;
        position: absolute;
        z-index: 5;
        top: 0;
        right: 2%;
        width: 4rem;
        height: 4rem;
        cursor: pointer;
        text-indent: 4rem;
        overflow: hidden;
        white-space: nowrap;
    }
    .popup_close:before {
        -moz-transition: background-color 0.2s ease-in-out;
        -webkit-transition: background-color 0.2s ease-in-out;
        -ms-transition: background-color 0.2s ease-in-out;
        transition: background-color 0.2s ease-in-out;
        content: '';
        display: block;
        position: absolute;
        top: 0.75rem;
        left: 0.75rem;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 100%;
        background-position: center;
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' width='20px' height='20px' viewBox='0 0 20 20' zoomAndPan='disable'%3E%3Cstyle%3Eline %7B stroke: %23ffffff%3B stroke-width: 1%3B %7D%3C/style%3E%3Cline x1='2' y1='2' x2='18' y2='18' /%3E%3Cline x1='18' y1='2' x2='2' y2='18' /%3E%3C/svg%3E");
        background-size: 20px 20px;
        background-repeat: no-repeat;
    }

    .popup_close:hover:before {
        background-color: rgba(255, 255, 255, 0.075);
    }

    .popup_close:active:before {
        background-color: rgba(255, 255, 255, 0.175);
    }
    @media all and (max-width: 736px) {
        #popup_alert_box {
            width: 95%;
        }
    }
</style>
