import { defineStore } from 'pinia';
import mixins from "../mixins";

export const useCourseStorage = defineStore('preloadedContent', {
    state: () => ({
        courses: [],
    }),
    actions: {
        async getCoursesList() {
            await mixins.methods.getAPI(
                'get_all_courses',
                null,
                (response) => this.courses = response.data
            )
        },
    },

});
