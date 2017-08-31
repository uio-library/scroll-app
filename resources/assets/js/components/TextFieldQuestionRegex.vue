<style>
    .correct button {
        background-color: green
    }
    .incorrect button {
        background-color: red
    }
    .padded-card {
        margin-bottom: 1em
    }
</style>

<template>
    <div class="card padded-card" :class="{'text-white bg-danger': error }">
        <div class=card-header v-if="!error">{{ question }}</div>

        <div class="card-body" v-if="error" >
            Error: {{ error }}
        </div>

        <form class="card-body" v-if="!error" v-on:submit.prevent="onSubmit">
            <div class="input-group">
                <input type="text" class="form-control" v-model="answer" placeholder="Svar..." aria-label="Search for...">
                <span class="input-group-btn">
                    <button class="btn btn-primary" style="width:30vw" :class="{ 'btn-success' : isCorrect, 'btn-danger' : isCorrect === false}" id="submitButton" type="submit">{{buttonText}}</button>
                </span>
            </div>
        </form>
    </div>
</div>
</template>

<script>
    export default {
        data : function() {
            return {
                answer : 'test',
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
            },

        },

        computed: {
            buttonText: function() {
                if (this.isCorrect === true) {
                    return "Riktig";
                }
                else if (this.isCorrect === false) {
                    return "Galt";
                }
                else {
                    return "Sjekk svar";
                }
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
