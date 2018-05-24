<template>
    <div>
        <button ref="h2button" class="header" v-b-toggle="'collapse-' + moduleId"
            :style="{ height: headerHeight + 'px', backgroundImage: 'url(' + image +')' }">
                <h2 style="user-select: none;">{{ name }}</h2>
        </button>

        <b-collapse :id="'collapse-' + moduleId" v-model="showCollapse" @show="onShow" @hide="onHide">
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
            this.showCollapse = persistentState.get(this.uid, false);
        },
        mounted() {
            this.headerHeight = this.$refs.h2button.offsetWidth / this.imageAspectRatio
            window.addEventListener('resize', () => {
                this.headerHeight = this.$refs.h2button.offsetWidth / this.imageAspectRatio
            });
        },
        methods: {
            onShow: function() {
                persistentState.put(this.uid, true);
            },
            onHide: function() {
                persistentState.put(this.uid, false);
            },
        }
    }
</script>
