<script setup>
import { useAttrs } from 'vue'
const attrs = useAttrs()

</script>
<template>
    <form @submit="attrs.action">
        <content class="fields">
            <slot></slot>
        </content>
        <ul class="actions" v-if="isShowedTestForm">
            <li><button type="submit" class="primary" :disabled="attrs.is_privacyPolicyConfirmed">attrs.button_name</button></li>
        </ul>
    </form>
</template>

<style scoped>

</style>
