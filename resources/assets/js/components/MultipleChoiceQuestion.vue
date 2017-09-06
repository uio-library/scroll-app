<style lang="sass">
@import '~bootstrap/scss/bootstrap.scss';

.exercise
    .alternatives
        margin-top: 0.5rem
        margin-bottom: 0.5rem

    .alternative
        margin: 0
        label
            display: block
            border: 1px solid transparent
            padding: 0.5rem 0.5rem 0.5rem 30px
            height: 100%
            border-radius: 3px

            &:hover
                border-color: #ececec

            &.selected
                border-color: map-get($theme-colors, "primary")

        // By default, scale any image contained in the alternative to fill its width
        img
            display: block
            width: 100%

</style>

<template lang="pug">
    extends ExerciseBase.pug
    block content
        <div v-html="content.question"></div>

        <div class="alternatives row">
            <div class="form-check alternative" :class="colStyle" v-for="alternative in content.alternatives" @change="resetIsCorrect()">
                <label class="form-check-label" :class="{'selected': answer == alternative}">
                    <input class="form-check-input" type="radio" v-model="answer" :value="alternative" name="blankRadio" aria-label="..."> <span v-html="alternative"></span>
                </label>
            </div>
        </div>

        <button class="btn btn-primary" type="submit">Sjekk svar</button>
</template>

<script>
    import ExerciseBase from './ExerciseBase.vue';
    export default {
        extends: ExerciseBase,
        computed: {
            colStyle: function() {
                return 'col-lg-' + (12 / this.columns);
            }
        },
        props: {
            // Number of columns (must be a fraction of 12)
            columns: {
                default: 1,
                type: Number,
            },
        },
        mounted() {
            this.getExercise()
        }
    }
</script>
