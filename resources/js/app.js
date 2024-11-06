import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import VueCountDown from "@chenfengyuan/vue-countdown"
import PrimeVue from 'primevue/config'
import Aura from '@primevue/themes/aura';

import Button from "primevue/button"
import Layout from "./Layouts/Auth.vue"

import 'primeicons/primeicons.css'
import { Tooltip } from 'primevue';

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })

    let page = pages[`./Pages/${name}.vue`]
    page.default.layout = name.startsWith('Student/') ? Layout : undefined
    return page
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(PrimeVue, {
        theme: {
          preset: Aura,
        }
      })
      .use(plugin)
      .component(VueCountDown.name, VueCountDown)
      .directive('tooltip', Tooltip)

      .mount(el)
  },
})