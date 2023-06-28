<template>
    <nav>
        <ul>
            <template v-for="link in links">
                <li v-if="link.name">
                    <router-link :to="link.path">{{link.name}}</router-link>
                </li>
            </template>
        </ul>
    </nav>
</template>

<script>
import router from "../router"

export default {
    name: "navigate",
    data() {
        return {
            links: router.getRoutes(),
        }
    },
}
</script>
