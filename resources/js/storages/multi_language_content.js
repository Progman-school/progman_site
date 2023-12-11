import { defineStore } from 'pinia';
import mixins from '../mixins';

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
        getCurrentLanguage() {
            this.currentLanguage = null;
            mixins.methods.getAPI(
                'current_language',
                null,
                this.saveLanguage
            )
        },
        changeLanguage() {
            this.currentLanguage = null;
            mixins.methods.getAPI(
                'switch_tag_language',
                null,
                this.saveLanguage
            );
        },
        saveLanguage(response) {
            this.currentLanguage = response.data;
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
                        return await this.contentArray[tag]
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
