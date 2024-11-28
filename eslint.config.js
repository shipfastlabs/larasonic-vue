import antfu from '@antfu/eslint-config'
import eslintPluginTailwindCSS from 'eslint-plugin-tailwindcss'

export default antfu({
  vue: true,
  typescript: false,
  ignores: ['storage/logs/**/*'],
  formatters: {
    /**
     * Format CSS, LESS, SCSS files, also the `<style>` blocks in Vue
     * By default uses Prettier
     */
    css: true,
    /**
     * Format HTML files
     * By default uses Prettier
     */
    html: true,
    /**
     * Format Markdown files
     * Supports Prettier and dprint
     * By default uses Prettier
     */
    markdown: 'prettier',
  },
}, [...eslintPluginTailwindCSS.configs['flat/recommended']], {
  rules: {
    'tailwindcss/no-custom-classname': 'off',
  },
})
