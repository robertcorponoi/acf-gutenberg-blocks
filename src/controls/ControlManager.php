<?php

declare(strict_types = 1);

namespace RobertCorponoi\ACFGutenbergBlocks;

require_once('ControlBuilder.php');

/**
 * The ControlManager object acts as a central hub for the Module and Repeater objects to use that defines the type of controls that 
 * can be added to each of them.
 * 
 * This also keeps a reference to the controls added so the consuming object can reference and parse the chosen controls in a way they 
 * can use them.
 */
abstract class ControlManager {

  /**
   * The controls added to this manager.
   * 
   * @property array
   */
  public $controls = [];

  /**
   * Adds a text control to this manager.
   * 
   * The value returned from this text control will be the input as directly provided from the user, no sanitization or parsing is
   * provided currently.
   * 
   * @param string  $name                   - The name (kebab case) that will be used to reference this control in the template.
   * @param string  $label                  - The name of the label that this field will have in the editor.
   * @param array   $options                - Optional parameters to alter the functionality of this control.
   * @param string  $options['placeholder'] - Appears within the input. Defaults to ''.
   * @param string  $options['prepend']     - The string to prepend the user input with. Defaults to ''.
   * @param string  $options['append']      - The string to append the user input with. Defaults to ''.
   * @param string  $options['maxlength']   - Restricts the character limit. Defaults to 0 for infinite.
   * @param boolean $options['readonly']    - Makes the input readonly. Defaults to false.
   * @param boolean $options['disabled']    - Makes the input disabled. Defaults to false.
   *
   * @return ControlBuilder - Returns this control for further customization.
   */
  public function add_text(string $name, string $label, array $options = []): ControlBuilder {

    return $this->build_control($name, $label, 'text', $options);

  }

  /**
   * Adds a text area control to this manager.
   * 
   * The value returned from this text area control will be the input as directly provided from the user, no sanitization or parsing is 
   * provided currently.
   * 
   * @param string  $name                   - The name (kebab case) that will be used to reference this control in the template.
   * @param string  $label                  - The name of the label that this field will have in the editor.
   * @param array   $options                - Optional parameters to alter the functionality of this control.
   * @param string  $options['placeholder'] - Appears within the input. Defaults to ''.
   * @param string  $options['maxlength']   - Restricts the character limit. Defaults to 0 for infinite.
   * @param integer $options['rows']        - Restricts the number of rows and height. Defaults to '' for no restriction.
   * @param string  $options['string']      - Decides how to render new lines. Defaults to 'wpautop'.
   *                                          Choices are 'wpautop (Automatically add paragraphs), 'br' (Automatically add <br>) or '' (No formatting).
   * @param boolean $options['readonly']    - Makes the input readonly. Defaults to false.
   * @param boolean $options['disabled']    - Makes the input disabled. Defaults to false.
   *
   * @return ControlBuilder - Returns this control for further customization.
   */
  public function add_text_area(string $name, string $label, array $options = []): ControlBuilder {
    
    return $this->build_control($name, $label, 'textarea', $options); 
    
  }
  
  /**
   * Adds a number input control to this Module.
   *
   * @param string  $name                   - The name (kebab case) that will be used to reference this control in the template.
   * @param string  $label                  - The name of the label that this field will have in the editor.
   * @param array   $options                - Optional parameters to alter the functionality of this control.
   * @param string  $options['placeholder'] - Appears within the input. Defaults to ''.
   * @param string  $options['prepend']     - The string to prepend the user input with. Defaults to ''.
   * @param string  $options['append']      - The string to append the user input with. Defaults to ''.
   * @param integer $options['min']         - The minimum value that can be provided for this number input. Defaults to '' for no minimum.
   * @param integer $options['max']         - The maximum value that can be provided for this number input. Defaults to '' for no maximum.
   * @param integer $options['step']        - The step size increments. Defaults to ''.
   *
   * @return ControlBuilder - Returns this control for further customization.
   */
  public function add_number(string $name, string $label, array $options = []): ControlBuilder {
    
    return $this->build_control($name, $label, 'number', $options);
    
  }

