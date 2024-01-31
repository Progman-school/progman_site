<script setup>
// import {computed} from "vue";
// import { useMultiLanguageStore } from './storages/multi_language_content.js'
import Bottom_notice from "./components/helpers/bottom_notice.vue";

// const multiLanguageStore = useMultiLanguageStore()
// multiLanguageStore.getAllTagContents()
// const pagePreloading = computed({
//     get () {
//         return !Object.keys(multiLanguageStore.contentArray).length
//     },
// })
</script>
<template>
    <language-changer></language-changer>
    <div id="main">
        <router-view v-slot="{ Component }">
            <transition name="slide-fade" mode="out-in">
                <component :is="Component" />
            </transition>
        </router-view>
    </div>
    <bottom_notice></bottom_notice>
    <popup-alert></popup-alert>
<!--    <div class="site_cover" v-show="pagePreloading">-->
<!--        <div class="loader">-->
<!--            <div class="loader-inner"></div>-->
<!--            <div class="loader-inner"></div>-->
<!--            <div class="loader-inner"></div>-->
<!--            <div class="loader-inner"></div>-->
<!--            <div class="loader-inner"></div>-->
<!--        </div>-->
<!--    </div>-->
</template>
<script>
import { RouterView } from 'vue-router'
import popupAlert from './components/helpers/popup-alert.vue'
import languageChanger from './components/helpers/language-changer.vue';

export default {
    name: "app",
    RouterView,
    components: {
        popupAlert,
        languageChanger,
    },
}
</script>

<style>
.v-enter-from,
.v-leave-to {
    opacity: 0;
}

.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateY(-20px);
    opacity: 0;
}

.site_cover {
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    position: absolute;
    z-index: 10;
    background:rgba(80, 80, 80, .75);
}
.loader{
    width: 270px;
    height: 50px;
    margin: 70px auto;
    position: relative;
    top: 35vh;
}
.loader .loader-inner{
    width: 25px;
    height: 25px;
    background: rgba(94, 98, 94, 0.97);
    position: absolute;
    top: 20%;
    -webkit-animation: loading-1 1s infinite ease-in-out;
    animation: loading-1 1s infinite ease-in-out;
}
.loader .loader-inner:nth-child(1){
    left: 40px;
    z-index: 1;
    -webkit-animation: loading-2 1s infinite ease-in-out;
    animation: loading-2 1s infinite ease-in-out;
}
.loader .loader-inner:nth-child(2){
    left: 80px;
    z-index: 2;
    -webkit-animation: loading-3 1s infinite ease-in-out;
    animation: loading-3 1s infinite ease-in-out;
}
.loader .loader-inner:nth-child(3){
    left: 120px;
    z-index: 5;
    -webkit-animation: loading-4 1s infinite ease-in-out;
    animation: loading-4 1s infinite ease-in-out;
}
.loader .loader-inner:nth-child(4){
    left: 160px;
    z-index: 3;
    -webkit-animation: loading-5 1s infinite ease-in-out;
    animation: loading-5 1s infinite ease-in-out;
}
.loader .loader-inner:nth-child(5){
    left: 200px;
    z-index: 4;
    -webkit-animation: loading-6 1s infinite ease-in-out;
    animation: loading-6 1s infinite ease-in-out;
}
@-webkit-keyframes loading-1{
    50%{ border-radius: 50%; }
}
@keyframes loading-1{
    50%{ border-radius: 50%; }
}
@-webkit-keyframes loading-2{
    50%{
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background: rgba(225, 245, 225, 0.71);
        opacity: .5;
        -webkit-transform: translateX(80px) translateY(-15px) rotate(360deg);
        transform: translateX(80px) translateY(-15px) rotate(360deg);
    }
}
@keyframes loading-2{
    50%{
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background: rgba(225, 245, 225, 0.71);
        opacity: .5;
        -webkit-transform: translateX(80px) translateY(-15px) rotate(360deg);
        transform: translateX(80px) translateY(-15px) rotate(360deg);
    }
}
@-webkit-keyframes loading-3{
    50%{
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background: rgba(225, 245, 225, 0.71);
        opacity: .5;
        -webkit-transform: translateX(40px) translateY(-15px) rotate(360deg);
        transform: translateX(40px) translateY(-15px) rotate(360deg);
    }
}
@keyframes loading-3{
    50%{
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background: rgba(225, 245, 225, 0.71);
        opacity: .5;
        -webkit-transform: translateX(40px) translateY(-15px) rotate(360deg);
        transform: translateX(40px) translateY(-15px) rotate(360deg);
    }
}
@-webkit-keyframes loading-4{
    50%{
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background: rgba(225, 245, 225, 0.71);
        opacity: .5;
        -webkit-transform: translateY(-15px) rotate(360deg);
        transform: translateY(-15px) rotate(360deg);
    }
}
@keyframes loading-4{
    50%{
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background: rgba(225, 245, 225, 0.71);
        opacity: .5;
        -webkit-transform: translateY(-15px) rotate(360deg);
        transform: translateY(-15px) rotate(360deg);
    }
}
@-webkit-keyframes loading-5{
    50%{
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background: rgba(225, 245, 225, 0.71);
        opacity: .5;
        -webkit-transform: translateX(-40px) translateY(-15px) rotate(360deg);
        transform: translateX(-40px) translateY(-15px) rotate(360deg);
    }
}
@keyframes loading-5{
    50%{
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background: rgba(225, 245, 225, 0.71);
        opacity: .5;
        -webkit-transform: translateX(-40px) translateY(-15px) rotate(360deg);
        transform: translateX(-40px) translateY(-15px) rotate(360deg);
    }
}
@-webkit-keyframes loading-6{
    50%{
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background: rgba(225, 245, 225, 0.71);
        opacity: .5;
        -webkit-transform: translateX(-80px) translateY(-15px) rotate(360deg);
        transform: translateX(-80px) translateY(-15px) rotate(360deg);
    }
}
@keyframes loading-6{
    50%{
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background: rgba(225, 245, 225, 0.71);
        opacity: .5;
        -webkit-transform: translateX(-80px) translateY(-15px) rotate(360deg);
        transform: translateX(-80px) translateY(-15px) rotate(360deg);
    }
}
</style>
