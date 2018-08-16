<template>
    <div>
        <a :href="'#' + moduleId" :id="moduleId" ref="h2button" class="header" v-on:click.prevent="onClick" v-b-toggle="'collapse-' + moduleId"
            :style="{ height: headerHeight + 'px', backgroundImage: 'url(' + image +')' }">
                <h2 style="user-select: none;">{{ name }}</h2>
        </a>

        <b-collapse :id="'collapse-' + moduleId" v-model="showCollapse" @show="onShow" @hide="onHide" accordion="my-acc">
            <render-template :template="content" v-once></render-template>
        </b-collapse>
    </div>
</template>

<script>
    import persistentState from './PersistentState';
    export default {
        props: {
            name: String,
            image: String,
            content: String,
            courseId: Number,
            moduleId: String,
            imageAspectRatio: Number,
        },
        data: function () {
            return {
                showCollapse: false,
                headerHeight: 220,
            }
        },
        computed: {
            uid: function() {
                return 'course:' + this.courseId + '.showCollapse:' + this.moduleId;
            }
        },
        created() {
            this.showCollapse = (window.location.hash == '#' + this.moduleId) || persistentState.get(this.uid, false);
        },
        mounted() {
            this.updateHeaderHeight();
            window.addEventListener('resize', this.updateHeaderHeight.bind(this));
        },
        methods: {
            updateHeaderHeight() {
                this.headerHeight = Math.floor(this.$refs.h2button.offsetWidth / this.imageAspectRatio) - 1;
            },
            onClick: function(ev) {
                //
            },
            onShow: function() {
                persistentState.put(this.uid, true);


                let thisModuleEl = this.$refs.h2button.parentElement;
                let modules = Array.from(document.querySelectorAll('.module'));
                let thisModuleIdx = modules.indexOf(thisModuleEl);

                let offset = 0;
                for (var i = 0; i < thisModuleIdx; i++) {
                    offset += modules[i].firstChild.getBoundingClientRect().height;
                }


                let cancelScroll = this.$scrollTo(
                    thisModuleEl.parentElement,
                    450, // The collapse animation is 350 ms. By using a slightly longer duration,
                         // the animation will make a small bump, but feel less shaky?
                    {
                        offset: offset,
                        easing: 'ease',
                        cancelable: false,
                    }
                );
            },
            onHide: function() {
                persistentState.put(this.uid, false);
            },
        }
    }
</script>
