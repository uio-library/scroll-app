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

    &.video-collapsed .card-header button::before
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


        img, .iframe-container, .play-button, .play-button:before
            position: absolute

        .play-button, .play-button:before
            top: 50%
            left: 50%
            transform: translate3d( -50%, -50%, 0 )

        .iframe-container
            height: 100%
            width: 100%
            top: 0
            left: 0

</style>

<template>
    <b-card class="youtube" :class="{'video-collapsed': !showCollapse}">

        <b-card-header>
            <b-button block variant="outline-secondary" @click="showCollapse = !showCollapse">
                Video
            </b-button>
        </b-card-header>

        <b-card-body>
            <b-collapse :id="uid" v-model="showCollapse" @show="onShow" @hide="onHide">
                <div class="wrapper">
                    <div class="player" @click="initVideo">
                        <div class="play-button" v-show="!videoInitialized"></div>
                        <img v-if="showCollapse" :src="thumb" alt="YouTube thumbnail">
                        <div class="iframe-container" ref="iframe-container" style="height:100%"></div>
                    </div>
                </div>
            </b-collapse>
        </b-card-body>

    </b-card>
</template>

<script>
    import persistentState from './PersistentState';
    import YouTubePlayer from 'youtube-player';
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
                videoInitialized: false,
                width : 560,
                timer: undefined,
                showCollapse: false,
                player: null,
            }
        },
        created() {
            this.showCollapse = persistentState.get(this.uid, false);
        },
        computed: {
            thumb: function () {
                return `https://img.youtube.com/vi/${this.id}/hqdefault.jpg`;
            },
            height: function () {
                return this.width * this.aspectRatio;
            },
            uid : function () {
                return 'youtube.showCollapse:' + this.id;
            }
        },
        methods: {
            initVideo() {
                if (!this.videoInitialized) {
                    this.player = YouTubePlayer(this.$refs['iframe-container'], {
                        playerVars: {
                            modestbranding: 1, // Don't show YouTube logo in the controls bar.
                            rel: 0,            // Don't show related videos when video ends.
                        },
                    });

                    // 'loadVideoById' is queued until the player is ready to receive API calls.
                    this.player.loadVideoById(this.id);

                    // 'playVideo' is queue until the player is ready to received API calls and after 'loadVideoById' has been called.
                    this.player.playVideo();

                    this.videoInitialized = true;
                }
            },
            onShow: function() {
                persistentState.put(this.uid, true);
                this.initVideo();
            },
            onHide: function() {
                persistentState.put(this.uid, false);
                if (this.player) this.player.pauseVideo();
            },
        }
    }
</script>
