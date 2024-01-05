import { defineStore } from 'pinia';
import mixins from "../mixins";

export const usePreloadedDataStorage = defineStore('preloadedContent', {
    state: () => ({
        courses: [],
    }),
    actions: {
        getCoursesList() {
            this.currentLanguage = null;
            mixins.methods.getAPI(
                'get_courses_list',
                null,
                (data) => this.courses = data
            )
        },
    },
});
