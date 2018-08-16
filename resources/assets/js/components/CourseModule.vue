<template>
    <div>
        <a :href="'#' + moduleId" :id="moduleId" ref="h2button" class="header" v-on:click.prevent v-b-toggle="'collapse-' + moduleId"
            :style="{ height: headerHeight + 'px', backgroundImage: 'url(' + image +')' }">
                <h2 style="user-select: none;">{{ name }}</h2>
        </a>

        <b-collapse :id="'collapse-' + moduleId" v-model="showCollapse" @show="onShow" @hide="onHide">
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
            onShow: function() {
                persistentState.put(this.uid, true);
            },
            onHide: function() {
                persistentState.put(this.uid, false);
            },
        }
    }
</script>
