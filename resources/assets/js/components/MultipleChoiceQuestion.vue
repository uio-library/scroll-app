<style lang="sass">
</style>

<template lang="pug">
    extends ExerciseBase.pug
    block content
        <div v-html="question"></div>
        <div class="form-check" v-for="alternative in alternatives" @change="changeSelectVal(alternative)">
            <label class="form-check-label">
                <input class="form-check-input" type="radio" v-model="answer" v-bind:value="alternative" name="blankRadio" aria-label="..."> {{alternative}}
            </label>
        </div>
        <button class="btn btn-primary" type="submit">Sjekk svar</button>
</template>

<script>
    import ExerciseBase from './ExerciseBase.vue';
    export default {
        extends: ExerciseBase,
        data : function() {
            return {
                question : '',
                id : '',
                isCorrect : undefined,
                error : undefined,
                alternatives : [],
                answer : "",
            }
        },
        methods: {
            onSubmit: function(event) {
                this.$http.post('/checkAnswer', {answer : this.answer, id : this.id}).then(response =>  {
                    this.isCorrect = response.body.correct;
                });
            },
            changeSelectVal: function(val) {
                this.isCorrect = undefined;
            }
        },

        props: {
            name: {
                type: String,
            }
        },

        mounted() {
            this.$http.get('/getExercise', {params: {name : this.name}}).then(response =>  {
                this.question = response.body.content.question;
                this.id = response.body.id;
                this.alternatives = response.body.content.alternatives;
                this.answer = response.body.state.answer;
                this.isCorrect = response.body.state.isCorrect;
                console.log(this.answer, this.isCorrect);
            }, response => {
                if (response.body.error) {
                    this.error = response.body.error;
                } else {
                    this.error = 'Failed to fetch exercise.';
                }
            });
        }
    }
</script>
