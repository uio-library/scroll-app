<style lang="sass">
</style>

<template lang="pug">
    extends ExerciseBase.pug
    block content

        <div v-html="question"></div>

        <div class="input-group">
            <input type="text" class="form-control" v-model="answer" @input="resetIsCorrect()" placeholder="Svar..." aria-label="Search for...">
            <span class="input-group-btn">
                <button class="btn btn-primary" type="submit">Sjekk svar</button>
            </span>
        </div>
</template>

<script>
    import ExerciseBase from './ExerciseBase.vue';
    export default {
        extends: ExerciseBase,
        data : function() {
            return {
                question : '',
            }
        },
        mounted() {
            this.getExercise().then(response => {
                this.question = response.body.content.question;
            })
        }
    }
</script>
