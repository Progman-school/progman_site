<script setup>
import Closer from '../widgets/closer.vue';
import InsertContent from '../widgets/insert-content.vue'
</script>

<template>
    <article id="free">
        <h2 class="major">Personal consulting</h2>
        <p>
            How does it happen? You can ask me a question, and I will answer it.
            I will not give you a ready-made solution, but I will help you to find it.
            I will not do your work for you, but I will help you to do it yourself.
            I will not give you a fish, but I will teach you how to fish!
        </p>
        <br />
        <h4>Fill out this form</h4>
        <form id="test_form" @submit="saveTestData">
            <content class="fields" v-if="isShowedTestForm">

            </content>
        </form>
        <p class="footnote">
            We constantly add something new. Check this page!
        </p>
        <div style="margin-bottom: 20px">
            <ul class="actions" v-if="isShowedTestForm">
                <li><button type="submit" class="primary" :disabled="!isPrivacyPolicyConfirmed">Finish the test</button></li>
            </ul>
            <br />
            <br />
        </div>
        <Closer />
    </article>
</template>

<style scoped>

</style>
