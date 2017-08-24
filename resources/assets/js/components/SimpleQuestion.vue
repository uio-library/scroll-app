<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ question }}</div>

                    <form v-on:submit.prevent="onSubmit">

                        <input type="text" v-model="answer">

                        <button type="submit" >Var det riktig?</button>

                    </form>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data : function() {
            return {
                answer : 'test'
            }
        },
        methods: {
            onSubmit: function(event) {
                this.$http.post('/sjekkSvar').then(response =>  {
                    console.log(response);
                });
            }
        },
        props: {
            question: {
                type: String,
            },
        },
        mounted() {
            console.log('Component mounted. Question: ', this.question, ' - answers: ', this.answer);
        }
    }
</script>
