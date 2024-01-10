<template>
    <article id="contact">
        <h2 class="major">Contacts</h2>
        <ul class="my_social">
            <insert-content>links_contacts</insert-content>
<!--            <li title="denisprogman">-->
<!--                <a href="https://www.patreon.com/denisprogman" class="icon_links" target="_blank">-->
<!--                    <i class="fab fa-patreon"></i>-->
<!--                    <strong>Patreon</strong>-->
<!--                    <span>-->
<!--                        - &nbsp;&nbsp;Эксклюзивные материалы, готовые файлы с кодом опубликованным в различных соц сетях,-->
<!--                        записи всех прямых эфиров и самые интересные новости в айти.-->
<!--                        Для тех кто хочет поддержать проект ProgMan и получать вип доступ к закрытым от всех материалам!-->
<!--                        <br />-->
<!--                        Доступ дается за подписку всего от $1 в месяц (подписка: junior). Доступ к кодовым файлам $5 в месяц(подписка: middle).-->
<!--                    </span>-->
<!--                </a>-->
<!--            </li>-->
        </ul>
        <hr />
        <div style="margin: 12px 0; text-align: center">
            <router-link :to="'/start'" >
                <strong>
                    <insert-content>go_to_start_link</insert-content>
                </strong>
            </router-link>
        </div>
        <hr />
        <div style="text-align: center">
            <p>
                <insert-content>footer_contacts</insert-content>
            </p>
            <a href="tg://resolve?domain=progman_manager">
                <button class="primary">
                    <font-awesome-icon icon="fab fa-telegram"></font-awesome-icon>
                    Connect by Telegram
                </button>
            </a>
        </div>
        <closer />
    </article>
</template>

<script>
    import closer from '../helpers/closer.vue';
    import insertContent from '../helpers/insert-content.vue'

    export default {
        name: "contacts",
        components: {
            closer,
            insertContent,
        }
    }
</script>

<style scoped>

</style>
