function ViperTools(viper)
{
    this.viper = viper;

    this._items = {};

    var self = this;
    this.viper.registerCallback('Viper:mouseUp', 'ViperTools', function(e) {
        if (self._preventMouseUp === true) {
            self._preventMouseUp = false;
            return false;
        }
    });

}

ViperTools.prototype = {

    addItem: function(id, item)
    {
        this._items[id] = item;

    },

    getItem: function(id)
    {
        return this._items[id];

    },


    /**
     * If true then the next mouse up event will not fire.
     */
    _preventMouseUp: false,

    createRow: function(id, customClass)
    {
        var elem = document.createElement('div');
        dfx.addClass(elem, 'subSectionRow');

        if (customClass) {
            dfx.addClass(elem, customClass);
        }

        this.addItem(id, {
            type: 'row',
            element: elem
        });

        return elem;

    },

    /**
     * Creates a button group.
     *
     * @param {string} customClass Custom class to apply to the group.
     *
     * @return {DOMElement} The button group element.
     */
    createButtonGroup: function(id, customClass)
    {
        var group = document.createElement('div');
        dfx.addClass(group, 'Viper-buttonGroup');

        if (customClass) {
            dfx.addClass(group, customClass);
        }

        this.addItem(id, {
            type: 'buttonGroup',
            element: group,
            buttons: []
        });

        return group;

    },


    /**
     * Creates a toolbar button.
     *
     * @param {string}     content        The content of the button.
     * @param {string}     titleAttr      The title attribute for the button.
     * @param {string}     customClass    Class to add to the button for extra styling.
     * @param {function}   clickAction    The function to call when the button is clicked.
     *                                    Note that this action is ignored if the
     *                                    subSection param is specified. Clicking will
     *                                    then toggle the sub section visibility.
     * @param {boolean}    disabled       True if the button is disabled.
     * @param {string}     isActive       True if the button is active.
     *
     * @return {DOMElement} The new button element.
     */
    createButton: function(id, content, titleAttr, customClass, clickAction, disabled, isActive)
    {
        if (!content) {
            if (customClass) {
                // Must be an icon button.
                content = '<span class="buttonIcon ' + customClass + '"></span>';
            } else {
                content = '';
            }

            content += '&nbsp;';
        }

        var button = document.createElement('div');

        if (titleAttr) {
            if (disabled === true) {
                titleAttr = titleAttr + ' [Not available]';
            }

            button.setAttribute('title', titleAttr);
        }

        dfx.setHtml(button, content);
        dfx.addClass(button, 'Viper-button');

        if (disabled === true) {
            dfx.addClass(button, 'disabled');
        }

        if (customClass) {
            dfx.addClass(button, customClass);
        }

        var preventMouseUp = false;
        var self           = this;
        if (clickAction) {
            dfx.addEvent(button, 'mousedown.Viper', function(e) {
                self._preventMouseUp = true;
                dfx.preventDefault(e);
                if (dfx.hasClass(button, 'disabled') === true) {
                    return false;
                }

                return clickAction.call(this, button);
            });
        }//end if

        dfx.addEvent(button, 'mouseup.Viper', function(e) {
            self._preventMouseUp = false;
            dfx.preventDefault(e);
            return false;
        });

        if (isActive === true) {
            dfx.addClass(button, 'active');
        }

        this.addItem(id, {
            type: 'button',
            element: button,
            setIconClass: function(iconClass)
            {
                var btnIconElem = dfx.getClass('buttonIcon', button);
                if (btnIconElem.length === 0) {
                    btnIconElem = document.createElement('span');
                    dfx.addClass(btnIconElem, 'buttonIcon');
                    dfx.insertBefore(button.firstChild, btnIconElem);
                } else {
                    btnIconElem = btnIconElem[0];
                    btnIconElem.className = 'buttonIcon';
                }

                dfx.addClass(btnIconElem, iconClass);
            }
        });

        return button;

    },

    addButtonToGroup: function(buttonid, groupid)
    {
        var button = this.getItem(buttonid);
        var group  = this.getItem(groupid);
        if (!button || !group || button.type !== 'button' || group.type !== 'buttonGroup') {
            throw new Error('Invalid argument for ViperTools.addButtonToGroup(\'' + buttonid + '\', \'' + groupid + '\')');
            return;
        }

        group.element.appendChild(button.element);
        group.buttons.push(buttonid);

    },

    setButtonInactive: function(buttonid)
    {
        dfx.removeClass(this.getItem(buttonid).element, 'active');

    },

    setButtonActive: function(buttonid)
    {
        dfx.addClass(this.getItem(buttonid).element, 'active');
        dfx.removeClass(this.getItem(buttonid).element, 'disabled');

    },

    enableButton: function(buttonid)
    {
        dfx.removeClass(this.getItem(buttonid).element, 'disabled');

    },

    disableButton: function(buttonid)
    {
        dfx.addClass(this.getItem(buttonid).element, 'disabled');

    },

    /**
     * Creates a textbox.
     *
     * @param {string}   value      The initial value of the textbox.
     * @param {string}   label      The label of the textbox.
     * @param {boolean}  required   True if this field is required.
     * @param {boolean}  expandable If true then the textbox will expand when focused.
     *
     * @return {DOMNode} If label specified the label element else the textbox element.
     */
    createTextbox: function(id, label, value, action, required, expandable, desc, events)
    {
        label = label || '&nbsp;';
        value = value || '';

        var textBox = document.createElement('div');
        dfx.addClass(textBox, 'Viper-textbox');

        if (required === true && !value) {
            dfx.addClass(textBox, 'required');
        }

        var labelEl = document.createElement('label');
        dfx.addClass(labelEl, 'Viper-textbox-label');
        textBox.appendChild(labelEl);

        var main = document.createElement('div');
        dfx.addClass(main, 'Viper-textbox-main');
        labelEl.appendChild(main);

        var title = document.createElement('span');
        dfx.addClass(title, 'Viper-textbox-title');
        dfx.setHtml(title, label);

        // Wrap the element in a generic class so the width calculation is correct
        // for the font size.
        var tmp = document.createElement('div');
        dfx.addClass(tmp, 'ViperITP');

        if (navigator.userAgent.match(/iPad/i) !== null) {
            dfx.addClass(tmp, 'device-ipad');
        }

        dfx.setStyle(tmp, 'display', 'block');
        tmp.appendChild(title);
        document.body.appendChild(tmp);
        var width = (dfx.getElementWidth(title) + 10);
        document.body.removeChild(tmp);
        main.appendChild(title);

        var input   = document.createElement('input');
        input.type  = 'text';
        input.value = value;
        dfx.addClass(input, 'Viper-textbox-input');
        dfx.setStyle(input, 'padding-left', width + 'px');
        main.appendChild(input);

        if (desc) {
            // Description.
            var descEl = document.createElement('span');
            dfx.addClass(descEl, 'Viper-textbox-desc');
            dfx.setHtml(descEl, desc);
            textBox.appendChild(descEl);
        }

        var self = this;
        dfx.addEvent(input, 'focus', function() {
            self.viper.highlightSelection();
        });

        var _addActionButton = function() {
            var actionIcon = document.createElement('span');
            dfx.addClass(actionIcon, 'Viper-textbox-action');
            main.appendChild(actionIcon);
            dfx.addEvent(actionIcon, 'click', function() {
                if (dfx.hasClass(textBox, 'actionRevert') === true) {
                    input.value = value;
                    dfx.removeClass(textBox, 'actionRevert');
                    dfx.addClass(textBox, 'actionClear');
                } else if (dfx.hasClass(textBox, 'actionClear') === true) {
                    input.value = '';
                    dfx.removeClass(textBox, 'actionClear');
                    if (required === true) {
                        dfx.addClass(textBox, 'required');
                    }
                }
            });

            return actionIcon;
        };

        if (value !== '') {
            var actionIcon = _addActionButton();
            actionIcon.setAttribute('title', 'Clear this value');
            dfx.addClass(textBox, 'actionClear');
        }

        dfx.addEvent(input, 'keyup', function(e) {
            var actionIcon = dfx.getClass('Viper-textbox-action', main);
            if (actionIcon.length === 0) {
                actionIcon = _addActionButton();
            } else {
                actionIcon = actionIcon[0];
            }

            dfx.removeClass(textBox, 'actionClear');
            dfx.removeClass(textBox, 'actionRevert');

            if (input.value !== value && value !== '') {
                // Show the revert icon.
                actionIcon.setAttribute('title', 'Revert to original value');
                dfx.addClass(textBox, 'actionRevert');
                dfx.removeClass(textBox, 'required');
            } else if (input.value !== '') {
                actionIcon.setAttribute('title', 'Clear this value');
                dfx.addClass(textBox, 'actionClear');
                dfx.removeClass(textBox, 'required');
            } else {
                dfx.remove(actionIcon);
                if (required === true) {
                    dfx.addClass(textBox, 'required');
                }
            }

            // Action.
            if (action && e.which === 13) {
                self.viper.focus();
                action.call(input, input.value);
            }
        });

        if (events) {
            for (var eventType in events) {
                dfx.addEvent(input, eventType, events[eventType]);
            }
        }

        this.addItem(id, {
            type: 'textbox',
            element: textBox,
            input: input,
            label: labelEl,
            getValue: function() {
                return input.value;
            },
            setValue: function(value) {
                input.value = value;
            }
        });

        return textBox;

    },

    setFieldEvent: function(itemid, eventType, event)
    {
        var item = this.getItem(itemid);
        if (!item || !item.input) {
            return;
        }

        dfx.addEvent(item.input, eventType, event);

    },

    /**
     * Sets the list of errors of the specified item.
     *
     * If no errors specified then all exisiting errors will be cleared.
     *
     * @param {string} itemid The item id.
     * @param {array}  errors List of errors.
     */
    setFieldErrors: function(itemid, errors)
    {
        var item = this.getItem(itemid);
        if (!item || !item.input) {
            return;
        }

        errors = errors || [];

        var errorCount = errors.length;

        var msgsElement = dfx.getClass('Viper-' + item.type + '-messages', item.element);
        if (msgsElement.length === 0) {
            if (errorCount === 0) {
                return;
            }

            msgsElement = document.createElement('div');
            dfx.addClass(msgsElement, 'Viper-textbox-messages');
            item.label.appendChild(msgsElement);
        } else {
            msgsElement = msgsElement[0];
            if (errorCount === 0) {
                dfx.remove(msgsElement);
                return;
            }

            dfx.empty(msgsElement);
        }

        var content = '';
        for (var i = 0; i < errorCount; i++) {
            content += '<span class="Viper-textbox-error">' + errors[i] + '</span>';
        }

        dfx.setHtml(msgsElement, content);

    },

    /**
     * Creates a checkbox.
     *
     * @param {string}  id      The id of the item.
     * @param {string}  label   The label for the checkbox.
     * @param {boolean} checked True if checked by default.
     *
     * @return {DOMElement} The checkbox element.
     */
    createCheckbox: function(id, label, checked, changeCallback)
    {
        var labelElem = document.createElement('label');
        dfx.addClass(labelElem, 'Viper-checkbox');

        var checkbox  = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.checked = checked || false;

        var span = document.createElement('span');
        dfx.addClass(span, 'Viper-checkbox-title');
        dfx.setHtml(span, label);

        labelElem.appendChild(span);
        labelElem.appendChild(checkbox);

        var self = this;

        if (changeCallback) {
            dfx.addEvent(checkbox, 'click', function() {
                self.viper.focus();
                changeCallback.call(this, checkbox.checked);
            });
        }

        this.addItem(id, {
            type: 'checkbox',
            element: labelElem,
            input: checkbox,
            getValue: function() {
                return checkbox.checked;
            },
            setValue: function(checked) {
                checkbox.checked = checked;
                if (changeCallback) {
                    self.viper.focus();
                    changeCallback.call(this, checked, true);
                }
            }
        });

        return labelElem;

    },


    /**
     * Creates a radio button.
     *
     * @parma {string}  name    The name of the group the radio button belongs to.
     * @param {string}  value   The value of the radio button.
     * @param {string}  label   The label for the radio button.
     * @param {boolean} checked True if checked by default.
     *
     * @return {DOMElement} The checkbox element.
     */
    createRadiobutton: function(name, value, label, checked)
    {
        var labelElem = document.createElement('label');
        dfx.addClass(labelElem, 'Viper-radiobtn-label');

        var radio     = document.createElement('input');
        radio.type    = 'radio';
        radio.name    = name;
        radio.value   = value;
        radio.checked = checked || false;

        dfx.addClass(radio, 'Viper-radiobtn');

        var span = document.createElement('span');
        dfx.addClass(span, 'Viper-radio-text');
        dfx.setHtml(span, label);

        labelElem.appendChild(radio);
        labelElem.appendChild(span);

        return labelElem;

    },

    createPopup: function(id, title, topContent, midContent, bottomContent, customClass, draggable, resizable, openCallback, closeCallback, resizeCallback)
    {
        title = title || '&nbsp;';

        var self = this;

        var main = document.createElement('div');
        dfx.addClass(main, 'Viper-popup themeDark');

        if (customClass) {
            dfx.addClass(main, customClass);
        }

        var header = document.createElement('div');
        dfx.addClass(header, 'Viper-popup-header');

        if (draggable !== false) {
            var dragIcon = document.createElement('div');
            dfx.addClass(dragIcon, 'Viper-popup-dragIcon');
            header.appendChild(dragIcon);

            dfxjQuery(main).draggable({
                handle: header
            });
        }

        header.appendChild(document.createTextNode(title));

        var closeIcon = document.createElement('div');
        dfx.addClass(closeIcon, 'Viper-popup-closeIcon');
        header.appendChild(closeIcon);
        dfx.addEvent(closeIcon, 'mousedown', function() {
            self.closePopup(id, 'closeIcon');
        });

        var fullScreen  = false;

        var originalOpenCallback = openCallback;
        openCallback = function() {
            fullScreen = false;
            if (originalOpenCallback) {
                return originalOpenCallback.call(this);
            }
        }

        var currentSize = null;
        dfx.addEvent(header, 'safedblclick', function() {}, function() {
            if (fullScreen !== true) {
                fullScreen = true;
                var mainCoords = dfx.getElementCoords(main);
                currentSize = {
                    width: dfx.getElementWidth(midContent),
                    height: dfx.getElementHeight(midContent),
                    left: mainCoords.x,
                    top: mainCoords.y
                };
                dfx.getElementDimensions(midContent);
                var topHeight     = dfx.getElementHeight(header);
                var bottomHeight  = dfx.getElementHeight(bottomContent);
                var toolbarHeight = 35;

                var windowDim = dfx.getWindowDimensions();
                dfx.setStyle(main, 'left', 0);
                dfx.setStyle(main, 'top', toolbarHeight + 'px');
                dfx.setStyle(main, 'margin-left', 0);
                dfx.setStyle(main, 'margin-top', 0);
                dfx.setStyle(midContent, 'width', windowDim.width - 20 + 'px');
                dfx.setStyle(midContent, 'height', windowDim.height - toolbarHeight - bottomHeight - topHeight - 10 + 'px');
                if (resizeCallback) {
                    resizeCallback.call(this);
                }
            } else {
                fullScreen = false;
                dfx.setStyle(main, 'left', currentSize.left + 'px');
                dfx.setStyle(main, 'top', currentSize.top + 'px');
                dfx.setStyle(midContent, 'width', currentSize.width + 'px');
                dfx.setStyle(midContent, 'height', currentSize.height + 'px');
                if (resizeCallback) {
                    resizeCallback.call(this);
                }
            }
        });

        main.appendChild(header);

        if (topContent) {
            dfx.addClass(topContent, 'Viper-popup-top');
            main.appendChild(topContent);
        }

        dfx.addClass(midContent, 'Viper-popup-content');
        main.appendChild(midContent);

        if (bottomContent) {
            dfx.addClass(bottomContent, 'Viper-popup-bottom');
            main.appendChild(bottomContent);
        }

        if (resizable !== false) {
            var resizeElements = function(ui) {
                dfx.setStyle(midContent, 'width', ui.size.width + 'px');
                dfx.setStyle(midContent, 'height', ui.size.height + 'px');
            };

            dfxjQuery(midContent).resizable({
                handles: 'se',
                resize: function(e, ui) {
                    if (resizeCallback) {
                        resizeCallback.call(this, e, ui);
                    }
                },
                stop: function(e, ui) {
                    if (resizeCallback) {
                        resizeCallback.call(this, e, ui);
                    }
                }
            });

        }

        this.addItem(id, {
            type: 'popup',
            element: main,
            topContent: topContent,
            midContent: midContent,
            bottomContent: bottomContent,
            openCallback: openCallback,
            closeCallback: closeCallback,
            showTop: function() {
                dfx.blindDown(topContent);
            },
            hideTop: function() {
                dfx.blindUp(topContent);
            }
        });

        return main;

    },

    openPopup: function(id, width, height)
    {
        var popup        = this.getItem(id);
        var contentElem  = popup.midContent;
        var popupElement = popup.element;

        if (width) {
            dfx.setStyle(contentElem, 'width', width + 'px');
        }

        if (height) {
            dfx.setStyle(contentElem, 'height', height + 'px');
        }

        dfx.setStyle(popupElement, 'left', '-9999px');
        dfx.setStyle(popupElement, 'top', '-9999px');
        dfx.setStyle(popupElement, 'visibility', 'hidden');
        document.body.appendChild(popupElement);

        // Set the pos to be the middle of the screen
        //var windowDim  = dfx.getWindowDimensions();
        var elementDim = dfx.getBoundingRectangle(popupElement);

        dfx.setStyle(popupElement, 'margin-left', (((elementDim.x2 - elementDim.x1) / 2) * -1) + 'px');
        dfx.setStyle(popupElement, 'margin-top', (((elementDim.y2 - elementDim.y1) / 2) * -1) + 'px');

        dfx.setStyle(popupElement, 'left', '50%');
        dfx.setStyle(popupElement, 'top', '50%');

        if (popup.openCallback) {
            if (popup.openCallback.call(this) === false) {
                // Do not open.
                document.body.removeChild(popupElement);
                return;
            }
        }

        dfx.setStyle(popupElement, 'visibility', 'visible');

    },

    closePopup: function(id, closer)
    {
        var popup = this.getItem(id);
        if (popup.closeCallback) {
            if (popup.closeCallback.call(this, closer) === false) {
                // Do not close.
                return;
            }
        }

        document.body.removeChild(popup.element);

    },

    scaleElement: function(element)
    {
        var zoom = (document.documentElement.clientWidth / window.innerWidth);
        if (zoom === 1) {
            var scale = 1;
            dfx.setStyle(element, '-webkit-transform', 'scale(' + scale + ', ' + scale + ')');
            dfx.setStyle(element, '-moz-transform', 'scale(' + scale + ', ' + scale + ')');
            return;
        }

        var scale = ((1 / zoom) + 0.2);

        dfx.setStyle(element, '-webkit-transform', 'scale(' + scale + ', ' + scale + ')');
        dfx.setStyle(element, '-moz-transform', 'scale(' + scale + ', ' + scale + ')');

        return scale;

    }

};
