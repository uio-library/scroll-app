<template>
    <div ref="modules">

        <!-- Hidden scrollspy menu -->
        <b-nav v-b-scrollspy="20" class="d-none">
            <b-nav-item href="#top">Top</b-nav-item>
            <b-nav-item
                v-for="module in modules"
                :key="module.id"
                :href="'#' + module.id">{{ module.name }}</b-nav-item>
        </b-nav>

        <course-module
            v-for="module in modules"
            :key="module.id"
            class="module"
            :course-id="courseId"
            :module-id="module.id"
            :image="'resources/' + module.image"
            :image-aspect-ratio="module.imageaspectratio || 4"
            :name="module.name"
            :content="module.html"
        >
        </course-module>

    </div>
</template>

<script>
    export default {
        props: {
            courseId: Number,
            modules: {
                type: Array,
                default: [],
            }
        },
        methods: {
            onActivate (target) {
                if (target == '#top') target = '';
                window.history.replaceState(null, null, window.location.pathname + target);
            }
        },
        created () {
            this.$root.$on('bv::scrollspy::activate', this.onActivate)
        },
        mounted () {
            if (window.MathJax) {
                window.MathJax.Hub.Config({
                    tex2jax: {
                        inlineMath: [['$','$'], ['\\(','\\)']]
                    },
                    CommonHTML: {
                        scale: 93
                    },
                });
            }
        }
    }
</script>
