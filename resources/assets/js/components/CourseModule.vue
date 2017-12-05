<template>
    <div>
        <div class="header" href="#" v-b-toggle="'collapse-' + id" :style="'background-image:url(' + image +')'">
            <div class="container-fluid">
                <h2 style="user-select: none;">{{ name }}</h2>
            </div>
        </div>

        <b-collapse :id="'collapse-' + id" v-model="showCollapse" @show="onShow" @hide="onHide">
            <b-container fluid style="margin-top : 10px">
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
            id: String,
        },
        data : function () {
            return {
                showCollapse: false,
            }
        },
        computed: {
            uid: function() {
                return 'course.showCollapse:' + this.id;
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
