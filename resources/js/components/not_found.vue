<script setup>
</script>

<template>
    <article>
        <h2 class="major">404</h2>
        <section>
            <label>The page not found.</label>
        </section>
        <div class="bottom_links">
            <router-view to="/">
                <strong>Go to the Home page</strong>
            </router-view>
        </div>
    </article>
</template>

<style scoped>
.bottom_links{
    margin-top: 20px;
    text-align: right;
}
section{
    text-align: center;
    margin-top: 20px;
}
label{
    color: #58cc02ff;
    margin-top: 20px;
}
</style>
