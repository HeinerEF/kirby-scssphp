# SCSSPHP Plugin

The **SCSSPHP plugin** for Kirby creates the corresponding "**\*.css** file" from a "**\*.scss** file" and automatically integrates it into the invisible \<HEAD\> area of the website via the stylesheet link line `<?php snippet('scss') ?>`.
The "**\*.scss** file" can, of course, include any number of additional "**\*.scss** files" using *`@import "..."`* in accordance with the Sass rules, which are also evaluated by the plugin.

The plugin can automatically detect whether one of the "**\*.scss** files" used has been changed and then regenerates the "**\*.css** file." Otherwise, only the stylesheet link line with the last generated "**\*.css** file" is provided.

It is possible to create different SCSSs for each Kirby template. Just use the name of your template file for the SCSS file (e.g. `article.scss` for `templates/article.php`), and place it in `assets/scss`. If no SCSS file for a template can be found, `default.scss` will be used.

It is also possible to use a different SCSS file and/or for other media by adding another call such as "`<?php snippet('scss', ['scss' => 'print', 'media' => 'print']); ?>`" into the invisible \<HEAD\> area of the website code.

When the website is accessed by someone logged-in with the role of a `admin`, a detailed "**\*.css** file" is used, which also links to the corresponding SourceMap. This detailed "**\*.css** file" can be examined in detail in the browser, for example with \<F12\>.
All other visitors receive a compressed "**\*.css** file".
You can change these roles in the *config* via the `HeinerEF.scssphp.scssDeveloperRoles` option.

**Optionally** you can use this Kirby plugin to generate '*critical CSS*'. Please look at *[README_critical_CSS.md](README_critical_CSS.md)* for details.

This Kirby plugin uses the **SCSSPHP compiler** https://github.com/scssphp/scssphp/ written in PHP, which was originally developed by *Leaf Corcoran* and is now maintained by *Anthon Pang* and *Cédric Morin*.


## Installation

### Download

[Download](https://github.com/heineref/kirby-scssphp/archive/master.zip) the contents of this repository as Zip file.

Rename the **extracted** folder to `heineref_scssphp` and copy it into the `site/plugins/` directory in your Kirby project. If it does not exist, create a new directory `site/plugins/` first.
This file `README.md` therefore receives the path `site/plugins/heineref_scssphp/README.md`.

### Composer

```
composer require heineref/kirby-scssphp
```

### Git submodule

If you have used git in your project before:

```
git submodule add https://github.com/heineref/kirby-scssphp.git site/plugins/heineref_scssphp
```


## Setup

1. Make sure you backup your original CSS files **beforehand**.
2. Create a folder `scss` inside the `/assets` folder.
3. Create a file `default.scss` with some content and place it inside `assets/scss`.
4. Make sure the folder `/assets/css` exists on your server.
5. Add `'scssNestedCheck' => true` to the config of your **dev environment**. [Read more about multi environment setup for Kirby](https://getkirby.com/docs/guide/configuration#multi-environment-setup).
6. Call the SCSS snippet with `<?php snippet('scss') ?>` in your HTML \<HEAD\> area.


## Using SCSS plugin

After installing and setting up this plugin, `assets/css/*.css`-files will be overwritten automatically.

## Options

All options require **`HeinerEF.scssphp.`** as prefix.

**`scssNestedCheck`**

- default: `false`
- If true, the plugin checks if partials are changed. If any partial is newer than the main SCSS file, the main SCSS file will be 'touched'. This will trigger the **SCSSPHP compiler**, on this server and also an another environment when synced.
- Look at "`Setup 5.`" for details!

**`scssDeveloperRoles`**

- default: `['admin']`
- Only these roles use the **NOT minified** CSS and can access the corresponding SourceMap.

**`scssDevelopment`**

- default: `false`
- If true, the CSS file is build EVERY time the **SCSSPHP plugin** runs


## Compatibility

Sass is a **CSS** preprocessor language that adds many features such as variables, mixins, imports, nesting, color manipulation, functions, and control directives.

For a complete guide to the syntax of Sass, please consult the [official documentation](https://sass-lang.com/documentation).

The new implementation v2.x of the **SCSSPHP compiler** is a port of *dart-sass*, making it spec-compliant and easier to update by their developers to support new Sass features. However the actual release does not implement Sass modules yet.

## Note

Unlike previous implementations of the **SCSSPHP plugin**, you can now update the **SCSSPHP compiler** independently of the **SCSSPHP plugin**, e.g. using Composer, as long as the compiler calls and data transfer by the **SCSSPHP compiler** remain unchanged.

Please **only** update in your development environment!

## Requirements

This plugin was built using **Kirby 5.x** and tested with **Kirby 4.x**.

It may work with earlier versions. 

Otherwise, take a look at https://github.com/bartvandebiezen/kirby-v2-scssphp


## Disclaimer

This plugin is provided "**as is**" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment.


## License

[MIT](LICENSE.md)

It is not permitted to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any form of hate speech.

## Credits

- [Leaf Corcoran et al.](https://github.com/scssphp/scssphp) the **SCSSPHP compiler** written in PHP
- [Bart van de Biezen](https://github.com/bartvandebiezen/kirby-v2-scssphp) the initial **Kirby Plugin**
- [Jan-Frederik Stieler](https://github.com/janstieler/kirby-v2-scssphp) updated the **Kirby Plugin** to Kirby 3
