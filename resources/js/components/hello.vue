<template>
    <header id="header">
        <div class="logo">
        </div>
        <div class="content">
            <div class="inner">
                <h1>ProgMan school</h1>
                <p>
                    <insert-content>main_page_text</insert-content>
                </p>
            </div>
        </div>
        <navigate></navigate>
    </header>
</template>

<script>
    import insertContent from '../components/helpers/insert-content.vue'
    import navigate from "./navigate.vue"
    export default {
        name: "hello",
        components: {
            insertContent,
            navigate
        },
    }
</script>

<style scoped>

</style>
