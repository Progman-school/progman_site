<script setup>
import mixins from "../../mixins.js";
import {ref} from "vue";
import { useEventListener } from "../../storages/event_storage.js"

const eventListener = useEventListener()
const cookieKey = "cookie_alert"
const show = ref(false)

const click = () => {
    // mixins.methods.setCookie(cookieKey, true, 100)
    show.value = false
    // console.log(mixins.methods.getCookie(cookieKey))
}

const create = () => {
    eventListener.add('cookie_alert:show', (data) => {
        // mixins.methods.setCookie(cookieKey, false, 0)
        show.value = true
    })
    show.value = !mixins.methods.getCookie(cookieKey)
}
create()

</script>
<template>
    <transition mode="out-in">
        <div class="bottom_notice" v-show="show">
            <h6><insert-content>cookie_banner_label</insert-content></h6>
            <p>
                <insert-content>cookie_banner_text</insert-content>
            </p>
            <button class="primary" @click="click"><insert-content>cookie_banner_button</insert-content></button>
        </div>
    </transition>
</template>

<script>
import insertContent from '../helpers/insert-content.vue'

export default {
    name: "bottom_notice",
    components: {
        insertContent,
    }
}
</script>

<style scoped>
.bottom_notice {
    position: fixed;
    left: 2px;
    right: 2px;
    bottom: 0;
    z-index: 8;
    border: 1px white solid;
    padding: 20px;
    background: #34625782;
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}
.bottom_notice h6 {
    text-align: center;
    font-size: 12px;
}
.bottom_notice p {
    text-align: center;
    font-size: 14px;
}
.bottom_notice button {
    float: right;
}
.bottom_notice a {
    border-bottom: dotted 1px rgb(21 143 36 / 76%);
    color: #50aa60;
    font-weight: 200;
}
.bottom_notice a:hover {
    border-bottom: dotted 1px rgb(21 143 36 / 100%);
    color: #55d26c;
}
</style>
