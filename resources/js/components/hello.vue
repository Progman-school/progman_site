<script setup>
import InsertContent from './widgets/insert_content.vue'
import Navigate from "./navigate.vue"
import CourseCarousel from "./widgets/course_carousel.vue";
</script>

<template>
    <header id="header">
        <div class="logo">
        </div>
        <div class="content">
            <div class="inner">
                <h1>ProgMan site</h1>
                <p>
                    <InsertContent>main_page_text</InsertContent>
                </p>
                <div>
                    <CourseCarousel redirect-path="study_plan"></CourseCarousel>
                </div>
            </div>
        </div>
        <Navigate></Navigate>
    </header>
</template>

<style scoped>

</style>
