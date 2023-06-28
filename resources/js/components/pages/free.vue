<template>
    <article id="free">
        <h2 class="major">Useful files for free</h2>
        <span class="image main"><img src="images/4.jpg" alt="" /></span>
        <p>
            <insert-content set_class="not_weight">free_files_description</insert-content>
        </p>
        <br />
        <h4>
            <insert-content>important_free_label</insert-content>:
        </h4>
        <ul class="alt second_alt">
            <insert-content>important_free_files</insert-content>
        </ul>
        <h4>
            <insert-content>regular_free_label</insert-content>:
        </h4>
        <ul class="alt second_alt">
            <insert-content>regular_free_files</insert-content>
        </ul>
        <h4>
            <insert-content>languages_books_free_label</insert-content>:
        </h4>
        <ul class="alt second_alt">
            <insert-content>languages_books_free_files</insert-content>
        </ul>
        <p class="footnote">
            We constantly add something new. Check this page!
        </p>
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

<script>
    import closer from '../helpers/closer.vue';
    import insertContent from '../helpers/insert-content.vue'

    export default {
        name: "free",
        components: {
            closer,
            insertContent,
        }
    }
</script>

<style scoped>

</style>
