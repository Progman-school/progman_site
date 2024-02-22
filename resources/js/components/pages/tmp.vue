<script>
import closer from '../widgets/closer.vue'
export default {
    name: "tmp",
    components: {
        closer,
    }
}
</script>

<script setup>
import { useFBRegistration } from "../../registrations/facebook.js"
import { useEventListener } from "../../storages/event_storage.js"
const eventListener = useEventListener()
useFBRegistration()
const showToSale = () => {
    eventListener.call('popup_alert:show', {
        title: 'Welcome!',
        text:'{{start_page_alert}}',
        template: '<fb:login-button scope="public_profile,email" onlogin="checkLoginState();"></fb:login-button>' +
            '<br/><br/>' +
            '<div id="status"></div>',
        // url: '/test',
        // button: 'Go to the test',
    });
}
</script>

<template>
    <article id="start">
        <section>
            <h2 class="major">Tmp testing</h2>
            <br/>
            <h3 id="status"></h3>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                Amet consequuntur dignissimos dolorum eum fugit magnam molestias quam quos vitae voluptas.
                Ad consequuntur eos fugiat harum magnam nostrum quia. Laborum, quas?
            </p>
            <ul class="actions" style="margin-top: 20px">
                <li><button class="button primary" @click="showToSale" >Open alert</button></li>
            </ul>
            <br/>
        </section>
        <closer />
    </article>
</template>


<style scoped>

</style>