  /**
   * Adds an email input control to this Module.
   *
   * @param string $name                   - The name (kebab case) that will be used to reference this control in the template.
   * @param string $label                  - The name of the label that this field will have in the editor.
   * @param array  $options                - Optional parameters to alter the functionality of this control.
   * @param string $options['placeholder'] - Appears within the input. Defaults to ''.
   * @param string $options['prepend']     - The string to prepend the user input with. Defaults to ''.
   * @param string $options['append']      - The string to append the user input with. Defaults to ''.
   *
   * @return ControlBuilder - Returns this control for further customization.
   */
  public function add_email(string $name, string $label, array $options = []): ControlBuilder {

    return $this->build_control($name, $label, 'email', $options);

  }

  /**
   * Adds a URL input control to this Module.
   *
   * @param string $name                   - The name (kebab case) that will be used to reference this control in the template.
   * @param string $label                  - The name of the label that this field will have in the editor.
   * @param array  $options                - Optional parameters to alter the functionality of this control.
   * @param string $options['placeholder'] - Appears within the input. Defaults to ''.
   *
   * @return ControlBuilder - Returns this control for further customization.
   */
  public function add_url(string $name, string $label, array $options = []): ControlBuilder {

    return $this->build_control($name, $label, 'url', $options);

  }

  /**
   * Adds a password input control to this Module.
   *
   * @param string $name                   - The name (kebab case) that will be used to reference this control in the template.
   * @param string $label                  - The name of the label that this field will have in the editor.
   * @param array  $options                - Optional parameters to alter the functionality of this control.
   * @param string $options['placeholder'] - Appears within the input. Defaults to ''.
   * @param string $options['prepend']     - The string to prepend the user input with. Defaults to ''.
   * @param string $options['append']      - The string to append the user input with. Defaults to ''.
   *
   * @return ControlBuider - Returns this control for further customization.
   */
  public function add_password(string $name, string $label, array $options = []): ControlBuilder {

    return $this->build_control($name, $label, 'password', $options);

  }

  /**
   * Adds a WYSIWYG control to this Module.
   *
   * @param string  $name                     - The name (kebab case) that will be used to reference this control in the template.
   * @param string  $label                  - The name of the label that this field will have in the editor.
   * @param array   $options                  - Optional parameters to alter the functionality of this control.
   * @param string  $options['tabs']          - Specify which tabs are available. Defaults to 'all' Choices available are 'all', 'visual', or 'text'.
   * @param string  $options['toolbar']       - Specify the editor's toolbar. Defaults to 'full'. 
   *                                            Choices available are 'full', 'basic', or a custom toolbar.
   * @param boolean $options['media_upload']  - Determines whether the media upload button is shown or not. Defaults to true.
   *
   * @return ControlBuilder - Returns this control for further customization.
   */
  public function add_wysiwyg(string $name, string $label, array $options = []): ControlBuilder {

    return $this->build_control($name, $label, 'wysiwyg', $options);

  }

  /**
   * Adds a oEmbed control to this Module.
   *
   * @param string  $name               - The name (kebab case) that will be used to reference this control in the template.
   * @param string  $label                  - The name of the label that this field will have in the editor.
   * @param array   $options            - Optional parameters to alter the functionality of this control.
   * @param integer $options['width']   - Specify the width of the oEmbed element. Can be overriden by CSS.
   * @param integer $options['height']  - Specify the height of the oEmbed element. Can be overriden by CSS.
   *
   * @return ControlBuilder - Returns this control for further customization.
   */
  public function add_oembed(string $name, string $label, array $options = []): ControlBuilder {

    return $this->build_control($name, $label, 'oembed', $options);

  }

