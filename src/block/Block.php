<?php

declare(strict_types = 1);

namespace RobertCorponoi\ACFGutenbergBlocks;

require_once(__DIR__ . '/../utils/string.php');

/**
 * A block is a module created with ACF represented as a Gutenberg block.
 *
 * The block just defines the structure of the module and makes it available in the editor, the module structure still has to be declared.
 */
class Block {

  /**
   * The name of this block.
   *
   * This is used by ACF to get and save data saved to the module this block corresponds to.
   *
   * @property string
   */
  private $name;

  /**
   * The user friendly name of this block.
   *
   * @property string
   */
  private $title;

  /**
   * The description of this block.
   *
   * @property string
   */
  private $description;

  /**
   * The function that defines how to render this block.
   *
   * @property string
   */
  private $render_callback;

  /**
   * The category of blocks that this block should be placed under in the Gutenberg
   * block picker.
   *
   * @property string
   */
  private $category;

  /**
   * By default, start the blocks in edit mode since preview mode is not great
   * currently for building out Modules.
   *
   * @property string
   */
  private $mode = 'edit';

  /**
   * @param array $block
   * @param string $name            - The programmatic name of this Block.
   * @param string $title           - The user friendly name of this Block.
   * @param string $render_callback - The method that defines how the Blocks are rendered.
   * @param string $category        - The category in the Gutenberg block picker that this Block belongs under.
   */
  public function __construct(array $block) {

    $this->name             = $block['name'];

    $this->title            = $block['title'];

    $this->render_callback  = $block['render_callback'];

    $this->category         = $block['category'];

  }

  /**
   * Create and return an associative array of this Block's properties in a format that ACF expects to be passed 
   * to `acf_register_block`.
   *
   * @return array - Returns an array containing this Block's properties.
   */
  public function render(): array {

    return get_object_vars($this);

  }

}

?>
