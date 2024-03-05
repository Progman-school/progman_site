<script setup>
defineProps({
    isVisible: {
        type: Boolean,
        default: false
    }
})
</script>

<template>
    <transition name="slide-fade" mode="out-in">
        <div class="widget_preloader" v-show="isVisible">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </transition>
</template>

<style scoped>
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
