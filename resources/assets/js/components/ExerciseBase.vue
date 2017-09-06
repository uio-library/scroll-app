<style lang="sass">
@import '~bootstrap/scss/bootstrap.scss';

.exercise
    @extend .card
    margin: 1em 0

    button
        overflow: hidden
        @media only screen and (min-width: 576px)
            width: 20vw

    .header
        display: flex
        background: rgba(0,0,0,.03)
        transition: background .2s ease-out
        border-bottom: 1px solid rgba(0,0,0,.125)
        overflow: hidden
        > div
            padding: .75rem 1.25rem
        .title
            flex: 1 1 auto
        .result
            text-align: right;
            flex: 0 0 auto
            span
                display: inline-block

            .bounce-enter-active
                animation: bounce-in .5s ease-out

            @keyframes bounce-in
                0%
                    transform: translateY(-50px)
                50%
                    transform: translateY(5px)
                100%
                    transform: translateY(0)

.answer-correct .header
    @extend .bg-success
    @extend .text-white

.answer-wrong .header
    background: red;
    @extend .bg-danger
    @extend .text-white

.exercise.answer-correct
    @extend .border-success

.exercise.answer-wrong
    @extend .border-danger

</style>

<template lang="pug">
    include ExerciseBase.pug
</template>

<script>
    export default {
        props: {
            number: {
                type: String,
            }
        },
        data : function() {
            return {
                id : '',
                isCorrect : undefined,
                error : undefined,
            }
        },
    }
</script>
