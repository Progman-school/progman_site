export default {
    methods: {
        makeURL(baseURL, params){
            return params
                ? `${baseURL}?${(new URLSearchParams(params)).toString()}`
                : baseURL;
        },

        makeJSONFromForm(formData) {
            return JSON.stringify(Object.fromEntries(formData))
        },

        async getAPI(requestURL, urlParams, callback) {
            return await this.APIRequest(
                "GET",
                this.makeURL(requestURL, urlParams),
                null,
                callback
            )
        },

        async postAPI(requestURL, formData, callback) {
            return await this.APIRequest(
                "POST",
                requestURL,
                this.makeJSONFromForm(formData),
                callback
            )
        },

        async putAPI(requestURL, urlParams, formData, callback) {
            return await this.APIRequest(
                "PUT",
                this.makeURL(requestURL, urlParams),
                this.makeJSONFromForm(formData),
                callback
            )
        },

        async patchAPI(requestURL, urlParams, formData, callback) {
            return await this.APIRequest(
                "PATCH",
                this.makeURL(requestURL, urlParams),
                formData ? this.makeJSONFromForm(formData) : null,
                callback
            )
        },

        async deleteAPI(requestURL, id, callback) {
            return await this.APIRequest(
                "DELETE",
                `${requestURL}/${id}`,
                null,
                callback
            )
        },

        APIRequest: async function (method, URL, body, callback) {
            try {
                const requestObject = {
                    method: method,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }
                if (body && ["POST", "PUT", "PATCH"].includes(method)) {
                    requestObject.body = body
                    requestObject.headers["Content-Type"] = 'application/json'
                }
                let response = await fetch(
                    `api/${URL}`,
                    requestObject
                );
                let data = await response.json()

                if (data.status === "error") {
                    console.error(data.data)
                    return false
                }

                if (callback) {
                    await callback(data)
                }
            } catch (error) {
                console.error('Error:', error)
            }
        },
        setCookie(cName, cValue, expMin) {
            let date = new Date()
            date.setTime(date.getTime() + (expMin * 60 * 1000))
            const expires = "expires=" + date.toUTCString()
            document.cookie = cName + "=" + cValue + "; " + expires + "; path=/"
        },
        getCookie(cName) {
            const name = cName + "="
            const cDecoded = decodeURIComponent(document.cookie)
            const cArr = cDecoded.split('; ')
            let res
            cArr.forEach(val => {
                if (val.indexOf(name) === 0) res = val.substring(name.length)
            })
            return res
        }
    },
};
