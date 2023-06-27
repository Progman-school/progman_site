<script>
import closer from '../helpers/closer.vue'
import insertContent from '../helpers/insert-content.vue'
export default {
    name: "start",
    components: {
        closer,
        insertContent,
    }
}
</script>

<script setup>
import { useEventListener } from "../../storages/event_storage.js"
const eventListener = useEventListener()
const showToSale = () => {
    eventListener.call('popup_alert:show', {
        title: 'Welcome!',
        text:'{{start_page_alert}}',
        url: '/test',
        button: 'Go to the test',
    });
}

</script>

<template>
    <article id="start">
        <section>
            <h2 class="major">Start learning!</h2>
            <insert-content>start_page_info</insert-content>
            <br/>
            <ul class="actions" style="margin-top: 20px">
                <li><button class="button primary" @click="showToSale" >Start test</button></li>
                <!--<button class="button">Fast study</button>-->
            </ul>
            <br/>
        </section>
        <closer />
    </article>
</template>


<style scoped>

</style>