  /**
   * Adds an image picker control to this Module.
   *
   * The value returned from this image picker will be a single URL to the location of the chosen image.
   *
   * @param string    $name                     - The name (kebab case) that will be used to reference this control in the template.
   * @param string    $label                  - The name of the label that this field will have in the editor.
   * @param array     $options                  - Optional parameters to alter the functionality of this control.
   * @param string    $options['return_format'] - The type of value returned by the field. Choices are 'array', 'url', or 'id'. Defaults to 'url'.
   * @param integer   $options['min']           - The minimum number of attachments required to be selected. Defaults to 0.
   * @param integer   $options['max']           - The maximum number of attachements allowed to be selected. Defaults to 0 for infinite.
   * @param string    $options['preview_size']  - Specify the image size shown when editing. Defaults to 'thumnail'.
   * @param string    $options['library']       - Restricts the image library. Defaults to 'all'.
   * @param integer   $options['min_width']     - Specify the minimum width in pixels required when uploading. Defaults to 0.
   * @param integer   $options['min_height']    - Specify the minimum height in pixels required when uploading. Defaults to 0.
   * @param integer   $options['min_size']      - Specify the minimum filesize in MB required when uploading. 
   *                                              The units may also be included. eg. '256KB'. Defaults to 0.
   * @param integer   $options['max_width']     - Specify the maximum width in pixels required when uploading. Defaults to 0 for infinite.
   * @param integer   $options['max_height']    - Specify the maximum height in pixels allowed when uploading. Defaults to 0 for infinite.
   * @param integer   $options['max_size']      - Specify the maximum filesize in MB required when uploading. 
   *                                              The units may also be included eg. '256KB'. Defaults to 0 for infinite.
   * @param string    $options['mime_types']   - Comma separated list of file type extensions allowed when uploading. Defaults to '' for all mime types.
   *
   * @return ControlBuilder - Returns this control for further customization.
   */
  public function add_image_picker(string $name, string $label, array $options = []): ControlBuilder {

    $defaults = ['return_format' => 'url'];

    $defaults = array_merge($defaults, $options);

    return $this->build_control($name, $label, 'image', $defaults);

  }

  /**
   * Adds a file upload control to this Module.
   *
   * @param string  $name                     - The name (kebab case) that will be used to reference this control in the template.
   * @param string  $label                  - The name of the label that this field will have in the editor.
   * @param array   $options                  - Optional parameters to alter the functionality of this control.
   * @param string  $options['return_format'] - The type of value returned by the field. Choices are 'array', 'url', or 'id'. Defaults to 'url'.
   * @param string  $options['preview_size']  - Specify the file size shown when editing. Defaults to 'thumbnail'.
   * @param string  $options['library']       - Restricts the file library. Choices are 'all' or 'uploadedTo'. Defaults to 'all'.
   * @param integer $options['min_size']      - Specify the minimum file size in MB required when uploading. Defaults to 0.
   * @param integer $options['max_size']      - Specify the maximum file size in MB required when uploading. Defaults to 0 for infinite.
   * @param string  $options['mime_types']    - Comma separated list of file type extensions allowed when uploading. Defaults to '' for any.
   *
   * @return ControlBuilder - Returns this control for further customization.
   */
  public function add_file_upload(string $name, string $label, array $options = []): ControlBuilder {

    $defaults = [
      'return_format' => 'url',
      'preview_size'  => 'thumbnail',
      'library'       => 'all',
      'min_size'      => 0,
      'max_size'      => 0,
      'mime_types'    => ''
    ];

    $control_options = array_merge($defaults, $options);

    return $this->build_control($name, $label, 'file', $control_options);

  }

