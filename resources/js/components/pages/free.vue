<script setup>
import Closer from '../widgets/closer.vue';
import InsertContent from '../widgets/insert-content.vue'
</script>

<template>
    <article id="free">
        <h2 class="major">Useful files for free</h2>
        <span class="image main"><img src="/images/4.jpg" alt="" /></span>
        <p>
            <InsertContent set_class="not_weight">free_files_description</InsertContent>
        </p>
        <br />
        <h4>
            <InsertContent>important_free_label</InsertContent>:
        </h4>
        <ul class="alt second_alt">
            <InsertContent>important_free_files</InsertContent>
        </ul>
        <h4>
            <InsertContent>regular_free_label</InsertContent>:
        </h4>
        <ul class="alt second_alt">
            <InsertContent>regular_free_files</InsertContent>
        </ul>
        <h4>
            <InsertContent>languages_books_free_label</InsertContent>:
        </h4>
        <ul class="alt second_alt">
            <InsertContent>languages_books_free_files</InsertContent>
        </ul>
        <p class="footnote">
            We constantly add something new. Check this page!
        </p>
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
