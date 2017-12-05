
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

window.Vue = require('vue');
Vue.use(require('bootstrap-vue'));
Vue.use(require('vue-resource'));
Vue.use(require('vue-observe-visibility'));

Vue.http.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));
Vue.component('youtube-video', require('./components/YoutubeVideo.vue'));
Vue.component('text-field-question-regex', require('./components/TextFieldQuestionRegex.vue'));
Vue.component('multiple-choice-question', require('./components/MultipleChoiceQuestion.vue'));

Vue.component('quiz', require('./components/Quiz.vue'));

const app = new Vue({
    el: '#app'
});
