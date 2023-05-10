/**
 * Nextcloud - Tutorial5
 *
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Julien Veyssier <julien-nc@posteo.net>
 * @copyright Julien Veyssier 2023
 */

import App from './views/App.vue'
import Vue from 'vue'
Vue.mixin({ methods: { t, n } })

const VueApp = Vue.extend(App)
new VueApp().$mount('#content')
