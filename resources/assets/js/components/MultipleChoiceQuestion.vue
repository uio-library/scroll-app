<style lang="sass">
</style>

<template lang="pug">
    extends ExerciseBase.pug
    block content
        <div v-html="question"></div>
        <div class="form-check" v-for="alternative in alternatives" @click="changeSelectVal(alternative)">
            <label class="form-check-label">
                <input class="form-check-input" type="radio" name="blankRadio" aria-label="..."> {{alternative}}
            </label>
        </div>
        <button class="btn btn-primary" type="submit" v-html="buttonText"></button>
</template>

<script>
    import ExerciseBase from './ExerciseBase.vue';
    export default {
        extends: ExerciseBase,
        data : function() {
            return {
                answer : '',
                question : '',
                id : '',
                isCorrect : undefined,
                error : undefined,
                alternatives : [],
                selected : "",
            }
        },
        methods: {
            onSubmit: function(event) {
                this.$http.post('/checkAnswer', {answer : this.answer, id : this.id}).then(response =>  {
                    this.isCorrect = response.body.correct;
                });
            },
            changeSelectVal: function(val) {
                console.log(val);
                this.selected = val;
            }
        },
        props: {
            name: {
                type: String,
            }
        },

        computed: {
            buttonText: function() {
                // if (this.isCorrect === true) {
                //     return '<i class="fa fa-check-circle" aria-hidden="true"></i> <span style="display:inline-block;">Riktig</span>';
                // }
                // else if (this.isCorrect === false) {
                //     return '<i class="fa fa-times-circle" aria-hidden="true"></i> <span style="display:inline-block;">Galt</span>';
                // }
                return 'Sjekk svar';
            }
        },


        mounted() {
            this.$http.get('/getExercise', {params: {name : this.name}}).then(response =>  {
                this.question = response.body.content.question;
                this.id = response.body.id;
                this.alternatives = response.body.content.alternatives;
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
