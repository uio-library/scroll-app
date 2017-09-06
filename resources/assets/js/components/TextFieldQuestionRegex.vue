<style lang="sass">
</style>

<template lang="pug">
    extends ExerciseBase.pug
    block content
        <div v-html="question"></div>
        <div class="input-group">
            <input type="text" class="form-control" v-model="answer" v-on:input="isCorrect = undefined" placeholder="Svar..." aria-label="Search for...">
            <span class="input-group-btn">
                <button class="btn btn-primary" type="submit" v-html="buttonText"></button>
            </span>
        </div>
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
            }
        },
        methods: {
            onSubmit: function(event) {
                this.$http.post('/checkAnswer', {answer : this.answer, id : this.id}).then(response =>  {
                    this.isCorrect = response.body.correct;
                });
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
