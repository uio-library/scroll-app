<style lang="sass">

</style>

<template>

    <component v-bind:is="currentView" :name="name"></component>

</template>

<script>
    import ExerciseBase from './ExerciseBase.vue';
    import MultipleChoiceQuestion from './MultipleChoiceQuestion.vue';
    import TextFieldQuestionRegex from './TextFieldQuestionRegex.vue';
    var execall = require('execall');

    function camelCaseToSpinalCase(txt) {
        return txt.split('').map(function(char) {
            if (char.match(/[A-Z]/)) {
                return '-' + char.toLowerCase();
            }
            return char;
        }).join('').replace(/^-/, '');
    }

    export default {
        extends: ExerciseBase,
        computed: {
        },
        props: {
        },
        data : function() {
            return {
                currentView: null,
            }
        },
        mounted() {
            console.log('Fetching exercise')
            this.getExercise().then((response) => {
                var tag = camelCaseToSpinalCase(response.body.type);

                this.currentView = tag;
                console.log(response.body.type)
            });
        }
    }
</script>
