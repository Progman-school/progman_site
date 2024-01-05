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
                'current_language',
                null,
                (data) => this.courses = data
            )
        },
    },
});
