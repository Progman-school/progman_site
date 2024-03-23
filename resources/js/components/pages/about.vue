<script setup>
import Closer from '../widgets/closer.vue';
import InsertContent from '../widgets/insert_content.vue'
</script>

<template>
    <article id="conditions">
        <h2 class="major">About us</h2>
        <section>
            <h4>Founder's message:</h4>
            <p>
                <img src="/images/Denis_Progman (denis ProgMan) (prog.man) (progman community) (IT menthor) small_size.jpg" />
                <strong>
                    <span class="important_text">Hey progmans!</span> I'm Denis.
                    <br />
                    Welcome to our IT community!
                </strong>
                <blockquote>
                    <InsertContent>conditions_teacher_message</InsertContent>
                </blockquote>
            </p>
            <div style="margin: 20px auto">
                <div class="goto_test_button">
                    <a href="consulting">
                        <button class="primary">Personal consultation</button>
                    </a>
                </div>
            </div>
        </section>
        <hr />
        <section>
            <h3>The benefits of IT:</h3>
            <div class="icons_line">
                <font-awesome-icon icon="fa-solid fa-wand-magic-sparkles"></font-awesome-icon>
                <font-awesome-icon icon="fa-solid fa-globe"></font-awesome-icon>
                <font-awesome-icon icon="fa-solid fa-file-shield"></font-awesome-icon>
                <font-awesome-icon icon="fa-solid fa-hand-holding-dollar"></font-awesome-icon>
                <font-awesome-icon icon="fa-solid fa-sack-dollar"></font-awesome-icon>
                <font-awesome-icon icon="fa-solid fa-thumbs-up"></font-awesome-icon>
                <font-awesome-icon icon="fa-solid fa-people-roof"></font-awesome-icon>
            </div>
            <InsertContent>course_description_conditions</InsertContent>
            <div class="goto_test_button">
                <a href="study_plan">
                    <button class="primary">Make study plan</button>
                </a>
            </div>
        </section>
        <hr/>
        <section>
            <InsertContent>mentor_experience_conditions</InsertContent>
        </section>
        <div style="margin-bottom: 20px">
            <router-link :to="'study_plan'" >
                <strong>
                    <InsertContent>go_to_start_link</InsertContent>
                </strong>
            </router-link>
            <br />
            <br />
        </div>
        <Closer />
    </article>
</template>

<style scoped>
.goto_test_button {
    text-align: center;
    margin: 20px auto;
}
p strong {
    line-height: 36px;
}
p img {
    border-radius: 50%;
    border: #58cc02 solid 2px;
    width: 80px;
    height: 80px;
    margin: 0 20px 20px 20px;
    float: left;
}
.icons_line {
    display: flex;
    justify-content: space-around;
    margin: 50px auto;
}
.icons_line svg {
    font-size: 2.5em;
}
.icons_line svg:hover {
    color: #58cc02;
}

</style>
