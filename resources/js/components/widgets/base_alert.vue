<script setup>
import {ref, defineExpose} from "vue";
const name = 'base-alert'
const show = ref(false)
const title = ref("")
const elements = ref("")

defineOptions({
    name: 'base-alert',
    inheritAttrs: false,
})

const modalClose = () => {
    this.show = false
}

const scroling = () => {
    window.scrollTo(0, 0)
    if (window.scrollY > 0) {
        requestAnimationFrame(scroling)
    }
}

const modalOpen = () => {
    this.show = true
    this.scroling()
}

defineExpose({
    show,
    title,
    elements,
    modalClose,
    modalOpen,
})

</script>

<template>
    <div class="popup_cover" v-show="show" @click.self="modalClose">
        <div id="popup_alert_box" ref="alertBox">
            <div class="popup_close" @click="modalClose"></div>
            <div class="popup_content">
                <strong>{{title}}</strong>
                <div class="alert_elements">
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>
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

