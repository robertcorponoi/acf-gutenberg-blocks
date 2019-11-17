<?php

/**
 * Plugin Name: ACF Gutenberg Blocks
 * Plugin URI: https://github.com/robertcorponoi/acf-gutenberg-blocks
 * Description: A simple API for using ACF modules as Gutenberg blocks.
 * Version: 0.1.0
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Author: Robert Corponoi
 * Author URI: https://github.com/robertcorponoi/
 * License: MIT
 * License URI: https://github.com/robertcorponoi/acf-gutenberg-blocks/blob/master/LICENSE
 */

declare(strict_types = 1);

namespace RobertCorponoi\ACFGutenbergBlocks;

require_once('src/utils/string.php');
require_once('src/module/Module.php');
require_once('src/options/Options.php');

/**
 * The builder is used to create ACF modules as Gutenberg blocks and adds them to the Gutenberg block picker.
 */
class Builder {

  /**
   * A reference to the options for this instance of the builder.
   *
   * @property Options
   */
  private $options;

  /**
   * A reference to the modules created through this plugin.
   *
   * This is used to register the modules through acf when the `acf/init` action is called.
   *
   * @property array
   */
  private $modules = [];

  /**
   * A reference to the custom categories that have been created for this instance of the plugin.
   *
   * This is used by the `add_to_block_categories` when the action is called to merge the custom categories with 
   * the existing ones.
   *
   * @property array
   */
  private $categories = [];

  /**
   * When the builder is initialized, first it checks to make sure that the necessary version of ACF is available as it can't continue otherwise.
   *
   * @param array   $options
   * @param boolean $options->restrict_block_categories - If set to true, then only custom categories will be shown in the Gutenberg block picker.
   */
  public function __construct(array $options = []) {

    $this->check_acf_exists();

    $this->options = new Options($options);

    add_action('admin_enqueue_scripts', array($this, 'load_scripts'));
    add_action('block_categories', array($this, 'add_to_block_categories'), 10, 2);

    if ($this->options->restrict_block_categories) {

      add_action('allowed_block_types', array($this, 'restrict_block_categories'));

    }

  }

  /**
   * Loads the styles and icons needed to style the look of the editor and also the script that makes the editing experience easier.
   *
   * @param string $hook - The current page.
   */
  public function load_scripts(string $hook) {

    if ($hook == 'post-new.php' || $hook == 'post.php') {

      wp_register_style('gutenberg-editor', plugin_dir_url(__FILE__) . 'src/assets/editor.css', false, '1.0.0');
      wp_register_style('font-awesome-icons', 'https://use.fontawesome.com/releases/v5.8.1/css/all.css', false, '1.0.0');

      wp_enqueue_script('gutenberg-editor-custom', plugin_dir_url(__FILE__) . 'src/assets/acf.js', array(), '1.0.0', true);

      wp_enqueue_style('gutenberg-editor');
      wp_enqueue_style('font-awesome-icons');

    }

  }

  /**
   * Adds a new block category to the Gutenberg block picker.
   *
   * This should only be used if you are not defining a category when creating a module as doing so will automatically create a new category.
   *
   * Do not attempt to use `add_to_block_categories` to add custom categories. That method is used after custom categories have been defined 
   * to finalize the custom categories.
   *
   * @param string $name - The name of the category to add.
   */
  public function add_block_category(string $name) {

    $category = array (
      'slug'  => $name,
      'title' => __(ucfirst($name), $name)
    );

    array_push($this->categories, $category);

  }

  /**
   * Hooks into the `block_categories` action to add custom block categories to the Gutenberg block picker.
   *
   * This is not the method used to add new custom block categories, use `add_block_category` to add a new block category. This is meant to be 
   * used after custom block categories have been defined.
   *
   * @param array $categories - The existing Gutenberg block picker categories.
   * @param object $post - The post object to show the categories on.
   *
   * @return array - Returns all of the current categories.
   */
  public function add_to_block_categories(array $categories, object $post) {

    return array_merge($categories, $this->categories);

  }

  /**
   * Restricts the categories in the Gutenberg block picker to categories that have been created through this plugin.
   *
   * This means that the default categories such as core, formatting, etc. that are there by default and all components that are a part of those 
   * will be removed.
   *
   * @return array - Returns the categories that are active.
   */
  public function restrict_block_categories() {

    $categories = array();

    foreach($this->modules as $module) {

      array_push($categories, $module['location'][0][0]['value']);

    }

    return $categories;

  }

  /**
   * Creates a new module and returns it so that controls can be added to it.
   *
   * Along with creating the module, this also creates the block for the module in the Gutenberg editor that will allow the user to use the module.
   *
   * @param string $name - The name of the module.
   * @param string $category - The category in the Gutenberg block picker that this module belongs under.
   *
   * @return Module - Returns the module so that controls can be added to it.
   */
  public function add_module(string $name, string $category): Module {

    $module = array();

    $nameToKebabCase = toKebabCase($name);

    $key = sprintf('group_%s', toKebabCase($name));

    $location = array(array(array(
      'param'     => 'block',
      'operator'  => '==',
      'value'     => sprintf('acf/%s', toKebabCase($key))
    )));

    $module['key']      = $key;
    $module['title']    = $name;
    $module['location'] = $location;
    $module['category'] = $category;

    $needs_category = true;

    foreach ($this->categories as $existing_category) {

      if ($existing_category['slug'] == $category) {

        $needs_category = false;
        break;

      }

    }

    if ($needs_category) $this->add_block_category($category);

    array_push($this->modules, $module);

    return new Module($module);

  }

  /**
   * Defines how the blocks are rendered on the front-end.
   *
   * @param array The data of the module to render.
   */
  public static function acf_block_render_callback($module) {

    do_action('builder_render_callback', $module);

  }

  /**
   * Verify that the version of the ACF plugin that we need is installed.
   *
   * Since this plugin cannot work without the `acf_add_local_field_group` method, this will throw an exception and stop any further operation.
   *
   * @access private
   */
  private function check_acf_exists() {

    if (!function_exists('acf_add_local_field_group')) {

      throw new \Exception("The Advanced Custom Fields plugin was not detected. Please check to make sure it is active and try again.");

    }

  }

}

