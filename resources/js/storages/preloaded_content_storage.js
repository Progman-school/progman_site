import { defineStore } from 'pinia';
import mixins from "../mixins";

export const usePreloadedDataStorage = defineStore('preloadedContent', {
    state: () => ({
        courses: [],
    }),
    actions: {
        async getCoursesList() {
            let courses
            await mixins.methods.getAPI(
                'get_courses_list',
                null,
                function (data) {console.log(courses); courses = data;}
            )
            console.log(courses)
            return courses
        },
    },
});
