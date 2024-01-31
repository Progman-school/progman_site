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
                (response) => this.courses = response.data
            )
        },
    },

});
