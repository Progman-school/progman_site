<script setup>
import {ref} from "vue";
import {usePreloadedDataStorage} from "../../../storages/preloaded_content_storage";
import {useMultiLanguageStore} from "../../../storages/multi_language_content";
import router from "../../../router";
import InlinePreloader from '../inline_preloader.vue'

const ON_SELECT_EMIT = 'onSelect'

const props = defineProps({
    openedCourseId: {
        type: Number,
        default: null
    },
    urlParamName: {
        type: String,
        default: 'course'
    }
})

const multiLanguageStore = useMultiLanguageStore()
const emit = defineEmits([ON_SELECT_EMIT])
const selectedCourse = ref(null)
const preloadedData = usePreloadedDataStorage()
const isWidgetReady = ref(false)

preloadedData.getCoursesList().then(() => {
    router.isReady().then(() => {
        const urlCourseId = router.currentRoute.value.query[props.urlParamName] ?? null
        const requestedCourse = preloadedData.courses[urlCourseId] ?? preloadedData[props.openedCourseId] ?? null
        if (requestedCourse) {
            setCourse(requestedCourse)
        }
    })
    isWidgetReady.value = true
})

function setCourse(course) {
    selectedCourse.value = null
    setTimeout(() => {
        const currentRout = router.currentRoute.value.path
        if (course && course?.id) {
            router.push(`${currentRout}?${props.urlParamName}=${course.id}`)
            selectedCourse.value = course
            selectedCourse.value.hours = 0
            for (let technology of selectedCourse.value.technologies) {
                selectedCourse.value.hours += technology.pivot.hours
            }
        } else {
            router.push(currentRout)
            selectedCourse.value = null
        }
        emit(ON_SELECT_EMIT, selectedCourse.value)
    }, 300)
}

const selectCourse = (event) => {
    setCourse(preloadedData.courses[event.target.value])
}

</script>

<template>
    <InlinePreloader :isVisible="!isWidgetReady" />
    <div class="field" v-show="isWidgetReady">
        <label for="course">
            Your course:
        </label>
        <select id="course" name="course_id" :class="selectedCourse ? 'selected_course' : ''" @change="selectCourse">
            <option v-if="!selectedCourse" value="" selected disabled>Select course</option>
            <option v-for="course in preloadedData.courses" :value=course.id :title=course.level :selected="selectedCourse?.id === course.id">
                {{course.name}}
            </option>
        </select>
        <transition name="slide-fade" mode="out-in">
            <div class="curse_details" v-show="selectedCourse">
                <h4>Details:</h4>
                <div>
                    <b>Start from:&nbsp;&nbsp;{{ selectedCourse.level }}</b>
                    <b class="longer_param">Type:&nbsp;&nbsp;{{ selectedCourse.type }}</b>
                    <b>Hours:&nbsp;&nbsp;~{{ selectedCourse.hours }}</b>
                </div>
                <p>
                    {{ selectedCourse['description_' + multiLanguageStore.currentLanguage] || 'No description :(' }}
                </p>
                <h6>Technologies:</h6>
                <ul>
                    <li v-for="technology in selectedCourse.technologies" :title=technology.description>
                        <b>{{technology.name}}</b> (~{{technology.pivot.hours}}h)
                    </li>
                </ul>
            </div>
        </transition>
    </div>
</template>

<style scoped>

.field_details {
    padding: 0 !important;
    border: none !important;
}

.curse_details > h4 {
    margin-top: 20px;
}

.curse_details > div {
    padding: 2px 10px;
    border: none;
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    background: #9ef68633;
}
.curse_details > div > b {
    padding: 0 5px;
}

@media (max-width: 760px) {
    .curse_details > div > b[class="longer_param"] {
        order: 3;
    }
}

.curse_details > p {
    margin: 20px 0;
    font-style: italic;
}

.curse_details ul {
    border: none;
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-gap: 3px 20px;
    margin: 0;
}

.curse_details ul li {
    border: none;
    padding-left: 2px;
}

.selected_course {
    color: #58cc02ff;
    font-weight: bold;
}
</style>
