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
    <div class="card padded-card">
        <div class=card-header>{{ question }}</div>

        <form class=card-body v-on:submit.prevent="onSubmit">
            <div class="input-group">
                <input type="text" class="form-control" v-model="answer" placeholder="Svar..." aria-label="Search for...">
                <span class="input-group-btn">
                    <button class="btn btn-primary" style="width:30vw" v-bind:class="{ 'btn-success' : isCorrect, 'btn-danger' : isCorrect === false}" id="submitButton" type="submit">{{buttonText}}</button>
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
            });

            console.log('Component mounted. Question: ', this.name);
        }
    }
</script>
