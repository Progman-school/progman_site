<script setup>
import router from "../../../router";
import {ref} from "vue";
import {usePreloadedDataStorage} from "../../../storages/preloaded_content_storage";
import {useMultiLanguageStore} from "../../../storages/multi_language_content";

function setCourse(courseId) {
    if (courseId) {
        chosenCourse.value = preloadedData.courses[courseId]
        router.push(`test?course=${courseId}`)
        chosenCourse.value.hours = 0
        for (let technology of chosenCourse.value.technologies) {
            chosenCourse.value.hours += technology.pivot.hours
        }
    } else {
        chosenCourse.value = null
        router.push(`test`)
    }
}

const multiLanguageStore = useMultiLanguageStore()


const chosenCourse = ref(null)
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
        <div class="field_details" v-if="chosenCourse">
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
.chosen_course {
    color: #58cc02ff;
    font-weight: bold;
}
</style>
