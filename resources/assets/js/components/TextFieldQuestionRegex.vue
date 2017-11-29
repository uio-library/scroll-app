<style lang="sass">
</style>

<template>
    <div>
        <label v-html="question.question" class="title"></label>
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
