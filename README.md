# Automatic Content Spinner

[![Build Status](https://travis-ci.org/deanearlbartolabac/automatic-content-spinner.png?branch=master)](https://travis-ci.org/deanearlbartolabac/automatic-content-spinner)

This plugin spins contents of WP pages (posts/pages).

## Installation
1. Copy the plugin directory into your `wp-content/plugins` directory
2. Navigate to the *Plugins* dashboard page
3. Activate this plugin

## Quick Start / Example
1. In the admin panel, goto - `Settings` > `Automatic Content Spinner`
2. update to your desired settings.

```
Welcome to my {website|page|blog}
```
Depending on you spin method, the actual output of the page may either of the following:
1. Welcome to my website
2. Welcome to my page
3. Welcome to my blog

### General Information

#### `Spin Method`
The spinning method you'll want to use on your page or post

#### `Spin on`
Spin the contents of a post, page or both.

#### `Spin Tag`
Specifiy the spin tags - that would be the opening e.g. `{{`, closing e.g. '}}' and the separator e.g. `|`

#### `Spin Option`
`detect` - This is the default. Detects whether to use the nested or flat version of the spinner (costs some speed).
`flat` - The flat version does not allow nested spin blocks, but is much faster (~2x)
`nested` - he nested version allows nested spin blocks, but is slower

### Credits
http://www.paul-norman.co.uk/2010/09/php-spinner-updated-spin-articles-for-seo/