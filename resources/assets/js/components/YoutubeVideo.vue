<style lang="sass">
</style>

<template>
    <div ref="video-container" v-observe-visibility="visibilityChanged">
        <iframe
            :width="width"
            :height="height"
            :src="'https://www.youtube-nocookie.com/embed/' + id + '?rel=0&amp;showinfo=0'"
            frameborder="0"
            allowfullscreen
        ></iframe>
    </div>
</template>

<script>
    export default {
        props: {
            id: {
                type: String,
            },
            aspectRatio: {
                type: Number,
                default: 0.5625,  // 16:9   =>  9 / 16 = 0.5625
            }
        },
        data : function () {
            return {
                width : 560,
                timer: undefined,
            }
        },
        computed: {
            height: function () {
                return this.width * this.aspectRatio;
            },
        },
        methods: {
            visibilityChanged: function (isVisible, entry) {
                // When the element is shown, it will get a .clientWidth
                // that we can use to set the width of the iframe.
                this.resize();
            },
            resize () {
                // If the element is hidden, its .clientWidth will be 0
                if (this.$refs['video-container'].clientWidth) {
                    this.width = this.$refs['video-container'].clientWidth;
                }
            },
            resizeThrottler () {
                // Resize events can come very fast. Don't resize more than once per 100 ms
                window.clearTimeout(this.timer);
                this.timer = window.setTimeout(this.resize, 100);
            },
        },
        mounted: function () {
            window.addEventListener('resize', this.resizeThrottler);
        },
        beforeDestroy: function () {
            window.removeEventListener('resize', this.resizeThrottler);
        }
    }
</script>
