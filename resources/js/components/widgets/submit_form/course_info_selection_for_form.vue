<script setup>
import router from "../../../router";
import {ref} from "vue";
import {usePreloadedDataStorage} from "../../../storages/preloaded_content_storage";
import {useMultiLanguageStore} from "../../../storages/multi_language_content";

const props =  defineProps({
    chosenCourse: null,
})

function setCourse(courseId) {
    if (courseId) {
        attrs.chosenCourse.value = preloadedData.courses[courseId]
        router.push(`new_test?course=${courseId}`)
        attrs.chosenCourse.value.hours = 0
        for (let technology of attrs.chosenCourse.value.technologies) {
            attrs.chosenCourse.value.hours += technology.pivot.hours
        }
    } else {
        attrs.chosenCourse.value = null
        router.push(`new_test`)
    }
}

const multiLanguageStore = useMultiLanguageStore()


const chooseCourse = (event) => {
    setCourse(event.target.value)
}

const preloadedData = usePreloadedDataStorage()
const urlCourseId = ref(null)
preloadedData.getCoursesList().then(() => {
    urlCourseId.value = router.currentRoute.value.query.course ?? ""
    if (router.currentRoute.value.query.course) {
        setCourse(router.currentRoute.value.query.course)
    }
})

</script>

<template>
    <div class="field">
        <label for="course">
            Your course:
        </label>
        <select id="course" name="course_id" :class="urlCourseId ? 'chosen_course' : ''" v-model="urlCourseId" @change="chooseCourse">
            <option v-if="!urlCourseId" value="" selected disabled>Select course</option>
            <option v-for="course in preloadedData.courses" :value=course.id :title=course.level :selected="urlCourseId === course.id">
                {{course.name}}
            </option>
        </select>
        <div class="curse_details" v-if="chosenCourse">
            <h4>Details:</h4>
            <div>
                <b>Start from:&nbsp;&nbsp;{{chosenCourse.level}}</b>
                <b class="longer_param">Type:&nbsp;&nbsp;{{chosenCourse.type}}</b>
                <b>Hours:&nbsp;&nbsp;~{{chosenCourse.hours}}</b>
            </div>
            <p>
                {{chosenCourse['description_' + multiLanguageStore.currentLanguage] || 'No description :('}}
            </p>
            <h6>Technologies:</h6>
            <ul>
                <li v-for="technology in chosenCourse.technologies" :title=technology.description>
                    <b>{{technology.name}}</b> (~{{technology.pivot.hours}}h)
                </li>
            </ul>
        </div>
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

.chosen_course {
    color: #58cc02ff;
    font-weight: bold;
}
</style>
