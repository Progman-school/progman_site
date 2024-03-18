<script setup>
import Closer from '../widgets/closer.vue';
import InsertContent from '../widgets/insert_content.vue'
</script>

<template>
    <article id="conditions">
        <h2 class="major">Study conditions</h2>
        <section>
            <h4>Message from the teacher:</h4>
            <blockquote>
                <InsertContent>conditions_teacher_message</InsertContent>
            </blockquote>
            <div style="margin: 20px auto">
                <div class="goto_test_button">
                    <a href="consulting">
                        <button class="primary">Get a personal consultation</button>
                    </a>
                </div>
            </div>
        </section>
        <hr />
        <section>
            <InsertContent>course_description_conditions</InsertContent>
            <div class="goto_test_button">
                <a href="study_plan">
                    <button class="primary">Make study plan</button>
                </a>
            </div>
        </section>
        <hr/>
        <section>
            <InsertContent>mentor_experience_conditions</InsertContent>
        </section>
        <div style="margin-bottom: 20px">
            <router-link :to="'study_plan'" >
                <strong>
                    <InsertContent>go_to_start_link</InsertContent>
                </strong>
            </router-link>
            <br />
            <br />
        </div>
        <Closer />
    </article>
</template>

<style scoped>
.goto_test_button {
    text-align: center;
    margin: 20px auto;
}
</style>
