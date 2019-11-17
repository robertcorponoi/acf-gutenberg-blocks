<?php

declare(strict_types = 1);

namespace RobertCorponoi\ACFGutenbergBlocks;

/**
 * Defines the options available for every instance and their defaults values if they exist.
 */
class Options {

  /**
   * Indicates whether block categories should be restricted to just the ones the user creates or not.
   *
   * Restricting block categories will hide all of the default Gutenberg ones in the block picker such as text, image, etc.
   *
   * @property boolean
   */
  public $restrict_block_categories = true;

  /**
   * Override the default settings with custom settings.
   *
   * @param array   $options
   * @param boolean $options->restrict_block_categories - If set to true, then only custom categories will be shown in the Gutenberg block picker.
   */
  public function __construct(array $options) {

    foreach ($this as $k => $v) {
      
      if (isset($options[$k])) $this->$k = $options[$k];

    }

  }

}
