import antfu from '@antfu/eslint-config'

export default antfu({
  vue: true,
  typescript: false,
  formatters: {
    css: true,
    html: true,
    markdown: 'prettier',
  },
  ignores: ['storage/**/*', '**/*.{yaml,yml,php}', 'resources/js/components/ui/**/*', 'public/**/*'],
})
