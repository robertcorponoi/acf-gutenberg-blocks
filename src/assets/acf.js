'use strict'

let count = 0;

/**
 * Create a new ACF Model that control interactions with ACF components within the Gutenberg editor.
 */
const instance = new acf.Model({

  /**
   * Actions are defined by the ACF JS API.
   * 
   * The 'append' action occurs whenever an ACF block is added or removed from the DOM.
   * The 'ready' action occurs whenever the page has loaded and is ready.
   */
  actions: {
    'append': 'onAppend'
  },

  /**
   * Events are defined by specifying the event to listen for (usually change) followed by the element to watch for 
   * the specified event.
   *  
   * The value of the block is the method to run when the event occurs.
   */
  events: {
    'change .editor-block-list__block-edit': 'addBlockHeader',
  },

  /**
   * Adds a header to the block with the block type followed by a snippet of text input that helps to identify this 
   * block from other blocks.
   * 
   * @param {Event} ev The event data of the new block.
   * @param {Object} $el An object containing block data including the structure used to create it.
   */
  addBlockHeader(ev, $el) {
    
    ev.preventDefault();

    const ctx = $el.context;

    const headerValue = ctx.querySelector('input[type="text"]').value;

    const headerSubId = ctx.querySelector('.lf-acf-header span');
    headerSubId.textContent = headerValue;

  },

  /**
   * Fires whenever a new ACF block is added to the editor.
   * 
   * Here we handle finding the name of the block, which is not directly given by ACF but there is a data- attribute somewhere 
   * that contains it so we find that and use that to create the block's header.
   * 
   * @param {Array<Object>} $el An array containing the editor block's information.
   */
  onAppend($el) {

    $el = $el[0];

    if (!$el.classList.contains('acf-block-fields')) return;

    const type = getByDataAttribute($el, 'type');

    const divToToggle = getByDataAttribute($el, 'block');

    const header = document.createElement('div');
    header.classList.add('lf-acf-header');

    const title = document.createElement('h4');
    title.textContent = type.el.dataset.type.replace('acf/group', '').split('-').join(' ').replace(/(^|\s)([a-z])/g, function (m, p1, p2) { return p1 + p2.toUpperCase(); });

    const headerSubInfo = type.el.querySelector('input[type="text"]');

    if (headerSubInfo) {

      const span = document.createElement('span');
      span.textContent += headerSubInfo.value;
      title.appendChild(span);

    }

    count++;

    const toggle = document.createElement('i');
    toggle.id = `lf-icon-${count}`;
    toggle.classList.add('fa', 'fa-chevron-down');

    header.appendChild(title);
    header.appendChild(toggle);
    type.prevEl.prepend(header);

    header.onclick = () => toggleComponent(toggle.id, divToToggle.el);

    toggleComponent(toggle.id, divToToggle.el);

  }

});

/**
 * Find out whether a given element contains the specified data attribute.
 * 
 * @private
 * 
 * @param {HTMLElement} el The HTML element to check for the data attribute.
 * @param {string} attr The data attribute to check if exists.
 * 
 * @returns {boolean} Returns true if the element contains the given data attribute or false otherwise. 
 */
function hasDataAttribute(el, attr) {

  if (el.dataset[attr]) return true;

  return false;

}

/**
 * Find the first parent element that contains the given data attribute and also the child element that led to that parent element.
 * 
 * This also returns the child because if a parent has multiple children it is not likely to find which child led to that parent.
 * 
 * @param {HTMLElement} el The HTML element to search through
 * @param {string} attr The data attribute to check each element for.
 * 
 * @returns {Object} Returns the HTMLElement that contains the data attribute and the child that led to that element.
 */
function getByDataAttribute(el, attr) {

  let prevEl;

  while (!hasDataAttribute(el, attr)) {

    prevEl = el;
    
    el = el.parentElement;

  }

  return { el: el, prevEl: prevEl };

}

/**
 * Toggles whether the block information (minus the header) is visible or not.
 * 
 * Currently this method handles the block visibility and also the changing of the chevron on on the header.
 * 
 * @param {string} toggleId The id of the icon to change. 
 * @param {HTMLElement} el The element to toggle visibility of.
 */
function toggleComponent(toggleId, el) {

  const icon = document.getElementById(toggleId);

  icon.classList.toggle('fa-chevron-down');
  icon.classList.toggle('fa-chevron-up');

  el.classList.toggle('hidden');

  icon.closest('.lf-acf-header').classList.toggle('block-closed');

}
