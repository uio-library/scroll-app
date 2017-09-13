<style lang="sass">
</style>

<template lang="pug">
    extends ExerciseBase.pug
    block content

        <div v-html="content.question"></div>
            <input type="text" class="form-control" v-model="answer" @input="resetIsCorrect()" placeholder="Svar..." aria-label="Search for...">
</template>

<script>
    import ExerciseBase from './ExerciseBase.vue';
    export default {
        extends: ExerciseBase,
        mounted() {
            this.getExercise()
        }
    }
</script>
