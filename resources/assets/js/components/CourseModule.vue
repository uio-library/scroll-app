<template>
    <div>
        <div class="header" href="#" v-b-toggle="'collapse-' + moduleId" :style="'background-image:url(' + image +')'">
            <div class="container-fluid">
                <h2 style="user-select: none;">{{ name }}</h2>
            </div>
        </div>

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
        },
        data : function () {
            return {
                showCollapse: false,
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