  /**
   * Adds a gallery picker control to this Module.
   *
   * @param string    $name                     - The name (kebab case) that will be used to reference this control in the template.
   * @param string    $label                  - The name of the label that this field will have in the editor.
   * @param array     $options                  - Optional parameters to alter the functionality of this control.
   * @param integer   $options['min']           - The minimum number of attachments required to be selected. Defaults to 0.
   * @param integer   $options['max']           - The maximum number of attachements allowed to be selected. Defaults to 0 for infinite.
   * @param string    $options['preview_size']  - Specify the image size shown when editing. Defaults to 'thumnail'.
   * @param string    $options['library']       - Restricts the image library. Defaults to 'all'.
   * @param integer   $options['min_width']     - Specify the minimum width in pixels required when uploading. Defaults to 0.
   * @param integer   $options['min_height']    - Specify the minimum height in pixels required when uploading. Defaults to 0.
   * @param integer   $options['min_size']      - Specify the minimum filesize in MB required when uploading. 
   *                                              The units may also be included. eg. '256KB'. Defaults to 0.
   * @param integer   $options['max_width']     - Specify the maximum width in pixels required when uploading. Defaults to 0 for infinite.
   * @param integer   $options['max_height']    - Specify the maximum height in pixels allowed when uploading. Defaults to 0 for infinite.
   * @param integer   $options['max_size']      - Specify the maximum filesize in MB required when uploading. 
   *                                              The units may also be included eg. '256KB'. Defaults to 0 for infinite.
   * @param string    $options['mime_types']   - Comma separated list of file type extensions allowed when uploading. Defaults to '' for all mime types.
   *
   * @return ControlBuilder - Returns this control for further customizaton.
   */
  public function add_gallery_picker(string $name, string $label, array $options = []): ControlBuilder {

    return $this->build_control($name, $label, 'gallery', $options);

  }

  /**
   * Adds a button control to this Module.
   *
   * @param string  $name             - The programmatic name of this button control.
   * @param string  $label            - The name of the label that this field will have in the editor.
   * @param string  $text             - The text that should display on the button.
   * @param string  $link             - The link that should be placed in this button's href attribute.
   * @param boolean [$new_tab=false]  - Indicates whether this button should open the link in a new tab or not.
   *
   * @return ControlBuilder - Returns this control for further customization.
   */
  public function add_button(string $name, string $label, string $text, string $link, boolean $new_tab): ControlBuilder {

    $this->build_control($name . '_text', $text);

    $this->build_control($name . '_link', $link);

    if ($new_tab) $this->build_control($name . '_new_tab', $label, $new_tab);

  }

  /**
   * Adds a select dropdown control.
   *
   * @param string    $name                     - The name (kebab case) that will be used to reference this control in the template.
   * @param string    $label                    - The name of the label that this field will have in the editor.
   * @param array     $choices                  - An associative array with the keys being the choices that the user sees and the values being
   *                                              what those keys represent when chosen.
   * @param array     $options                  - Optional parameters to alter the functionality of this control.
   *                                              and the values represent the label of the choice.
   * @param array     $options
   * @param boolean   $options['allow_null']    - Allow a null (blank) value to be selected. Defaults to 0 for false.
   * @param boolean   $options['multiple']      - Allow multiple choices to be selected. Defaults to 0 for false.
   * @param boolean   $options['ui']            - Use the select2 interface. Defaults to 0 for false.
   * @param boolean   $options['ajax']          - Load choices via AJAX. The ui setting must also be true for this to work. Defaults to 0 for false.
   * @param string    $options['placeholder']   - Appears within the select2 input. Defaults to ''.
   *
   * @return ControlBuilder - Returns this control for further customization.
   */
  public function add_dropdown(string $name, string $label, array $choices, array $options = []): ControlBuilder {

    $settings = ['choices' => $choices];

    $settings = array_merge($settings, $options);

    return $this->build_control($name, $label, 'select', $settings);

  }

