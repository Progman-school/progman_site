<script setup>
import Closer from '../widgets/closer.vue'
import InsertContent from '../widgets/insert-content.vue'
import { useEventListener } from "../../storages/event_storage.js"

const eventListener = useEventListener()
const showToSale = () => {
    eventListener.call('popup_alert:show', {
        title: 'Welcome!',
        text:'{{start_page_alert}}',
        url: `test`,
        button: 'Go to the test',
    });
}

</script>

<template>
    <article id="start">
        <section>
            <h2 class="major">Start learning!</h2>
            <InsertContent>start_page_info</InsertContent>
            <br/>
            <ul class="actions" style="margin-top: 20px">
                <li><button class="button primary" @click="showToSale" >Start test</button></li>
            </ul>
            <br/>
        </section>
        <Closer />
    </article>
</template>


<style scoped>

</style>
