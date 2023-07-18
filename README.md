# Pagination toolbox plugin for Craft CMS 4.x

Pagination Toolbox is Craft CMS plugin that helps with the use of pagination in the templates.

## Features

* Generating configurable **ellipsis pagination** components in the templates. You can use ready to use widget or build template yourself, using data provided by the plugin.
* Default **CSS styles**, with the option to disable them.
* Page **number input widget**, allowing to visit specific pagination page by typing in its number.
* **Logarithmic pagination**, outputting pagination links on the logarithmic scale. Best suited for paginating large datasets.
* **Dynamic pagination**, allowing to change pagination subpages without refreshing whole page using **AJAX**. Properly updates the page URL and suppeorts forward and back buttons. Can fall back to regular pagination if Javascript is disabled. Available in the **PRO edition**.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require craftsnippets/craft-pagination-toolbox

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Pagination toolbox.

## Documentation

Click here for [Pagination toolbox documentation](http://craftsnippets.com/docs/image-toolbox)

## Author

Brought to you by Piotr Pogorzelski. Visit [Craft Snippets](http://craftsnippets.com) for more plugins and the Craft CMS resources.