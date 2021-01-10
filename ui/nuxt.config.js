// const webpack = require("webpack")
const authEndpointParams = {
  method: 'post',
  withCredentials: true, 
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
}

export default {
  // Global page headers (https://go.nuxtjs.dev/config-head)
  head: {
    htmlAttrs: {
      lang: 'en'
    },
    title: 'miniSend',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' }
    ],
    // link: [
    //   { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
    // ]
  },

  // Global CSS (https://go.nuxtjs.dev/config-css)
  css: [
  ],

  // Plugins to run before rendering page (https://go.nuxtjs.dev/config-plugins)
  plugins: [
  ],

  // Auto import components (https://go.nuxtjs.dev/config-components)
  components: true,

  // Modules for dev and build (recommended) (https://go.nuxtjs.dev/config-modules)
  buildModules: [
    // https://go.nuxtjs.dev/tailwindcss
    '@nuxtjs/tailwindcss',
    'nuxt-purgecss'
  ],

  tailwindcss: {
    configPath: './tailwind.config.js',
    cssPath: '~/assets/css/tailwind.css',
    exposeConfig: false
  },
  
  purgeCSS: {
    // mode: 'postcss',
    paths: [
      "assets/**/*.vue",
      "components/**/*.vue",
      "components/*.vue",
      "layouts/**/*.vue",
      "pages/**/*.vue",
      "plugins/**/*.js"
    ],
    whitelist: ["body", "html", "nuxt-progress", "code", "pre"],
    whitelistPatterns: [/(^|\.)fa-/, /-fa($|\.)/, /^editor/, /ProseMirror/],
    whitelistPatternsChildren: [/code/, /pre/, /token$/, /^editor/, /ProseMirror/, /pagination/]
  },

  // Modules (https://go.nuxtjs.dev/config-modules)
  modules: [
    // https://go.nuxtjs.dev/axios
    '@nuxtjs/axios',
    '@nuxtjs/auth-next',
    [
      "nuxt-fontawesome",
      {
        component: "fa",
        imports: [
          {
            set: "@fortawesome/free-solid-svg-icons",
            icons: [
              "faBold",
              "faItalic",
              "faStrikethrough",
              "faUnderline",
              "faCode",
              "faParagraph",
              "faListUl",
              "faListOl",
              "faQuoteRight",
            ]
          }
        ]
      }
    ]
  ],

  // Axios module configuration (https://go.nuxtjs.dev/config-axios)
  axios: {
    proxy: true, // Can be also an object with default options
    prefix: "/",
    credentials: true,
  },
  proxy: {
    "/api/": { target: "http://api" }
  },
  router: {
    middleware: ['auth']
  },
  auth: {
    watchLoggedIn: true,
    redirect: {
      login: '/signin',
      home: '/'
    },
    strategies: {
      tokenRequired: false,
      tokenType: false,
      cookie: {
        cookie: {
          name: 'XSRF-TOKEN',
        },
        user: {
          property: false
        },
        endpoints: {
          csrf: {
            url: '/api/csrf-cookie'
          },
          login: { 
            ...authEndpointParams,
            url: '/api/signin', 
          },
          logout: {
            ...authEndpointParams,
            url: '/api/logout', 
          },
          user: { 
            ...authEndpointParams,
            url: '/api/user', 
            method: 'get',
          }
        }
      },
    }
  },

  build: {
    postcss: {
      plugins: {
        // Disable a plugin by passing false as value
        'postcss-nested': {},
      },
      preset: {
        // Change the postcss-preset-env settings
        autoprefixer: {
          grid: false
        }
      }
    }
  }
}
