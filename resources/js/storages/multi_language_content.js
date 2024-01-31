import { defineStore } from 'pinia';
import mixins from '../mixins';
import router from "../router";

const refreshDelay = 30;

function getTimeStamp() {
    return Math.floor(Date.now() / 1000)
}

export const useMultiLanguageStore = defineStore({
    id: 'languageContent',
    state: () => ({
        currentLanguage: null,
        contentArray: {},
    }),
    actions: {
        readCurrentLanguage() {
            return this.currentLanguage
        },

        getCurrentLanguageFromApi() {
            this.currentLanguage = null
            mixins.methods.getAPI(
                'current_language',
                null,
                this.saveLanguage
            )
        },

        getCurrentLanguage() {
            if (this.currentLanguage === null) {
                this.getCurrentLanguageFromApi()
            }
        },

        switchLanguage() {
            this.currentLanguage = null
            mixins.methods.patchAPI(
                'switch_tag_language',
                null,
                null,
                this.saveLanguage
            );
        },

        changeLanguageTo(language) {
            this.currentLanguage = null
            mixins.methods.patchAPI(
                `change_language_to/${language}`,
                null,
                null,
                this.saveLanguage
            );
        },

        saveLanguage(response) {
            this.currentLanguage = response.data;
            router.isReady().then(() => {
                if (
                    this.currentLanguage
                    && router.currentRoute.value.params.lang !== this.currentLanguage
                ) {
                    router.push({
                        name: router.currentRoute.value.name,
                        params: {
                            lang: this.currentLanguage
                        }
                    })
                }
            })
        },

        getAllTagContents() {
            this.contentArray = []
            mixins.methods.getAPI(
                'all_tags',
                null,
                (response) => {
                    this.contentArray = response.data
                }
            );
        },
        async getContentByTag(tag, ifOldOny = true) {
            let params = {
                tag: tag,
                timeStamp: 0
            }

            if (this.contentArray[tag] !== undefined && ifOldOny) {
                if (this.contentArray[tag].timeStamp !== undefined) {
                    if ((this.contentArray[tag].timeStamp + refreshDelay - getTimeStamp()) > 0) {
                        return this.contentArray[tag]
                    }
                    this.contentArray[tag]['timeStamp'] = getTimeStamp()
                }
                params.timeStamp = this.contentArray[tag].timeStamp
            }
            await mixins.methods.getAPI(
                'tag_value_by_name',
                params,
                (response) => {
                    if (response.data === 1) {
                        return true;
                    }
                    this.contentArray[tag] = response.data
                    this.contentArray[tag].timeStamp = getTimeStamp()
                }
            )
            return this.contentArray[tag]
        },
        async replaceContent(content, ifOldOny = true) {
            let result = content;
            let insert = /^\{\{([\w]+)\}\}$/ui.exec(content)
            if (insert && insert[1] !== undefined) {
                await this.getContentByTag(insert[1], ifOldOny).then(insertData => {
                    result = insertData[this.currentLanguage]
                })
            }
            return result
        },
    }
});