  /**
   * Adds a checkbox control.
   *
   * @param string  $name                     - The name (kebab case) that will be used to reference this control in the template.
   * @param string  $label                    - The name of the label that this field will have in the editor.
   * @param array   $options                  - Optional parameters to alter the functionality of this control.
   *                                            and the values represent the label of the choice.
   * @param array   $options
   * @param string  $options['layout']        - Specify the layout of the checkbox inputs. Defaults to 'vertical' but can be 'horizontal'.
   * @param boolean $options['allow_custom']  - Whether to allow custom options to be added by the user. Defaults to false.
   * @param boolean $options['save_custom']   - Whether to allow custom options to be saved to the field choices. Defaults to false.
   * @param boolean $options['toggle']        - Adds a "Toggle all" checkbox to this list. Defaults to false.
   * @param string  $options['return_format'] - Specify how the value is formatted when loaded. Default 'value'. Choices are 'value', 'label', or 'array'.
   *
   * @return ControlBuilder - Returns thie control for further customization.
   */
  public function add_checkbox(string $name, string $label, array $choices, array $options = []): ControlBuilder {

    $settings = ['choices' => $choices];

    $settings = array_merge($settings, $options);

    return $this->build_control($name, 'checkbox', $settings);

  }

  /**
   * Adds a true/false control.
   *
   * @param string $name    - The name (kebab case) that will be used to reference this control in the template.
   * @param string $label   - The name of the label that this field will have in the editor.
   * @param string $message - An optional message to show next to the control.
   *
   * @return ControlBuilder - Returns thie control for further customization.
   */
  public function add_boolean(string $name, string $label, string $message = ''): ControlBuilder {

    return $this->build_control($name, $label, 'true_false', ['message' => $message]);

  }

  /**
   * Adds a radio select control to this Module.
   *
   * The value returned from this radio button control will be the values provided not the keys.
   *
   * @param string  $name                         - The name (kebab case) that will be used to reference this control in the template.
   * @param string  $label                        - The name of the label that this field will have in the editor.
   * @param array   $choices                      - An associative array with the keys being the choices that the user sees and the values being
   *                                                what those keys represent when chosen.
   * @param array   $options                      - Optional parameters to alter the functionality of this control.
   * @param boolean $options['other_choice']      - Allow a custom choice to be entereed via a text input. Defaults to false.
   * @param boolean $options['save_other_choice'] - Allow the custom value to be added to this field's choices. Defaults to false.
   *                                                This will not work with PHP registered fields, only DB fields.
   * @param string  $options['layout']            - Specify the layout of the checkbox inputs. Defaults to vertical.
   *                                                Choices are 'vertical' or 'horizontal'.
   *
   * @return ControlBuilder   - Returns this control for further customization.
   */
  public function add_radio_button(string $name, string $label, array $choices, array $options): ControlBuilder {

    $settings = ['choices' => $choices];

    $settings = array_merge($settings, $options);

    return $this->build_control($name, $label, 'radio', $settings);

  }

  /**
   * Adds a repeater field to this manager.
   * 
   * @param string $name  - The name (kebab case) that will be used to reference this control in the template.
   * @param string $label - The name of the label that this field will have in the editor.
   * 
   * @return Repeater - Returns the repeater control so that other controls can be added to it.
   */
  public function add_repeater(string $name, string $label) {

    $repeater = new Repeater($name, $label);

    $repeater_ref = &$repeater;

    array_push($this->controls, $repeater_ref);

    return $repeater;

  }

  /**
   * Builds the specified control, adds it to the list of controls for this module, and then returns the control so that it can be modified via 
   * adding instructions, a default value conditionals, etc.
   *
   * @return ControlBuilder - Returns the control instance so that extra properties can be added to it.
   */
  private function build_control(string $name, string $label, string $type, array $other = array()): ControlBuilder {

    $control = new ControlBuilder($name, $label, $type);

    $control_ref = &$control;

    if (count($other) > 0) {

      foreach ($other as $key => $value) $control->add_prop($key, $value);

    }

    array_push($this->controls, $control_ref);

    return $control;

  }

}

