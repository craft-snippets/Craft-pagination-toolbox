module.exports = {

  head: [
    ['link', { rel: 'icon', href: 'http://craftsnippets.com/static/fav.png' }]
  ],

    title: 'Pagination toolbox Documentation',
    description: 'Documentation for the Pagination toolbox Craft CMS plugin',
    base: '/docs/pagination-toolbox/',
    themeConfig: {
        displayAllHeaders: true,
        sidebar: [
            ['/', 'Introduction'],
            ['Basic', 'Basic usage'],
            ['Dynamic', 'Dynamic pagination (PRO)'],
            ['Options', 'Pagination options'],
        ],
        nav: [
          { text: 'craftsnippets.com', link: 'http://craftsnippets.com/' }
        ],
      
    }
};
