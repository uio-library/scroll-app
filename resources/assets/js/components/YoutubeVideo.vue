<style lang="sass">

.card.youtube
    border: 0
    margin: 1em 0

    .card-body, .card-header
        padding: 0

    .card-header button
        cursor: pointer
        text-align: left

        &::before
            display: inline-block
            content: "-"
            width: .7em

    &.collapse .card-header button::before
        content: "+"

    .wrapper
        max-width: 960px
        margin: 0 auto
        padding: 0

    .player
        background-color: #000
        margin-bottom: 0
        position: relative
        padding-top: 56.25%
        overflow: hidden
        cursor: pointer

        img
            cursor: pointer
            width: 100%
            top: -16.82%
            left: 0
            opacity: 0.7

        .play-button
            cursor: pointer
            width: 90px
            height: 60px
            background-color: #333
            box-shadow: 0 0 30px rgba( 0,0,0,0.6 )
            z-index: 1
            opacity: 0.8
            border-radius: 6px

            &:before
                content: ""
                border-style: solid
                border-width: 15px 0 15px 26.0px
                border-color: transparent transparent transparent #fff


        img, iframe, .play-button, .play-button:before
            position: absolute

        .play-button, .play-button:before
            top: 50%
            left: 50%
            transform: translate3d( -50%, -50%, 0 )

        iframe
            height: 100%
            width: 100%
            top: 0
            left: 0

</style>

<template>
    <b-card class="youtube" :class="{collapse: !showCollapse}">

        <b-card-header>
            <b-button block variant="outline-secondary" @click="showCollapse = !showCollapse">
                Video
            </b-button>
        </b-card-header>

        <b-card-body>
            <b-collapse :id="uid" v-model="showCollapse" @show="onShow" @hide="onHide">
                <div class="wrapper">
                    <div class="player" @click="() => playing=true">
                        <div class="play-button" v-show="!playing"></div>
                        <img :src="thumb" alt="YouTube thumbnail">
                        <iframe v-if="playing"
                            ref="iframe"
                            :width="width"
                            :height="height"
                            :src="'https://www.youtube-nocookie.com/embed/' + id + '?rel=0&amp;showinfo=0&amp;autoplay=1&amp;enablejsapi=1'"
                            frameborder="0"
                            allowfullscreen
                        ></iframe>
                    </div>
                </div>
            </b-collapse>
        </b-card-body>

    </b-card>
</template>

<script>
    import persistentState from './PersistentState';
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
                playing: false,
                width : 560,
                timer: undefined,
                showCollapse: false,
            }
        },
        created() {
            this.showCollapse = persistentState.get(this.uid, false);
        },
        computed: {
            thumb: function () {
                return `https://img.player.com/vi/${this.id}/sddefault.jpg`;
            },
            height: function () {
                return this.width * this.aspectRatio;
            },
            uid : function () {
                return 'youtube.showCollapse:' + this.id;
            }
        },
        methods: {
            pauseVideo : function() {
                this.$refs.iframe.contentWindow.postMessage(JSON.stringify({
                    event: 'command',
                    func: 'pauseVideo',
                    args: [],
                }), '*');
            },
            onShow: function() {
                persistentState.put(this.uid, true);
            },
            onHide: function() {
                persistentState.put(this.uid, false);
                if (this.playing) this.pauseVideo();
            },
        }
    }
</script>
