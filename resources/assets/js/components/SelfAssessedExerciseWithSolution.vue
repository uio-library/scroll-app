<style lang="sass">
@import '~bootstrap/scss/bootstrap.scss';

.quiz
    .alternatives
        margin-top: 0.5rem
        margin-bottom: 0.5rem

    .alternative
        margin: 0
        label
            display: block
            border: 1px solid transparent
            padding: 0.5rem 0.5rem 0.5rem 30px
            height: 100%
            border-radius: 3px

            &:hover
                border-color: #ececec

            &.selected
                border-color: map-get($theme-colors, "primary")

        // By default, scale any image contained in the alternative to fill its width
        img
            display: block
            width: 100%

    .question.is-correct .alternative label.selected
        border-color: map-get($theme-colors, "success")

    .question.is-incorrect .alternative label.selected
        border-color: map-get($theme-colors, "danger")

</style>

<template>
    <div>
        <h4>{{ question.question }}</h4>
        <p contenteditable="true">
            {{ question.text }}
        </p>

        <b-btn block @click="showCollapse=!showCollapse">Klikk her for å vurdere løsningen din</b-btn>
        <b-collapse v-model="showCollapse" id="collapse">
            <p class="card-text">
                {{ question.answertext }}
            </p>
            <b-card-body>Synes du dette gikk bra?</b-card-body>
            <div class="alternatives">
                <div class="form-check alternative" v-for="alternative in question.alternatives" @change="setAnswer">
                    <label class="form-check-label" :class="{'selected': answer.value == alternative}">
                        <input class="form-check-input" type="radio" v-model="answer.value" :value="alternative" :name="question.question" aria-label="..."> <span v-html="alternative"></span>
                    </label>
                </div>
            </div>
        </b-collapse>
    </div>
</template>

<script>
    import ExerciseBase from './ExerciseBase.vue';
    export default {
        extends: ExerciseBase,
        data()  {
            return {
                showCollapse : false
            }
        }
    }
</script>
