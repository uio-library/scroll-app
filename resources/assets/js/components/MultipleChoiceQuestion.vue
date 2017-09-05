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
            <div class="form-check" v-for="alternative in alternatives" @click="changeSelectVal(alternative)">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="blankRadio" aria-label="...">
                    {{ alternative }}
                </label>
            </div>
            <span class="btn-primary">
                <button class="btn btn-primary" style="width:30vw" v-bind:class="{ 'btn-success' : isCorrect, 'btn-danger' : isCorrect === false}" id="submitButton" type="submit">{{buttonText}}</button>
            </span>
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
                alternatives : [],
                selected : "",
            }
        },
        methods: {
            onSubmit: function(event) {
                console.log("Answer to send is " + this.selected);
                this.$http.post('/checkAnswer', {answer : this.selected, id : this.id}).then(response =>  {
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
                this.alternatives = response.body.content.alternatives;
            });

            console.log('Component MultipleChoiceQuestion mounted: ', this.name);
        }
    }
</script>
