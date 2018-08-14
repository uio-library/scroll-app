<template>
    <div>
        <a :href="'#' + moduleId" :id="moduleId" ref="h2button" class="header" v-on:click.prevent="onClick" v-b-toggle="'collapse-' + moduleId"
            :style="{ height: headerHeight + 'px', backgroundImage: 'url(' + image +')' }">
                <h2 style="user-select: none;">{{ name }}</h2>
        </a>

        <b-collapse :id="'collapse-' + moduleId" v-model="showCollapse" @show="onShow" @hide="onHide" accordion="my-acc">
            <b-container fluid style="padding-top : 1em; padding-bottom: 2em;">
                <slot></slot>
            </b-container>
        </b-collapse>
    </div>
</template>

<script>
    import persistentState from './PersistentState';
    export default {
        props: {
            name: String,
            image: String,
            courseId: String,
            moduleIndex: Number,
            moduleId: String,
            imageAspectRatio: Number,
        },
        data : function () {
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
                history.pushState(null, null, '#' + this.moduleId);

                let el = this.$refs.h2button.parentElement.previousSibling;
                while (el.nodeType !== Node.ELEMENT_NODE) {
                    el = el.previousSibling;
                }
                if (!el.classList.contains('module')) {
                    // First module
                    return;
                }
                let cancelScroll = this.$scrollTo(
                    el,
                    300, // The collapse animation is 350 ms. Using a slightly shorter or longer duration seems to look best.
                    {
                        offset: el.getBoundingClientRect().height,
                        easing: 'ease',
                    }
                );
            },
            onHide: function() {
                persistentState.put(this.uid, false);
            },
        }
    }
</script>
