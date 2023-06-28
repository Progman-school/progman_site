<script>
import closer from '../helpers/closer.vue';
import insertContent from '../helpers/insert-content.vue'

export default {
    name: "conditions",
    components: {
        closer,
        insertContent,
    },
}
</script>

<template>
    <article id="conditions">
        <h2 class="major">Study conditions</h2>
        <section>
            <h4>Message from the teacher:</h4>
            <blockquote>
                <insert-content>conditions_teacher_message</insert-content>
            </blockquote>
        </section>
        <hr />
        <section>
            <insert-content>course_description_conditions</insert-content>
        </section>
        <hr/>
        <section>
            <insert-content>course_stack_conditions</insert-content>
        </section>
        <hr/>
        <section>
            <insert-content>mentor_experience_conditions</insert-content>
        </section>
        <div style="margin-bottom: 20px">
            <router-link :to="'/start'" >
                <strong>
                    <insert-content>go_to_start_link</insert-content>
                </strong>
            </router-link>
            <br />
            <br />
        </div>
        <closer />
    </article>
</template>

<style scoped>

</style>
