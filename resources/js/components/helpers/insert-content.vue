<script>
export default {
    name: "insert-content",
    props: {
        "set_class": ""
    },
}
</script>

<script setup>
import { useSlots, computed } from 'vue'
import { useMultiLanguageStore } from '../../storages/multi_language_content.js'

const slots = useSlots()
const multiLanguageStore = useMultiLanguageStore();
const tag = slots.default()[0].children

const created = () => {
    multiLanguageStore.getContentByTag(tag)
}

const langContent = computed({
    get () {
        return multiLanguageStore.contentArray[tag] === undefined ? '' :
            multiLanguageStore.contentArray[tag][multiLanguageStore.currentLanguage]
    },
})
created()
</script>

<template>
    <transition mode="out-in">
    <div class="widget_preloader" v-show="!langContent">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    </transition>
    <transition mode="out-in">
        <content :class="set_class" v-html="langContent" v-show="langContent"></content>
    </transition>
</template>

<style scoped>
content {
    display: inline-block;
}
/*the wget preloader*/
.widget_preloader{
    text-align: center;
    margin: 20px 0;
}
.widget_preloader span{
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: rgba(125, 152, 125, 0.6);
    display: inline-block;
}
.widget_preloader span:first-child{
    animation: loading-2 0.5s linear infinite;
    opacity: 0;
    transform: translate(-20px);
}
.widget_preloader span:nth-child(2),
.widget_preloader span:nth-child(3){
    animation: loading-3 0.5s linear infinite;
}
.widget_preloader span:last-child{
    animation: loading-1 0.5s linear infinite;
}
@-webkit-keyframes loading-1{
    100%{
        transform:translate(40px);
        opacity: 0;
    }
}
@keyframes loading-1{
    100%{
        transform:translate(40px);
        opacity: 0;
    }
}
@-webkit-keyframes loading-2{
    100%{
        transform:translate(20px);
        opacity: 1;
    }
}
@keyframes loading-2{
    100%{
        transform:translate(20px);
        opacity: 1;
    }
}
@-webkit-keyframes loading-3{
    100%{
        transform:translate(20px);
    }
}
@keyframes loading-3{
    100%{
        transform:translate(20px);
    }
}

</style>
