import { defineStore } from 'pinia';

export const useEventListener = defineStore('eventListener', {
    state: () => ({
        events: [],
    }),
    actions: {
        add(eventName, callback) {
            this.events[eventName] = callback
        },
        call(eventName, data) {
            if (this.events[eventName] !== undefined) {
                return this.events[eventName](data)
            }
        },
    },
})
