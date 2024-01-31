<script setup>
import {usePreloadedDataStorage} from "../../storages/preloaded_content_storage";
import {useMultiLanguageStore} from "../../storages/multi_language_content";
import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'
import {ref} from "vue";
import router from '../../router.js'

const multiLanguageStore = useMultiLanguageStore()
const preloadedDataStorage = usePreloadedDataStorage()

const name = "course-carousel"
preloadedDataStorage.getCoursesList();

const autoPlay = ref(3000)

const toggleDetails = (event) => {
    autoPlay.value = 0
    if (!event.target.parentElement.parentElement.classList.contains("carousel__slide--active")) {
        return false
    }
    event.target.parentElement.parentElement.parentElement.querySelectorAll(".short_details, .full_details").forEach((element) => {
        element.style.display = element.style.display === "none" ? "block" : "none"
        element.style.display = element.style.display === "none" ? "block" : "none"
    })
    const shortDetails = event.target.parentElement.querySelector(".short_details")
    const fullDetails = event.target.parentElement.querySelector(".full_details")
    shortDetails.style.display = shortDetails.style.display === "none" ? "block" : "none"
    fullDetails.style.display = fullDetails.style.display === "none" ? "block" : "none"
}

</script>

<template>
    <Carousel :autoplay=autoPlay :itemsToShow="3.32" :wrapAround="true" :transition="600">
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
                    <a @click="router.push(`${multiLanguageStore.currentLanguage}/test`)">>  start  &lt;</a>
                </div>
            </div>
        </Slide>
    </Carousel>
</template>

<style scoped>
.carousel__item {
    background: rgba(1, 3, 4, 0.4);
    border-right: black 1px solid;
    border-left: black 1px solid;
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
    letter-spacing: 0 !important;
    text-transform: none !important;
}

.full_details {
    overflow-y: scroll;
    height: 120px !important;
}
.carousel__item h5 {
    margin: 5px 0 !important;
    padding: 0 2px !important;
    cursor: default;
    letter-spacing: 0 !important;
}

.carousel__item a {
    text-transform: capitalize;
    font-size: 12px;
    margin-bottom: 10px;
    color: #50aa60;
}
.carousel__item a:hover {
    text-decoration: underline;
    cursor: pointer;
}
.carousel__slide--active .carousel__item h5:hover {
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
    margin: 0;
}
.carousel__item ul li {
    color: white;
    font-size: 6px;
    padding: 1px;
    cursor: default;
    display: flex;
    justify-content: center;
    align-items: center;
}
.carousel__item ul li:hover {
    color: #35c44e;
    text-decoration: underline;
}

.carousel__slide {
    padding: 20px;
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
    opacity: 0.8;
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
    transform: rotateY(0) scale(1.3);
}
</style>
