<script setup>
import {usePreloadedDataStorage} from "../../storages/preloaded_content_storage";
import {useMultiLanguageStore} from "../../storages/multi_language_content";
import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'
import {ref} from "vue";

const multiLanguageStore = useMultiLanguageStore()
const preloadedDataStorage = usePreloadedDataStorage()

const name = "course-carousel"
preloadedDataStorage.getCoursesList();

const toggleDetails = (event) => {
    console.log(event.target.parentElement)
    event.target.parentElement.querySelector(".short_details p").style.display = event.target.p.style.display === "none" ? "block" : "none"
    event.target.parentElement.querySelector(".full_details div").style.display = event.target.div.style.display === "none" ? "block" : "none"
}
</script>

<template>
    <Carousel :itemsToShow="3.95" :wrapAround="true" :transition="500">
        <Slide v-for="course in preloadedDataStorage.courses" :key="course.id">
            <div class="carousel__item">
                <h5 @click="toggleDetails">{{course.name}}</h5>
                <p class="short_details">{{course['description_' + multiLanguageStore.currentLanguage].slice(0, 20)}}</p>
                <div class="full_details" style="display: none">
                    <p>{{course['description_' + multiLanguageStore.currentLanguage]}}</p>
                    <ul>
                        <li v-for="technology in course.technologies" :title=technology.description>
                            {{technology.name}}
                        </li>
                    </ul>
                </div>
            </div>
        </Slide>
    </Carousel>
</template>

<style scoped>
.carousel__item {
    background: rgba(1, 3, 4, 0.4);
    border-right: white 1px solid;
    border-left: white 1px solid;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    color: #000;
    text-align: center;
    margin: 5px;
}
.carousel__item p {
    color: white;
    min-width: fit-content !important;
    font-size: 8px !important;
    cursor: default;
    margin: 0;
}

.carousel__item div > p {
    overflow-y: scroll;
    height: 100px !important;
}
.carousel__item h5 {
    padding-top: 15px;
    cursor: default;
}
.carousel__item h5:hover {
    text-decoration: underline;
    cursor: pointer;
    color: #50aa60;
}
.carousel__item ul {
    list-style: none;
    display: grid;
    grid-template-columns: repeat(3,1fr);
    grid-gap: 2px;
    padding: 0 6px;
}
.carousel__item ul li {
    color: white;
    font-size: 6px;
    padding: 2px;
    border-bottom: 1px solid lightgrey;
    cursor: default;
}

.carousel__slide {
    padding: 5px;
}

.carousel__viewport {
    perspective: 2000px;
}

.carousel__track {
    transform-style: preserve-3d;
}

.carousel__slide--sliding {
    transition: 0.5s;
}

.carousel__slide {
    opacity: 0.9;
    transform: rotateY(-20deg) scale(0.9);
}

.carousel__slide--active ~ .carousel__slide {
    transform: rotateY(20deg) scale(0.9);
}

.carousel__slide--prev {
    opacity: 1;
    transform: rotateY(-10deg) scale(0.95);
}

.carousel__slide--next {
    opacity: 1;
    transform: rotateY(10deg) scale(0.95);
}

.carousel__slide--active {
    opacity: 1;
    transform: rotateY(0) scale(1.1);
}
</style>
