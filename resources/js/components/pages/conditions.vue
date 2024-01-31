<script setup>
import Closer from '../helpers/closer.vue';
import InsertContent from '../helpers/insert-content.vue'
</script>

<template>
    <article id="conditions">
        <h2 class="major">Study conditions</h2>
        <section>
            <h4>Message from the teacher:</h4>
            <blockquote>
                <InsertContent>conditions_teacher_message</InsertContent>
            </blockquote>
        </section>
        <hr />
        <section>
            <InsertContent>course_description_conditions</InsertContent>
        </section>
        <hr/>
        <section>
            <InsertContent>course_stack_conditions</InsertContent>
        </section>
        <hr/>
        <section>
            <InsertContent>mentor_experience_conditions</InsertContent>
        </section>
        <div style="margin-bottom: 20px">
            <router-link :to="'start'" >
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

</style>
