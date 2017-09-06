<style lang="sass">
</style>

<template lang="pug">
    extends ExerciseBase.pug
    block content
        <div v-html="question"></div>

        <div class="alternatives">
            <div class="form-check alternative" v-for="alternative in alternatives" @change="resetIsCorrect()">
                <label class="form-check-label" :class="{'selected': answer == alternative}">
                    <input class="form-check-input" type="radio" v-model="answer" :value="alternative" name="blankRadio" aria-label="..."> <span v-html="alternative"></span>
                </label>
            </div>
        </div>

        <button class="btn btn-primary" type="submit">Sjekk svar</button>
</template>

<script>
    import ExerciseBase from './ExerciseBase.vue';
    export default {
        extends: ExerciseBase,
        data: function() {
            return {
                question : '',
                alternatives : [],
            }
        },
        props: {
        },
        mounted() {
            this.getExercise().then(response => {
                this.question = response.body.content.question;
                this.alternatives = response.body.content.alternatives;
            })
        }
    }
</script>
