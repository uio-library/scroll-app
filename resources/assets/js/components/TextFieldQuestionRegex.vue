<style lang="sass">
@import '~bootstrap/scss/functions';
@import '~bootstrap/scss/variables';

.quiz
    .question.is-correct .form-control
        border-color: map-get($theme-colors, "success")
    .question.is-incorrect .form-control
        border-color: map-get($theme-colors, "danger") 
</style>

<template>
    <div>
        <h4 v-html="question.question"></h4>
        <input type="text"
            class="form-control"
            :class="{'is-invalid': answer.isCorrect === false, 'is-valid': answer.isCorrect === true}"
            :value="answer.value"
            @input="setAnswer"
            placeholder="Svar..."
            aria-label="Svar">
    </div>
</template>

<script>
    import ExerciseBase from './ExerciseBase.vue';
    export default {
        extends: ExerciseBase,
    }
</script>
