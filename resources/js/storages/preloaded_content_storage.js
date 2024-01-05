import { defineStore } from 'pinia';
import mixins from "../mixins";

export const usePreloadedDataStorage = defineStore('preloadedContent', {
    state: () => ({
        courses: [],
    }),
    actions: {
        async getCoursesList() {
             await mixins.methods.getAPI(
                'get_courses_list',
                null,
                (data) => this.courses = data
            )
            console.log(this.courses)
            return this.courses
        },
    },
});
