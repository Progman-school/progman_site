<script setup>
import {useMultiLanguageStore} from "../../../storages/multi_language_content";

const daySets = {
    'en': [
        {
            name: 'Sunday',
            short: 'Sun',
            number: 7,
            code: 'sun'
        },
        {
            name: 'Monday',
            short: 'Mon',
            number: 1,
            code: 'mon'
        },
        {
            name: 'Tuesday',
            short: 'Tue',
            number: 2,
            code: 'tue'
        },
        {
            name: 'Wednesday',
            short: 'Wed',
            number: 3,
            code: 'wed'
        },
        {
            name: 'Thursday',
            short: 'Thu',
            number: 4,
            code: 'thu'
        },
        {
            name: 'Friday',
            short: 'Fri',
            number: 5,
            code: 'fri'
        },
        {
            name: 'Saturday',
            short: 'Sat',
            number: 6,
            code: 'sat'
        }
    ],
    'ru': [
        {
            name: 'Понедельник',
            short: 'Пн',
            number: 1,
            code: 'mon'
        },
        {
            name: 'Вторник',
            short: 'Вт',
            number: 2,
            code: 'tue'
        },
        {
            name: 'Среда',
            short: 'Ср',
            number: 3,
            code: 'wed'
        },
        {
            name: 'Четверг',
            short: 'Чт',
            number: 4,
            code: 'thu'
        },
        {
            name: 'Пятница',
            short: 'Пт',
            number: 5,
            code: 'fri'
        },
        {
            name: 'Суббота',
            short: 'Сб',
            number: 6,
            code: 'sat'
        },
        {
            name: 'Воскресенье',
            short: 'Вс',
            number: 7,
            code: 'sun'
        }
    ]
}

const multiLanguage = useMultiLanguageStore();
</script>

<template>
    <div class="field week_days">
        <label>
            <slot></slot>:
        </label>
        <div>
            <div v-for="day of daySets[multiLanguage.currentLanguage]" :title="day.name">
                <input :id="`weekday_${day.code}`" type="checkbox" :name="`weekday__${day.name}`" :value="day.code">
                <label :for="`weekday_${day.code}`">{{day.short}}</label>
            </div>
        </div>
    </div>
</template>

<style scoped>
.week_days > div {
    display: flex;
    flex-wrap: nowrap;
    flex-direction: row;
    justify-content: space-around;
}
.week_days > div > div {
    display: inline-block;
    margin: 0 5px;
}
.week_days input[type="checkbox"] + label {
    margin: 0 0 2.5rem 0;
    padding-left: 0;
    padding-right: 0;
}

.week_days input[type="checkbox"] + label:before {
    top: 2rem;
    left: 0.05rem;
}
</style>
