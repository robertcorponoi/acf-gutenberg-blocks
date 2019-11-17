<?php

declare(strict_types = 1);

namespace RobertCorponoi\ACFGutenbergBlocks;

require_once(__DIR__ . '/../block/Block.php');
require_once(__DIR__ . '/../utils/string.php');
require_once(__DIR__ . '/../controls/ControlManager.php');

/**
 * The repeater handles the creation of ACF Repeater fields.
 */
class Repeater extends ControlManager {

  /**
   * @param $name   - The programmatic name of this Repeater.
   * @param $label  - The name to use for the label of this Repeater in the editor.
   */
  public function __construct(string $name, string $label) {

    $this->name = $name;

    $this->label = $label;

    $this->type = 'repeater';

    $this->key = sprintf('field_%s', $name);

    $this->sub_fields = [];

  }

  /**
   * Builds the specified control, adds it to the list of controls for this module, and then returns the control so that it can be modified via 
   * adding instructions, a default value, conditionals, etc.
   *
   * @return ControlBuilder - Returns the control instance so that extra properties can be added to it.
   */
  private function build_control(string $name, string $type, array $other = array()): ControlBuilder {

    $control = new ControlBuilder($name, $type);

    $control_ref = &$control;

    array_push($this->controls, $control_ref);

    return $control;

  }

  /**
   * Creates the structure of the controls as is expected by ACF by calling the method on each control.
   */
  private function update_controls() {

    foreach ($this->controls as $control) {

      array_push($this->sub_fields, $control->render());

    }

  }

  /**
   * Create the Control array as is expected by ACF.
   *
   * @return array - Returns a generic associative array with this controls properties.
   */
  public function render(): array {

    $this->update_controls();

    return get_object_vars($this);

  }

}

?>
