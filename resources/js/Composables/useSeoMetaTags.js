import { useSeoMeta } from '@unhead/vue'

// Default SEO meta tags
const defaultSeoMeta = {
  title: 'Home',
  titleTemplate: '%s | Larasonic Modern Laravel SaaS Starter Kit',
  description:
        'Larasonic is a modern Laravel boilerplate for the VILT stack (Vue, Inertia, Laravel, TailwindCSS). Clone and start building scalable, maintainable, and production-ready applications quickly.',
  keywords:
        'Larasonic, Laravel boilerplate, Laravel VILT, Vue, Inertia, TailwindCSS, Laravel Octane, Docker, FilamentPHP, OpenAI integration, Laravel Cashier, Laravel Sanctum',
  robots: 'index, follow',
  themeColor: '#000000',

  // Open Graph
  ogTitle: '%s | Larasonic Modern Laravel SaaS Starter Kit',
  ogDescription:
        'Larasonic is a modern Laravel SaaS starter kit for the VILT stack. Clone the repo, start building scalable and maintainable applications quickly.',
  ogUrl: 'https://larasonic.com',
  ogType: 'website',
  ogImage: 'https://larasonic.com/images/og.webp',
  ogSiteName: 'Larasonic',
  ogLocale: 'en_US',

  // Twitter
  twitterTitle: '%s | Larasonic Modern Laravel SaaS Starter Kit',
  twitterDescription:
        'Larasonic is a modern Laravel SaaS starter kit for the VILT stack. Clone the repo, start building scalable and maintainable applications quickly.',
  twitterCard: 'summary_large_image',
  twitterImage: 'https://larasonic.com/images/og.webp',
  twitterSite: '@pushpak1300',
}

/**
 * Composable for managing SEO meta tags
 * @param {object|null} seoMeta - Custom SEO meta tags to apply
 * @param {object} options - Configuration options
 * @param {boolean} options.merge - When true, merges custom meta tags with defaults.
 *                                 When false, only uses custom meta tags.
 *                                 Useful for pages that need completely custom SEO
 *                                 without inheriting defaults.
 * @returns {void}
 */
export function useSeoMetaTags(seoMeta, options = { merge: true }) {
  if (!seoMeta)
    return useSeoMeta(defaultSeoMeta)

  return useSeoMeta(
    options.merge ? { ...defaultSeoMeta, ...seoMeta } : seoMeta,
  )
}
