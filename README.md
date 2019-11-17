<div align="center">

# ACF Gutenberg Blocks

</div>

<div align="center">

ACF Gutenberg Blocks allows you to easily define ACF modiles as components consisting of PHP definitons, Twig structure, and stying. This lets you keep your ACF components defined in the code and cleanly stored in the project structure.

</div>

**Table of Contents:**

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Example](#example)
- [API](#api)
- [Field Methods](#field-properties)

## **Features**

- Easily create custom blocks for a seamless page building experience.
- Abstracts away from the creation of ACF modules and turning them into Gutenberg blocks.
- Group your php, twig, and styling in component folders for easy readability.

## **Installation**

To use ACF Gutenberg Blocks you must have following prerequisites:

- [Composer](https://getcomposer.org/download/)
- [Advanced Custom Fields](https://www.advancedcustomfields.com/)
- PHP >=7.2
- Wordpress >=5.2

Then you can use composer to install it like so:

```bash
$ composer require "robertcorponoi/acf-gutenberg-blocks"
```

## **Usage**

While any file structure will work, it's recommended to create a new folder at the top level in your theme for all of the blocks and add a functions.php file containing the following:

```php
<?php

// Use the builder's namespace.
use RobertCorponoi\ACFGutenbergBlocks\Builder;

// Create a new instance of the builder
$builder = new Builder();

// Register all component php files as a glob.
foreach (glob(__DIR__ . '/components/**/*.php') as $filename) require_once($filename);

/**
 * Determines where to find the templates for the rendered modules.
 *
 * This isn't defined automatically by the builder because it should remain flexible.
 *
 * @param object $module - The module to render.
 */
 function render_builder_module($module) {

  // Get the Timber instance and the context as required by ACF.
  $timber = new Timber\Timber();
  
  $context = timber::get_context();
  
  $context['block'] = $module;
  $context['fields'] = get_fields();

  // module_type is the type of block this block is.
  // In the Gutenberg editor this is is used to group blocks together.
  $block_type = $context['block']['category'];

  // module_name is the name of the module as defined in the php file.
  $module_name = str_replace('acf/group-', '', $context['block']['name']);

  // The path to the php file that defines the module.
  $path = sprintf('path/to/blocks/%s/%s/%s.twig', $block_type, $module_name, $module_name);
  
  // Send the file to timber to render.
  $timber::render($path, $context);

}

add_action('builder_render_callback', 'render_builder_module');
```

and then in your theme's functions.php, you want to require this file so that it can actually run:

**functions.php**

```php
require_once('patterns/functions.php');
```

## **Example**

In the exmple below, we use the following folder structure:

```
...
patterns
  blocks
    heroes
      hero-standard
        hero-standard.php
        hero-standard.twig
        hero-standard.scss
      hero-video
        hero-video.php
        hero-video.twig
        hero-video.scss
    images
      image-parallax
        image-parallax.php
        image-parallax.twig
        image-parallax.scss
  functions.php
...
```

The patterns folder is at the root of the theme directory and in it are the blocks folder and a functions.php folder. The functions.php contains the [usage](#usage) code described above and that gets imported into the theme's functions.php

The blocks folder contains all of the blocks we have made. It is recommended to structure your project like above with the root folder of the component being the category (video, images) and then each individual folder will contain the files for thath component specifically.

**Note:** ACF Gutenberg Blocks does not handle the importing of scss files or usage of CSS. You will have to require those manually with webpack or whatever tool you are using to handle bundling of styling.

Let's take a look at hero-standard.

**hero-standard.php**

```php
// Create a new module with the name of Hero Standard and put it under the heroes category.
// If a category does not yet exist, it will be created automatically.
$hero_standard = $builder->add_module('Hero Standard', 'heroes');

// Add a text field which will be referenced by 'header' in the twig and in the editor it will be labelled as 'Header'.
$hero_header = $hero_standard->add_text('header', 'Header');

$hero_bg_image = $hero_standard->add_image_picker('background', 'Background');

// You can add instructions that show up in the wordpress editor.
$hero_bg_image->instructions('The background image for the hero');
```

**hero-standard.twig**

```twig
<div class="hero hero--standard" style="background-image: url('{{ fields.background }}');">
  <h1>{{ fields.header }}</h1>
</div>
```

## **API**

The fields available to be added to modules are:

- add_text
- add_text_area
- add_number
- add_email
- add_url
- add_password
- add_wysiwyg
- add_oembed
- add_image_picker
- add_file_upload
- add_gallery_picker
- add_dropdown
- add_checkbox
- add_boolean
- add_radio_button
- add_repeater

All of the documentation for the fields can be found [here](https://www.advancedcustomfields.com/resources/register-fields-via-php/#field-settings). The documentation found there is more helpful than documentation that can be found here and the methods above were constructed from that documentation.

**Note:** Repeater fields are special, when you create a repeater field, you can use any of the above methods on it to add repeatable text, images, etc.

## **Field Methods**

Once you add a field using the above methods, you can add the following properties to it:

- instructions
- required
- default
- condition
- placeholder
- prepend
- append

Again just like above, the documentation [here](https://www.advancedcustomfields.com/resources/register-fields-via-php/#field-settings) is more helpful than what can be put here.

## **License**

MIT