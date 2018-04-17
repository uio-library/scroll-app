<style lang="sass">
@import '~bootstrap/scss/bootstrap.scss';
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
                currentQuestionNum: 0,
            };
        },
        computed: {
            quizDataArray: function() {
                return Array.from(this.quizData.values());
            },
            currentQuestion: function () {
                return this.quizDataArray[this.currentQuestionNum]
            }
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
            nextQuestion() {
                this.currentQuestionNum = this.currentQuestionNum + 1;
            },
            previousQuestion() {
                this.currentQuestionNum = this.currentQuestionNum - 1;
            },
            restartQuiz() {
                this.currentQuestionNum = 0;
                
            }
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
        div(class="card-header" v-if="!error")
            <h4 class="card-title">Oppgaver </h4>
            span(v-if="currentQuestionNum < quizDataArray.length")
                    button(class="btn btn-primary" :disabled="currentQuestionNum==0" v-on:click="previousQuestion")
                        <span>Forrige</span>
                

            span(v-if="currentQuestionNum == quizDataArray.length")
                    button(class="btn btn-primary" :disabled="currentQuestionNum==0" v-on:click="restartQuiz")
                        <span>Se igjennom</span>

            span(v-if="currentQuestionNum < quizDataArray.length")
                    button(class="btn btn-primary" :disabled="currentQuestionNum==quizDataArray.length" v-on:click="nextQuestion")
                        span(v-if="currentQuestionNum < quizDataArray.length-1") Neste
                        span(v-if="currentQuestionNum == quizDataArray.length-1") Ferdig
                    
            span(v-if="currentQuestionNum == quizDataArray.length" v-on:mounted="checkAnswers")
                transition(name="bounce" mode="out-in")
                    div(v-if="waiting" key="waiting")
                        <i class="fa fa-cog fa-spin fa-fw"></i>
                    div(v-else-if="correct === true" key="true")
                        <i class="fa fa-check-circle" aria-hidden="true"></i> <span>Riktig</span>
                    div(v-else-if="correct === false" key="false")
                        <i class="fa fa-times-circle" aria-hidden="true"></i> <span>Galt</span>
                    div(v-else key="rest")

        div(class="card-body")
            span(v-if="total && currentQuestionNum == quizDataArray.length" style="padding-left:1em;") {{correct}} av {{total}} riktige
            span(v-if="currentQuestion" class="question" :class="{'is-incorrect': currentQuestion.answer.isCorrect === false, 'is-correct': currentQuestion.answer.isCorrect === true}")
                component(:is="currentQuestion.tag" :id="currentQuestion.id" :name="currentQuestion.name" :question="currentQuestion.question" :answer="currentQuestion.answer" @update:answer="updateAnswer")    
        
        
</template>
