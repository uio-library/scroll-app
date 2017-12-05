<style>
.wrapper {
    max-width: 960px;
    margin: 0 auto;
    padding: 0;
}

.youtube {
    background-color: #000;
    margin-bottom: 30px;
    position: relative;
    padding-top: 56.25%;
    overflow: hidden;
    cursor: pointer;
}
.youtube img {
    width: 100%;
    top: -16.82%;
    left: 0;
    opacity: 0.7;
}
.youtube .play-button {
    width: 90px;
    height: 60px;
    background-color: #333;
    box-shadow: 0 0 30px rgba( 0,0,0,0.6 );
    z-index: 1;
    opacity: 0.8;
    border-radius: 6px;
}
.youtube .play-button:before {
    content: "";
    border-style: solid;
    border-width: 15px 0 15px 26.0px;
    border-color: transparent transparent transparent #fff;
}
.youtube img,
.youtube .play-button {
    cursor: pointer;
}
.youtube img,
.youtube iframe,
.youtube .play-button,
.youtube .play-button:before {
    position: absolute;
}
.youtube .play-button,
.youtube .play-button:before {
    top: 50%;
    left: 50%;
    transform: translate3d( -50%, -50%, 0 );
}
.youtube iframe {
    height: 100%;
    width: 100%;
    top: 0;
    left: 0;
}

.no-border {
    border : none;
    
}

.no-border .card-body {
    padding : 0;
}

</style>

<template>
    <b-card class="no-border">
    <div class=card-header @click="showCollapse = !showCollapse; !showCollapse && playing ? pauseVideo() : null" :class="showCollapse ? 'collapsed' : null">
          <h5 class="mb-0"><div style="display:inline-block;width:1em">{{showCollapse ? "-":"+"}}</div> Video</h5>
    </div>

    <b-collapse :id="uid" v-model="showCollapse">
      <div class="card-body">
        <div>
            <div class="wrapper">
                <div class="youtube" @click="() => playing=true">
                    <div class="play-button" v-show="!playing"></div>
                    <img :src="thumb" alt="YouTube thumbnail">
                    <iframe ref="youtubevideo" v-if="playing"
                        :width="width"
                        :height="height"
                        :src="'https://www.youtube-nocookie.com/embed/' + id + '?rel=0&amp;showinfo=0&amp;autoplay=1&amp;enablejsapi=1'"
                        frameborder="0"
                        allowfullscreen
                    ></iframe>
                </div>
            </div>
        </div>
    </div>
    </b-collapse>
    </b-card>
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
                playing: false,
                width : 560,
                timer: undefined,
                showCollapse: false,
            }
        },
        computed: {
            thumb: function () {
                return `https://img.youtube.com/vi/${this.id}/sddefault.jpg`;
            },
            height: function () {
                return this.width * this.aspectRatio;
            },
            uid : function () {
                return "video"+this._uid.toString();
            }
        },
        methods: {
            pauseVideo : function() {
                this.$refs.youtubevideo.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":[]}', '*');
            } 
        }
    }
</script>
