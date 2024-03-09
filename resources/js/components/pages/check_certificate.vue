<script setup>
import { useEventListener } from "../../storages/event_storage.js"
const eventListener = useEventListener()
import mixins from '../../mixins.js'
import Closer from '../widgets/closer.vue';
import InsertContent from '../widgets/insert_content.vue'

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
            <InsertContent>check_cert_text</InsertContent>
        </p>
        <section>
            <h3 class="major">
                <InsertContent>check_cert_subtitle</InsertContent>
            </h3>
            <form id="check_form" @submit="checkCertificate">
                <div class="fields">
                    <div class="field">
                        <label for="certificate">
                            <InsertContent>check_cert_number_label</InsertContent>
                        </label>
                        <input type="text" name="certificate" id="certificate" minlength="3" placeholder="Certificate number, include '-' symbols" required/>
                    </div>
                    <div class="field">
                        <label for="student">
                            <InsertContent>check_cert_last_name_label</InsertContent>
                        </label>
                        <input type="text" name="student" id="student" minlength="3" placeholder="Last name, as written on the cert" required/>
                    </div>
                </div>
                <ul class="actions">
                    <li><button type="submit" class="primary">Check certificate</button></li>
                </ul>
            </form>
            <div style="margin-bottom: 20px">
                <router-link :to="'start'" >
                    <strong>
                        <InsertContent>go_to_start_link</InsertContent>
                    </strong>
                </router-link>
                <br />
                <br />
            </div>
        </section>
        <Closer />
    </article>
</template>

<style scoped>

</style>
