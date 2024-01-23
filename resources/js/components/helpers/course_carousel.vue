<template>
    <carousel-3d>
        <slide :index="0">
            Slide 1 Content
        </slide>
        <slide :index="1">
            Slide 2 Content
        </slide>
        <slide :index="2">
            Slide 3 Content
        </slide>
        <slide :index="3">
            Slide 4 Content
        </slide>
        <slide :index="4">
            Slide 5 Content
        </slide>
    </carousel-3d>
</template>

<script>
import { Carousel3d, Slide } from 'vue-carousel-3d';

export default {
    name: "course-carousel",
    components: {
        Carousel3d, Slide
    }
};
</script>

<style scoped>

</style>
