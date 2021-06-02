// import $ from 'jquery';
import Vue from 'vue';
// import ModelSelect from './components/model-select'
// var app = new Vue({
//     el: '#app',
//     components: {
//         'model-select': ModelSelect
//     }
// });

import ModelSelect from './components/model-select.vue';
const MyComponent = Vue.component('my-component', {
    data () {
        // Your data here...
    },
    methods: {
        // Your methods here...
    },
    template: '11111111111'
});
Vue.component('model-select', MyComponent);
