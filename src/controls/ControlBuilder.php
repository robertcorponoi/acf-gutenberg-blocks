<?php

declare(strict_types = 1);

namespace RobertCorponoi\ACFGutenbergBlocks;

/**
 * Abstracts the creation of Controls so that each type of Control doesn't have to be its own object.
 */
class ControlBuilder {

  /**
   * The properties of this Control.
   *
   * As each Control has different properties this is array is assigned dynamically with the only consistent fields 
   * being key, name, and title.
   *
   * @property array
   */
  private $props = [];

  /**
   * Initializes a new Control with minimum configuration.
   *
   * @param string  $name     - The name of this Control in snake_case format.
   * @param string  $label    - The label for this Control in the editor.
   * @param string  $type     - The type of Control this Element is.
   * @param array   $options  - Additional Control initialization options.
   */
  public function __construct(string $name, string $label, string $type, array $options = []) {

    $this->key = sprintf('field_%s', $name);

    $this->name = $name;

    $this->label = $label;

    $this->type = $type;

  }

  /**
   * Adds instructions to the Control.
   *
   * Instructions detail to the author how the Control is meant to be used.
   *
   * @param string $instructions - The instructions to show to the author.
   */
  public function instructions(string $instructions) {

    $this->add_prop('instructions', $instructions);

  }

  /**
   * By default all Controls are not required to have a value. Setting this to `true` will make it so that this Control 
   * must have a value before it can be published.
   */
  public function required() {

    $this->add_prop('required', 1);

  }

  /**
   * Set a default value that appears for this Control if no value is specified.
   *
   * @param string $value - The default value to set for this control.
   */
  public function default(string $value) {

    $this->add_prop('default_value', $value);

  }

  /**
   * Sets a condition so that this control will only show if another control has the expected value.
   *
   * @param string  $control  - The name of the control that this condition depends upon.
   * @param mixed   $expected - The expected value of the above control.
   */
  public function condition(string $control, $expected) {

    $conditional = array(array(array(
      'field'     => sprintf('field_%s', $control),
      'operator'  => '==',
      'value'     => $expected,
    )));

    $this->add_prop('conditional_logic', $conditional);

  }

  /**
   * Sets a placeholder attribute for this control.
   *
   * Note that not all fields support a placeholder attribute but more than few do and this avoid repeating the same attribute
   * for each one.
   *
   * @param string $text - The placeholder text to set for this control.
   */
  public function placeholder(string $text) { 

    $this->add_prop('placeholder', $text);

  }

  /**
   * Prepends a piece of text before the input.
   *
   * Only supported on fields where a textbox/textarea is present.
   *
   * @param string $text - The text to prepend to the input.
   */
  public function prepend(string $text) {

    $this->add_prop('prepend', $text);

  }

  /**
   * Appends a piece of text after the input.
   *
   * Only supported on fields where a textbox/textarea is present.
   *
   * @param string $text - The text to append to the input.
   */
  public function append(string $text) {

    $this->add_prop('append', $text);

  }

  /**
   * Create the Control array as is expected by ACF.
   *
   * @return array - Returns a generic associative array with this controls properties.
   */
  public function render(): array {

    return get_object_vars($this);

  }

  /**
   * Adds a new property to the `props` array.
   *
   * @param string  $propName   - The name of the property to add.
   * @param mixed   $propValue   - The value of the property to add.
   */
  public function add_prop(string $propName, $propValue) {

    $this->{$propName} = $propValue;

  }

}

?>
