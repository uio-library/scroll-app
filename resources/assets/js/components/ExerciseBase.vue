<style lang="sass">
</style>
<template>
</template>
<script>
    export default {
        props: {
            id: Number,
            name: String,
            question: Object,
            answer: Object,
        },
        methods: {
            setAnswer(evt) {
                this.$emit('update:answer', {id: this.id, value: evt.target.value});
            }
        }
    }
</script>
