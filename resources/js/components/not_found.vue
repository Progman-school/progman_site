<script setup>
</script>

<template>
    <article>
        <h1 class="major">404</h1>
        <section>
            <h2>Ups!</h2>
            <label>The page not found.</label>
        </section>
        <div class="bottom_links">
            <router-link :to="'/'" >
                <strong>Go to the Home page</strong>
            </router-link>
        </div>
    </article>
</template>

<style scoped>
h2{
    margin-top: 0;
}
.bottom_links{
    margin: 20px 0;
    text-align: left;
}
section{
    text-align: center;
    margin: 80px auto;
}
label{
    font-weight: bold;
    color: #58cc02ff;
    margin-top: 20px;
}
</style>
