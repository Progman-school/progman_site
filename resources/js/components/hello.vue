<script setup>
import InsertContent from '../components/helpers/insert-content.vue'
import Navigate from "./navigate.vue"
import CourseCarousel from "../components/helpers/course_carousel.vue";
</script>

<template>
    <header id="header">
        <div class="logo">
        </div>
        <div class="content">
            <div class="inner">
                <h1>ProgMan school</h1>
                <p>
                    <InsertContent>main_page_text</InsertContent>
                </p>
                <div>
                    <CourseCarousel></CourseCarousel>
                </div>
            </div>
        </div>
        <Navigate></Navigate>
    </header>
</template>

<style scoped>

</style>
