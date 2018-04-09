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
    <b-card class="mb-1" :title="question.question">
        <div class="alternatives">
            <div class="form-check alternative" v-for="alternative in question.alternatives" @change="setAnswer">
                <label class="form-check-label" :class="{'selected': answer.value == alternative}">
                    <input class="form-check-input" type="radio" v-model="answer.value" :value="alternative" :name="question.question" aria-label="..."> <span v-html="alternative"></span>
                </label>
            </div>
        </div>
        </b-card>
    </div>
</template>

<script>
    import ExerciseBase from './ExerciseBase.vue';
    export default {
        extends: ExerciseBase,
    }
</script>
