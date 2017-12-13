<style lang="sass">
.quiz
    .question
        .title
            font-weight: bold
        padding: .5em 1em;
        margin: .5em 0;

        // border: 1px solid white;
        // border-radius: 4px;
        // &.is-correct
        //     border-color: green;
        // &.is-incorrect
        //     border-color: red;

        .badge
            float: right;
</style>

<script>
    import MultipleChoiceQuestion from './MultipleChoiceQuestion.vue';
    import TextFieldQuestionRegex from './TextFieldQuestionRegex.vue';

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
                quizData: {},
                waiting: false,
            };
        },
        computed: {
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

                            let quizData = {};
                            for (const [key, value] of Object.entries(response.data)) {
                                quizData[value.id] = {
                                    tag: camelCaseToSpinalCase(value.type),
                                    name: value.name,
                                    id: value.id,
                                    type: value.type,
                                    question: value.content,
                                    answer: {
                                        value: value.state.answer,
                                        isCorrect: value.state.isCorrect,
                                    }

                                };
                            }
                            this.quizData = quizData;
                            console.log(quizData);

                            // Allows for chaining
                            resolve(response);
                        },
                        // Error response
                        this.handleErrorResponse
                    );
                });
            },
            updateAnswer(data) {
                console.log('update answer');
                this.quizData[data.id].answer = {
                    value: data.value,
                    isCorrect: undefined,
                };
            },
            checkAnswers() {
                let answers = {};
                for (const [key, q] of Object.entries(this.quizData)) {
                    answers[key] = q.answer.value;
                }

                this.error = undefined; // reset
                this.waiting = true;
                return new Promise((resolve, reject) => {
                    this.$http.post('/checkAnswers', {answers: answers}).then(
                        // Success response
                        response =>  {
                            this.waiting = false;

                            let quizData = {};
                            let correct = 0;
                            let total = 0;
                            for (const [key, value] of Object.entries(this.quizData)) {
                                quizData[key] = value;
                                total++;
                                if (response.data.hasOwnProperty(key)) {
                                    console.log(key, response.data[key]);
                                    quizData[key].answer.isCorrect = response.data[key];
                                    if (response.data[key]) {
                                        correct++;
                                    }
                                }
                            }
                            this.correct = correct;
                            this.total = total;
                            this.quizData = quizData;
                            console.log(quizData);
                            console.log('set quizdata');

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
div(class="quiz" :class="{'text-white bg-danger': error, 'answer-pending' : correct === undefined, 'answer-correct' : correct === true, 'answer-wrong' : correct === false, 'spinner' : waiting }")
    form(v-on:submit.prevent="checkAnswers")
        div(class="header")
            div(class="title")

        div(class="card-body" v-if="error") Error: {{error}}

        div(class="card-body" v-if="!error")
            div(v-for="(q, id) in quizData" class="question" :class="{'is-incorrect': q.answer.isCorrect === false, 'is-correct': q.answer.isCorrect === true}")
                span(v-if="q.answer.isCorrect === true" class="badge badge-success") Riktig
                span(v-if="q.answer.isCorrect === false" class="badge badge-danger") Galt
                component(:is="q.tag" :id="q.id" :name="q.name" :question="q.question" :answer="q.answer" @update:answer="updateAnswer")

        div(class="card-footer" v-if="!error")
            button(class="btn checkAnswerBtn" type="submit")
                transition(name="bounce" mode="out-in")
                    div(v-if="waiting" key="waiting")
                        <i class="fa fa-cog fa-spin fa-fw"></i>
                    div(v-else-if="correct === true" key="true")
                        <i class="fa fa-check-circle" aria-hidden="true"></i> <span>Riktig</span>
                    div(v-else-if="correct === false" key="false")
                        <i class="fa fa-times-circle" aria-hidden="true"></i> <span>Galt</span>
                    div(v-else key="rest")
                        <span> Sjekk svar</span>
            span(v-if="total" style="padding-left:1em;") {{correct}} av {{total}} riktige
</template>
