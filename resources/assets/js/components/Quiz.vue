<style lang="sass">
@import '~bootstrap/scss/bootstrap.scss';
.quiz
    .question
        .title
            font-weight: bold
        padding: 0 0;
        margin: 0 0;

        // border: 1px solid white;
        // border-radius: 4px;
        // &.is-correct
        //     border-color: green;
        // &.is-incorrect
        //     border-color: red;

        .badge
            float: right;
    .card 
        margin-top: 1em;
        
    .card-body
        padding: .5em .5em;
</style>

<script>
    import MultipleChoiceQuestion from './MultipleChoiceQuestion.vue';
    import TextFieldQuestionRegex from './TextFieldQuestionRegex.vue';
    import SelfAssessedExerciseWithSolution from './SelfAssessedExerciseWithSolution.vue';

    function camelCaseToSpinalCase(txt) {
        return txt.split('').map(function(char) {
            if (char.match(/[A-Z]/)) {
                return '-' + char.toLowerCase();
            }
            return char;
        }).join('').replace(/^-/, '');
    }

    export default {
        data: function() {
            return {
                course_id: -1,
                correct : 0,
                total: 0,
                error : undefined,
                quizData: new Map(),
                waiting: false,
            };
        },
        computed: {
            quizDataArray: function() {
                return Array.from(this.quizData.values());
            },
        },
        props: {
            exercises: Array,
        },
        methods: {
            getExercises() {
                this.waiting = true;
                this.error = undefined; // reset
                return new Promise((resolve, reject) => {
                    this.$http.get('/getQuiz', {params: {
                        course: this.course_id,
                        exercises: this.exercises,
                    }}).then(
                        // Success response
                        response => {
                            this.waiting = false;

                            let quizData = new Map();
                            for (const name of this.exercises) {
                                let ex = response.data[name];
                                if (ex === undefined) {
                                    this.error = 'Exercise not found: ' + name;
                                    return;
                                }
                                quizData.set(ex.id, {
                                    tag: camelCaseToSpinalCase(ex.type),
                                    name: ex.name,
                                    id: ex.id,
                                    type: ex.type,
                                    question: ex.content,
                                    answer: {
                                        value: ex.state.answer,
                                        isCorrect: ex.state.isCorrect,
                                    }
                                });
                            }
                            this.quizData = quizData;

                            // Allows for chaining
                            resolve(response);
                        },
                        // Error response
                        this.handleErrorResponse
                    );
                });
            },
            updateAnswer(data) {
                let ex = this.quizData.get(data.id);
                ex.answer = {
                    value: data.value,
                    isCorrect: undefined,
                };
                this.quizData.set(data.id, ex);
            },
            checkAnswers() {
                let answers = {};
                for (const [key, q] of this.quizData) {
                    answers[key] = q.answer.value;
                }

                this.error = undefined; // reset
                this.waiting = true;
                return new Promise((resolve, reject) => {
                    this.$http.post('/checkAnswers', {answers: answers}).then(
                        // Success response
                        response =>  {
                            this.waiting = false;

                            let quizData = new Map();
                            let correct = 0;
                            let total = 0;
                            for (const [key, value] of this.quizData) {
                                total++;
                                if (response.data.hasOwnProperty(key)) {
                                    value.answer.isCorrect = response.data[key];
                                    if (response.data[key]) {
                                        correct++;
                                    }
                                }
                                quizData.set(key, value);
                            }
                            this.correct = correct;
                            this.total = total;
                            this.quizData = quizData;

                            // Allows for chaining
                            resolve(response);
                        },
                        // Error response
                        this.handleErrorResponse
                    );
                });
            },
            handleErrorResponse(response) {
                this.waiting = false;
                if (response.body.error) {
                    this.error = response.body.error;
                } else {
                    this.error = 'Server or network error 😭';
                }
            },
        },
        mounted() {
            this.course_id = document.getElementById('app').getAttribute('data-courseid');
            if (!this.course_id) {
                this.error = 'Failed to initialize: could not find course id';
                return;
            }
            this.getExercises();
        }
    }
</script>

<template lang="pug">
div(class="quiz card" :class="{'text-white bg-danger': error, 'answer-pending' : correct === undefined, 'answer-correct' : correct === true, 'answer-wrong' : correct === false, 'spinner' : waiting }")
    form(v-on:submit.prevent="checkAnswers")
        div(class="card-header")
            span(v-if="quizDataArray.length == 1") Oppgave
            span(v-else) Oppgaver

        div(class="card-body" v-if="error") Error: {{error}}
        div(class="card-body" v-if="!error")
            div(v-for="q in quizDataArray" class="question" :class="{'is-incorrect': q.answer.isCorrect === false, 'is-correct': q.answer.isCorrect === true}")
                component(:is="q.tag" :id="q.id" :name="q.name" :question="q.question" :answer="q.answer" @update:answer="updateAnswer")

        div(class="card-footer" v-if="!error")
            b-button(variant="primary" type="submit" class="mr-3" :disabled="waiting") Sjekk svar

            span(v-if="waiting")
                <i class="fa fa-cog fa-spin fa-fw"></i>

            span(v-if="total")
                span(v-if="correct === total" class="text-success")
                    <i class="fa fa-check-circle" aria-hidden="true"></i> {{correct}} av {{total}} riktige
                span(v-else class="text-danger")
                    <i class="fa fa-times-circle" aria-hidden="true"></i> {{correct}} av {{total}} riktige

</template>
