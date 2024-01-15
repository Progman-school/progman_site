<script>
import closer from '../helpers/closer.vue';
import insertContent from '../helpers/insert-content.vue'

export default {
    name: "check-certificate",
    components: {
        closer,
        insertContent,
    },
}
</script>

<script setup>
import { useEventListener } from "../../storages/event_storage.js"
const eventListener = useEventListener()
import mixins from '../../mixins.js'

const showCheckResult = (resultData) => {
    eventListener.call('popup_alert:show', {
        title: resultData.status,
        text: resultData.data,
        href: null,
        url: null,
        button: null,
    })
    document.getElementById('check_form').reset()
}

const checkCertificate = (event) => {
    event.preventDefault()
    mixins.methods.postAPI(
        'check_certificate',
        new FormData(event.target),
        showCheckResult
    );
}

</script>
<template>
    <article id="test">
        <h2 class="major">Certificate verification</h2>
        <p>
            <insert-content>check_cert_text</insert-content>
        </p>
        <section>
            <h3 class="major">
                <insert-content>check_cert_subtitle</insert-content>
            </h3>
            <form id="check_form" @submit="checkCertificate">
                <div class="fields">
                    <div class="field">
                        <label for="certificate">
                            <insert-content>check_cert_number_label</insert-content>
                        </label>
                        <input type="text" name="certificate" id="certificate" minlength="3" placeholder="Certificate number, include '-' symbols" required/>
                    </div>
                    <div class="field">
                        <label for="student">
                            <insert-content>check_cert_last_name_label</insert-content>
                        </label>
                        <input type="text" name="student" id="student" minlength="3" placeholder="Last name, as written on the cert" required/>
                    </div>
                </div>
                <ul class="actions">
                    <li><button type="submit" class="primary">Check certificate</button></li>
                </ul>
            </form>
            <div style="margin-bottom: 20px">
                <router-link :to="'/start'" >
                    <strong>
                        <insert-content>go_to_start_link</insert-content>
                    </strong>
                </router-link>
                <br />
                <br />
            </div>
        </section>
        <closer />
    </article>

</template>

<style scoped>

</style>
