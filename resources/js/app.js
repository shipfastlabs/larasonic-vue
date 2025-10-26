import { createInertiaApp } from '@inertiajs/vue3'
import { createHead } from '@unhead/vue/client'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createApp, h } from 'vue'
import { ZiggyVue } from 'ziggy-js'
import './bootstrap'
import '../css/app.css'

/**
 * This is used from unhead plugin to use seo meta tags
 * @see {@link https://unhead.unjs.io/setup/unhead/introduction} For createHead instance
 */
const head = createHead({})

createInertiaApp({
  resolve: name =>
    resolvePageComponent(
      `./pages/${name}.vue`,
      import.meta.glob('./pages/**/*.vue'),
    ),
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(head)
      .mount(el)
  },
  progress: {
    color: '#4B5563',
  },
})
