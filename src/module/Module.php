<?php

declare(strict_types = 1);

namespace RobertCorponoi\ACFGutenbergBlocks;

require_once(__DIR__ . '/../block/Block.php');
require_once(__DIR__ . '/../repeater/Repeater.php');
require_once(__DIR__ . '/../utils/string.php');
require_once(__DIR__ . '/../controls/ControlManager.php');

/**
 * A Module is a collection of controls that defines how a section looks. Modules can be comprised of one or more elements and they 
 * are selectable from the Gutenber block picker in the edit page screen.
 *
 * In ACF, a Module is comparable to a field group.
 */
class Module extends ControlManager {

  /**
   * The programmatic name of this Module.
   *
   * @property string
   */
  private $key;

  /**
   * The user friendly name of this Module.
   *
   * @property string
   */
  private $title;

  /**
   * The controls that have been added to this Module in an ACF friendly format.
   *
   * @property array
   */
  private $fields;

  /**
   * The location of this Module.
   *
   * All of the modules have the same location definition with the only difference between them being the Module's programmtic names.
   *
   * @property array
   */
  private $location;

  /**
   * The block that represents this Module in the Gutenberg block picker.
   *
   * @property Block
   */
  private $block;

  /**
   * @param array   $module
   * @param string  $key      - The programmatic name of this Module.
   * @param string  $title    - The user friendly name of this Module.
   * @param array   $location - The array that defines where this Module shows up on.
   * @param string  $category - The category that this Module belongs under in the Gutenberg block picker.
   */
  public function __construct(array $module) {

    $this->key      = $module['key'];

    $this->title    = $module['title'];

    $this->location = $module['location'];

    $this->category = $module['category'];

    $this->fields   = array();

    $this->controls = array();

    add_action('acf/init', array($this, 'finalize'));

  }

  /**
   * Finalizes this Moudle and all of the controls added to it by converting it to a format that can be used by ACF and then 
   * registering it with ACF.
   */
  public function finalize() {

    $block_init = array(
      'name'            => $this->key,
      'title'           => $this->title,
      'render_callback' => 'RobertCorponoi\ACFGutenbergBlocks\Builder::acf_block_render_callback',
      'category'        => $this->category 
    );

    $block = new Block($block_init);

    acf_register_block($block->render());

    $this->update_controls();

    $rendered = get_object_vars($this);

    acf_add_local_field_group($rendered);


  }

  /**
   * Creates the structure of the controls as is expected by ACF by calling the method on each control.
   */
  private function update_controls() {

    $this->fields = array();

    foreach ($this->controls as $control) {

      array_push($this->fields, $control->render());

    }

  }

}

?>
