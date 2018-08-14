
require('./bootstrap');

window.Vue = require('vue');

Vue.use(require('bootstrap-vue'));
Vue.use(require('vue-observe-visibility'));

var VueScrollTo = require('vue-scrollto');

// You can also pass in the default options
Vue.use(VueScrollTo);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));
Vue.component('youtube-video', require('./components/YoutubeVideo.vue'));
Vue.component('text-field-question-regex', require('./components/TextFieldQuestionRegex.vue'));
Vue.component('multiple-choice-question', require('./components/MultipleChoiceQuestion.vue'));
Vue.component('course-module', require('./components/CourseModule.vue'));
Vue.component('course-modules', require('./components/CourseModules.vue'));
Vue.component('self-assessed-exercise-with-solution', require('./components/SelfAssessedExerciseWithSolution.vue'));
Vue.component('render-template', require('./components/RenderTemplate.vue'));

Vue.component('quiz', require('./components/Quiz.vue'));
Vue.component('quiz-with-navigation', require('./components/QuizWithNavigation.vue'));

const app = new Vue({
    el: '#app'
});
