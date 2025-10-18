# SCSSPHP Plugin to create '*critical CSS*'

This is a preprocessor for SCSS files to generate CSS files in Kirby.
For details look at *[README.md](README.md)* first.

As an option, you can use this plugin to create '**critical CSS**'.

## Critical SCSS (a.k.a. above the fold)

If you would like to improve the performance of your website, you can use the 'scss.critical.php' snippet in combination with the "**normal**" 'scss.php' snippet. This *critical* part of the plugin is **optional and still experimental**. Using critical CSS means inlining any CSS that is used to render content directly visible when you open a page.
Before you start using critical CSS, my advice is to read more about this concept on [CSS Tricks](https://css-tricks.com/authoring-critical-fold-css/) or [Google PageSpeed Insights](https://developers.google.com/speed/docs/insights/PrioritizeVisibleContent).

## Setup the Critical SCSS

1. Follow the instructions for installing and setup this plugin in the **main** *[README.md](README.md)*.
2. Create a file `default.critical.scss` and place it inside `assets/scss`.
3. Call the **Critical CSS snippet** with `<?php snippet("scss.critical") ?>` in your HTML head.
4. Call the **SCSS snippet** with `<?php snippet("scss") ?>` **below** in your page footer **instead of** in your HTML head.

## Using Critical SCSS

Everything in `default.critical.scss` will be compiled and placed in your HTML head (not as a css-file but as css-code). All relative URLs in your critical SCSS will be automatically converted to the correct absolute URLs in your critical CSS.

It is possible to create different SCSSs for each Kirby template. Just use the name of your template file for the SCSS file (e.g. `article.critical.scss` for `templates/article.php`), and place it in `assets/scss`. If no SCSS file for a template can be found, `default.critical.scss` will be used.

## Hints

This plugin does not (yet) detect which CSS should be placed in your critical CSS. Therefor you need to manually place or import SCSS that will affect everything that has a large chance to be directly visible '*above the fold*' when loaded. Do not forget to include your SCSS utilities (e.g. mixins) and settings (i.e. global variables) in your critical SCSS file.

The critical SCSS **must be updated** after it has been uploaded *to another server*. This is necessary because the absolute URLs in your critical CSS need to be updated.
Therefore, after uploading, you should delete these files “**\*.critical.css**” generated with this `snippet("scss.critical")` **once** on the *another server*.

In terms of **critical SCSS**, I recommend avoiding such non-resolvable files in order to prevent such reloading of css files!

It is possible to create different critical SCSSs for each Kirby template. Just use the name of your template file for the critical SCSS file (e.g. 'article.critical.scss' for 'templates/article.php'), and place it in 'assets/scss'. If no critical SCSS file for a template can be found, 'default.critical.scss' will be used.

My advice is not to remove any SCSS from your main SCSS file.
