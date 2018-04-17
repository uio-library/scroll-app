<style lang="sass">
@import '~bootstrap/scss/bootstrap.scss';
.quiz
    .question.is-correct .form-control
        border-color: map-get($theme-colors, "success")
    .question.is-incorrect .form-control
        border-color: map-get($theme-colors, "danger") 
</style>

<template>
    <div>
        <b-card class="mb-1" :title="question.question">
        <input type="text"
            class="form-control"
            :class="{'is-invalid': answer.isCorrect === false, 'is-valid': answer.isCorrect === true}"
            :value="answer.value"
            @input="setAnswer"
            placeholder="Svar..."
            aria-label="Svar">
        </b-card>
    </div>
</template>

<script>
    import ExerciseBase from './ExerciseBase.vue';
    export default {
        extends: ExerciseBase,
    }
</script>
