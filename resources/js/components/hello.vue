<template>
    <header id="header">
        <div class="logo">
        </div>
        <div class="content">
            <div class="inner">
                <h1>ProgMan school</h1>
                <p>
                    <insert-content>main_page_text</insert-content>
                </p>
                <div>
                        <carousel-3d>
                            <slide :index="0">
                                <span>Slide 1 Content</span>
                            </slide>
                            <slide :index="1">
                                <span>Slide 2 Content</span>
                            </slide>
                        </carousel-3d>
                </div>
            </div>
        </div>
        <navigate></navigate>
    </header>
</template>

<script>
    import insertContent from '../components/helpers/insert-content.vue'
    import navigate from "./navigate.vue"
    // import courseCarousel from "../components/helpers/course_carousel.vue"
    import { Carousel3d, Slide } from 'vue-carousel-3d';

    export default {
        name: "hello",
        components: {
            insertContent,
            navigate,
            Carousel3d,
            Slide
            // courseCarousel,
        },
    }
</script>

<style scoped>

</style>
