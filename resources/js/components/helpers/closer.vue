<template>
    <div class="close" @click="pageClose">Close</div>
</template>

<script>
    import router from '../../router.js'

    export default {
        name: "closer",
        methods: {
            pageClose: function () {
                router.push('/')
            }
        },
    }
</script>

<style scoped>

</style>
