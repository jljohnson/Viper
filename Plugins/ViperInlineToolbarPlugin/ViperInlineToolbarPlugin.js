/**
 * +--------------------------------------------------------------------+
 * | This Squiz Viper file is Copyright (c) Squiz Australia Pty Ltd     |
 * | ABN 53 131 581 247                                                 |
 * +--------------------------------------------------------------------+
 * | IMPORTANT: Your use of this Software is subject to the terms of    |
 * | the Licence provided in the file licence.txt. If you cannot find   |
 * | this file please contact Squiz (www.squiz.com.au) so we may        |
 * | provide you a copy.                                                |
 * +--------------------------------------------------------------------+
 *
 */
(function(ViperUtil, ViperSelection, _) {
    function ViperInlineToolbarPlugin(viper)
    {
        this.viper                = viper;
        this._lineage             = null;
        this._lineageClicked      = false;
        this._currentLineageIndex = null;
        this._lineageItemSelected = false;
        this._margin              = 15;
        this._toolbarWidget       = null;
        this._selectionLineage    = [];
        this._toolbarElement      = null;

        this._subSections             = {};
        this._subSectionButtons       = {};
        this._subSectionActionWidgets = {};

        this._topToolbar  = null;
        this._buttons     = null;
        this._initialised = false;

    }

    Viper.PluginManager.addPlugin('ViperInlineToolbarPlugin', ViperInlineToolbarPlugin);

    ViperInlineToolbarPlugin.prototype = {

        init: function()
        {
            var self = this;

            this._topToolbar = this.viper.getPluginManager().getPlugin('ViperToolbarPlugin');
            this._initToolbar();

            this.viper.registerCallback('Viper:selectionChanged', 'ViperInlineToolbarPlugin', function() {
                if (self._toolbarWidget.isVisible() === false) {
                    self._setCurrentLineageIndex(null);
                }

            });

            this.viper.registerCallback('Viper:rightMouseDown', 'ViperInlineToolbarPlugin', function(e) {
                if (ViperUtil.isChildOf(e.target, self._toolbarElement) === false) {
                    self.hideToolbar();
                }
            });

            this.viper.registerCallback('Viper:mouseUp', 'ViperInlineToolbarPlugin', function(e) {
                if (self.viper._mouseDownEvent && ViperUtil.isChildOf(self.viper._mouseDownEvent.target, self._toolbarElement) === true) {
                    // The mouse down event happened in the Inline Toolbar so do not fire mouse up event.
                    return false;
                }
            });

            this.viper.registerCallback('Viper:getNodeSelection', 'ViperInlineToolbarPlugin', function(data) {
                var lineage         = self.getLineage();
                var currentLinIndex = self.getCurrentLineageIndex();

                var element = lineage[currentLinIndex];
                if (element && element.nodeType !== ViperUtil.TEXT_NODE) {
                    return element;
                }

                return null;
            });

        },

        setSettings: function(settings)
        {
            if (!settings) {
                return;
            }

            if (settings.buttons) {
                this._buttons = settings.buttons;

                if (this._toolbarWidget) {
                    this._toolbarWidget.orderButtons(this._buttons);
                }
            }

        },

        getToolbar: function()
        {
            return this._toolbarWidget;

        },

        _initToolbar: function()
        {
            var tools       = this.viper.Tools;
            var toolbarid   = 'ViperInlineToolbar';
            var self        = this;
            var toolbarElem = tools.createInlineToolbar(toolbarid, false, null, function(range, nodeSelection, hasActiveSection) {
                self.updateToolbar(range, nodeSelection, hasActiveSection);
            });

            this._toolbarWidget = tools.getItem(toolbarid);

            // Add lineage container to the toolbar.
            var lineage = document.createElement('ul');
            ViperUtil.addClass(lineage, 'ViperITP-lineage');
            ViperUtil.insertBefore(toolbarElem.firstChild, lineage);
            this._lineage = lineage;
            this._toolbarElement = toolbarElem;

            var toolbar = tools.getItem(toolbarid);
            this.viper.fireCallbacks('ViperInlineToolbarPlugin:initToolbar', toolbar);

            this._initialised = true;

        },

        isInitialised: function()
        {
            return this._initialised;

        },

        /**
         * Upudates the toolbar.
         *
         * This method is usually called by the Viper:selectionChanged event.
         *
         * @param {DOMRange} range The DOMRange object.
         */
        updateToolbar: function(range, nodeSelection, hasActiveSection)
        {
            if (this._lineageClicked !== true) {
                // Not selection change due to a lineage click so update the range object.
                // Note we can use cloneRange here but for whatever reason Firefox seems
                // to not do the cloning bit of cloneRange...
                this._updateOriginalSelection(range, nodeSelection);
            }

            if (this._topToolbar) {
                var bubble = this._topToolbar.getActiveBubble();
                if (bubble && bubble.getSetting('keepOpen') !== true) {
                    return false;
                }
            }

            this._lineageItemSelected = false;
            if (this._lineageClicked !== true && hasActiveSection !== true) {
                this._setCurrentLineageIndex(null);
            }

            var lineage = this._getSelectionLineage(range, nodeSelection);
            this._selectionLineage = lineage;
            if (!lineage || lineage.length === 0) {
                return false;
            }

            if (ViperUtil.isBrowser('firefox') === true
                && ViperUtil.isTag(lineage[(lineage.length - 1)], 'br') === true
            ) {
                this.hideToolbar();
                return false;
            }

            this._updateInnerContainer(range, lineage, nodeSelection);

            if (this._lineageClicked === true) {
                this._lineageClicked = false;
                return false;
            }

            var selIndex = null;
            if (hasActiveSection === true) {
                selIndex = this.getCurrentLineageIndex();
            }

            this._updateLineage(lineage, selIndex);

        },

        hideToolbar: function()
        {
            this._toolbarWidget.hide();

        },

        /**
         * Fires the updateToolbar event so that other plugins can modify the contents of the toolbar.
         *
         * @param {DOMRange} range   The DOMRange object.
         * @param {array}    lineage The lineage array.
         */
        _updateInnerContainer: function(range, lineage, nodeSelection)
        {
            if (!lineage || lineage.length === 0) {
                return;
            }

            if (this._currentLineageIndex === null || this._currentLineageIndex >= lineage.length) {
                this._setCurrentLineageIndex(lineage.length - 1);
            }

            var data = {
                range: range,
                lineage: lineage,
                current: this._currentLineageIndex,
                toolbar: this._toolbarWidget,
                nodeSelection: nodeSelection
            };

            this.viper.fireCallbacks('ViperInlineToolbarPlugin:updateToolbar', data);

        },

        /**
         * Returns a better tag name for the given DOMElement tag name.
         *
         * For example: strong -> Bold, u -> Underline.
         *
         * @param {string}  tagName The tag name of a DOMElement.
         * @param {DOMNode} tag     The source tag.
         *
         * @return {string} The readable name.
         */
        getReadableTagName: function(tagName, tag)
        {
            var readableTagName = this.viper.fireCallbacks(
                'ViperInlineToolbarPlugin:getReadableTagName',
                {
                    tagName: tagName,
                    tag: tag
                }
            );

            if (readableTagName) {
                return readableTagName;
            }

            switch (tagName) {
                case 'strong':
                    tagName = _('Bold');
                break;

                case 'u':
                    tagName = _('Underline');
                break;

                case 'em':
                case 'i':
                    tagName = _('Italic');
                break;

                case 'li':
                    tagName = _('Item');
                break;

                case 'ul':
                case 'ol':
                    tagName = _('List');
                break;

                case 'td':
                    tagName = _('Cell');
                break;

                case 'tr':
                    tagName = _('Row');
                break;

                case 'th':
                    tagName = _('Header');
                break;

                case 'a':
                    tagName = _('Link');
                break;

                case 'blockquote':
                    tagName = _('Quote');
                break;

                case 'img':
                    tagName = _('Image');
                break;

                case 'abbr':
                    tagName = _('Abbreviation');
                break;

                case 'sub':
                    tagName = _('Subscript');
                break;

                case 'sup':
                    tagName = _('Superscript');
                break;

                case 'del':
                    tagName = _('Strikethrough');
                break;

                case 'thead':
                    tagName = _('Table Header');
                break;

                case 'tfoot':
                    tagName = _('Table Footer');
                break;

                case 'tbody':
                    tagName = _('Table Body');
                break;

                default:
                    tagName = ViperUtil.ucFirst(tagName);
                break;
            }//end switch

            return tagName;

        },

        /**
         * Selects the specified lineage index.
         *
         * @param {integer} index The lineage index to select.
         */
        selectLineageItem: function(index)
        {
            var tags = ViperUtil.getTag('li', this._lineage);
            if (tags[index]) {
                ViperUtil.trigger(tags[index], 'click');
            }

        },

        getLineage: function()
        {
            this._selectionLineage = this._getSelectionLineage();
            return this._selectionLineage;

        },

        getCurrentLineageIndex: function()
        {
            if (this._currentLineageIndex !== null && this.viper.getViperRange().collapsed === false) {
                return this._currentLineageIndex;
            } else if (this._selectionLineage.length === 0) {
                 return 0;
            } else {
                return (this._selectionLineage.length - 1)
            }

        },

        remove: function()
        {
             this.viper.Tools.removeItem('ViperInlineToolbar');

        },

        /**
         * Updates the contents of the lineage container.
         *
         * @param {array} lineage The lineage array.
         */
        _updateLineage: function(lineage, selIndex)
        {
            // Remove the contents of the lineage container.
            ViperUtil.empty(this._lineage);

            var viper    = this.viper;
            var c        = lineage.length;
            var self     = this;
            var linElems = [];
            selIndex     = selIndex || null;

            // Create lineage items.
            for (var i = 0; i < c; i++) {
                if (!lineage[i].tagName) {
                    continue;
                }

                var tagName = lineage[i].tagName.toLowerCase();
                var parent  = document.createElement('li');
                ViperUtil.addClass(parent, 'ViperITP-lineageItem');

                if ((i === (c - 1) && selIndex === null) || (selIndex !== null && i === selIndex)) {
                    ViperUtil.addClass(parent, 'Viper-selected');
                }

                ViperUtil.setHtml(parent, this.getReadableTagName(tagName, lineage[i]));
                this._lineage.appendChild(parent);
                linElems.push(parent);

                (function(clickElem, selectionElem, index) {
                    // When clicked set the user selection to the selected element.
                    ViperUtil.addEvent(clickElem, 'click.ViperInlineToolbarPlugin', function(e) {
                        self.viper.fireCallbacks('ViperInlineToolbarPlugin:lineageClicked');

                        // We set the _lineageClicked to true here so that when the
                        // fireSelectionChanged is called we do not update the lineage again.
                        self._lineageClicked = true;
                        self._setCurrentLineageIndex(index);

                        ViperUtil.removeClass(linElems, 'Viper-selected');
                        ViperUtil.addClass(clickElem, 'Viper-selected');

                        if (ViperUtil.isBrowser('msie') === true) {
                            // IE changes the range when the mouse is released on an element
                            // that is not part of viper causing Viper to lose focus..
                            // Use time out to set the range back in to Viper..
                            self.viper.focus();
                            setTimeout(function() {
                                self._selectNode(selectionElem);
                            }, 30);
                        } else {
                            self._selectNode(selectionElem);
                        }

                        ViperUtil.preventDefault(e);

                        return false;
                    });
                }) (parent, lineage[i], i);
            }//end for

            if (this._originalRange.collapsed === true
                || (lineage[(lineage.length - 1)].nodeType !== ViperUtil.TEXT_NODE)
            ) {
                // No need to add the 'Selection' item as its collapsed or a node is selected.
                return;
            }

            // Add the original user selection to the lineage.
            var parent = document.createElement('li');
            ViperUtil.addClass(parent, 'ViperITP-lineageItem Viper-selected');
            ViperUtil.setHtml(parent, _('Selection'));
            linElems.push(parent);
            this._lineage.appendChild(parent);

            ViperUtil.addEvent(parent, 'click.ViperInlineToolbarPlugin', function(e) {
                self.viper.fireCallbacks('ViperInlineToolbarPlugin:lineageClicked');

                // When clicked set the selection to the original selection.
                self._lineageClicked = true;

                var prevIndex = self._currentLineageIndex;
                self._setCurrentLineageIndex(lineage.length - 1);

                ViperUtil.removeClass(linElems, 'Viper-selected');
                ViperUtil.addClass(parent, 'Viper-selected');

                if (ViperUtil.isBrowser('msie') === true) {
                    // IE changes the range when the mouse is released on an element
                    // that is not part of viper causing Viper to lose focus..
                    // Use time out to set the range back in to Viper..
                    setTimeout(function() {
                        self._selectPreviousRange(lineage, prevIndex);
                    }, 50);
                } else {
                    self._selectPreviousRange(lineage, prevIndex);
                }

                ViperUtil.preventDefault(e);
                return false;
            });

        },

        _selectNode: function(node)
        {
            this.viper.focus();

            var range = this.viper.getViperRange();

            if (this._lineageItemSelected === false) {
                // Update original selection. We update it here incase the selectionHighlight
                // method changed the DOM structure (e.g. normalised textnodes), when
                // Viper is focused update the 'selection' range.
                this._updateOriginalSelection(range);
            }

            // Set the range.
            ViperSelection.removeAllRanges();
            range = this.viper.getViperRange();

            var first = range._getFirstSelectableChild(node);
            var last  = range._getLastSelectableChild(node);
            if (!first || !last) {
                range.selectNode(node);
            } else {
                range.setStart(first, 0);
                range.setEnd(last, last.data.length);
            }

            ViperSelection.addRange(range);

            this.viper.fireCallbacks('ViperInlineToolbarPlugin:lineageItemSelected', node);

            this._toolbarWidget.closeActiveSubsection(true);
            this._toolbarWidget.setVerticalUpdateOnly(true);
            this.viper.fireSelectionChanged(range, true);
            this._toolbarWidget.setVerticalUpdateOnly(false);
            this._lineageItemSelected = true;

        },

        _selectPreviousRange: function(lineage, prevIndex)
        {
            this.viper.focus();

            ViperSelection.removeAllRanges();
            var range = this.viper.getViperRange();

            if (this._originalRange.nodeType) {
                range.selectNode(this._originalRange);
            } else {
                range.setStart(this._originalRange.startContainer, this._originalRange.startOffset);
                range.setEnd(this._originalRange.endContainer, this._originalRange.endOffset);
            }

            if (ViperUtil.isBrowser('msie') === true) {
                // Another timing issue  with IE.
                setTimeout(function() {
                    ViperSelection.addRange(range);
                }, 10);
            } else {
                ViperSelection.addRange(range);
            }

            this._toolbarWidget.closeActiveSubsection(true);
            this._toolbarWidget.setVerticalUpdateOnly(true);
            this.viper.fireSelectionChanged(range, true);
            this._toolbarWidget.setVerticalUpdateOnly(false);
            this._updateOriginalSelection(range);

        },

        _setCurrentLineageIndex: function(index)
        {
            this._currentLineageIndex = index;

        },

        _updateOriginalSelection: function(range, nodeSelection)
        {
            if (nodeSelection) {
                this._originalRange = nodeSelection;
                return;
            }

            this._originalRange = {
                startContainer: range.startContainer,
                endContainer: range.endContainer,
                startOffset: range.startOffset,
                endOffset: range.endOffset,
                collapsed: range.collapsed
            };

        },

        /**
         * Returns the selection's parent elements.
         *
         * @param {DOMRange} range The DOMRange object.
         *
         * @return {array} Array of DOMElements.
         */
        _getSelectionLineage: function(range, nodeSelection)
        {
            range             = range || this.viper.getViperRange();
            var lineage       = [];
            var parent        = null;

            var nodeSelection = nodeSelection || range.getNodeSelection(range, true);
            var viperElement  = this.viper.getViperElement();

            if (nodeSelection && viperElement !== nodeSelection) {
                parent = nodeSelection;
            } else {
                var startNode = range.getStartNode();
                if (!startNode) {
                    return lineage;
                } else if (startNode.nodeType == ViperUtil.TEXT_NODE
                    && (startNode.data.length === 0 || ViperUtil.isBlank(ViperUtil.trim(startNode.data)) === true)
                    && startNode.nextSibling
                    && startNode.nextSibling.nodeType === ViperUtil.TEXT_NODE
                ) {
                    // The startNode is an empty textnode, most likely due to node splitting
                    // if the next node is a text node use that instead.
                    startNode = startNode.nextSibling;
                }

                var endNode = range.getEndNode();
                if (startNode.nodeType !== ViperUtil.TEXT_NODE || ViperUtil.isBlank(startNode.data) !== true) {
                    if (startNode.nodeType !== ViperUtil.TEXT_NODE && startNode !== range.getEndNode()) {
                        if (endNode !== viperElement) {
                            lineage.push(range.getEndNode());
                        } else {
                            var firstSelectable = range._getFirstSelectableChild(startNode);
                            lineage.push(firstSelectable);
                        }
                    } else {
                        if (ViperUtil.isBrowser('edge') === true
                            && startNode.nodeType === ViperUtil.TEXT_NODE
                            && range.startOffset === startNode.data.length
                            && range.collapsed === false
                            && startNode.nextSibling
                            && startNode.nextSibling.nodeType === ViperUtil.ELEMENT_NODE
                            && ViperUtil.isStubElement(startNode.nextSibling) === false
                        ) {
                            // Handle <p>text[<strong>text] text</strong></p> -> <p>text<strong>[text] text</strong></p>.
                            var firstSelectable = range._getFirstSelectableChild(startNode.nextSibling);
                            if (firstSelectable) {
                                startNode = firstSelectable;
                            }
                        }

                        lineage.push(startNode);

                        if (ViperUtil.isBrowser('msie') === true
                            && startNode.nodeType === ViperUtil.TEXT_NODE
                            && !range.getEndNode()
                            && range.endContainer.nodeType === ViperUtil.ELEMENT_NODE
                            && range.endOffset >= range.endContainer.childNodes.length
                            && ViperUtil.isChildOf(startNode, range.endContainer.childNodes[(range.endContainer.childNodes.length - 1)]) === true
                        ) {
                            // When an inline tag is the last element in a block element and only last few characters of the
                            // tag is selected IE thinks this is not inside the tag but in common parent.
                            // Add the parent of startNode to lineage here.
                            lineage.push(range.endContainer.childNodes[(range.endContainer.childNodes.length - 1)]);
                        } else if ((ViperUtil.isBrowser('msie') === true || ViperUtil.isBrowser('edge') === true )
                            && range.startOffset === 0
                            && range.collapsed === true
                            && startNode.nodeType === ViperUtil.TEXT_NODE
                            && startNode.previousSibling
                            && startNode.previousSibling.nodeType === ViperUtil.ELEMENT_NODE
                            && ViperUtil.isStubElement(startNode.previousSibling) === false
                        ) {
                            // Handle case: <strong><a>text</a></strong>*more text.
                            // Lineage should be showing P > strong > a.
                            // Remove the previous text node.
                            lineage.pop();

                            // Get the last selectable child of the previous element.
                            var lastSelectable = range._getLastSelectableChild(startNode.previousSibling);
                            var parents        = ViperUtil.getParents(lastSelectable);
                            lineage.push(lastSelectable);
                            for (var i = 0; i < parents.length; i++) {
                                lineage.push(parents[i])
                            }

                            lineage = lineage.reverse();
                            return lineage;
                        } else if (range.startContainer.nodeType === ViperUtil.TEXT_NODE
                            && range.endContainer.nodeType === ViperUtil.TEXT_NODE
                            && range.endOffset === 0
                            && range.getPreviousContainer(range.endContainer) === range.startContainer
                            && range.endContainer.previousSibling !== range.startContainer
                        ) {
                            lineage.push(range.startContainer.parentNode);
                        }
                    }
                }
            }

            var viperElement = this.viper.getViperElement();

            if (parent === null) {
                var endNode = range.getEndNode();
                if (startNode && startNode === endNode) {
                    parent = startNode.parentNode;
                } else if (range.endContainer === endNode
                    && endNode.childNodes.length === range.endOffset
                    && startNode.parentNode === endNode.childNodes[endNode.childNodes.length - 1]
                ) {
                    parent = startNode.parentNode;
                } else {
                    parent = range.getCommonElement();
                    if (this.viper.isOutOfBounds(parent) === true) {
                        parent = viperElement;
                    }
                }
            }

            if (parent === viperElement) {
                if (ViperUtil.isBrowser('msie') === true
                    && range.startContainer.nodeType === ViperUtil.ELEMENT_NODE
                    && range.startOffset >= range.startContainer.childNodes.length
                    && ViperUtil.isTag(range.startContainer.childNodes[range.startOffset - 1], 'a') === true
                ) {
                    lineage.push(range.startContainer.childNodes[range.startOffset - 1]);
                }

                return lineage;
            }

            if (parent) {
                lineage.push(parent);

                parent = parent.parentNode;

                while (parent && parent !== viperElement) {
                    if (parent === document) {
                        // Couldn't find the editable element (possibly changed or disabled).
                        return [];
                    }

                    lineage.push(parent);
                    parent = parent.parentNode;
                }
            }

            lineage = lineage.reverse();

            if (ViperUtil.isBrowser('msie') === true
                && range.collapsed === true
                && range.startOffset === 0
                && range.startContainer.previousSibling
                && ViperUtil.isTag(range.startContainer.previousSibling, 'a') === true
            ) {
                lineage.push(range.startContainer.previousSibling);
            } else if (ViperUtil.isBrowser('msie', '<9') === true
                && range.startContainer.nodeType === ViperUtil.ELEMENT_NODE
                && range.startOffset >= range.startContainer.childNodes.length
                && ViperUtil.isTag(range.startContainer.childNodes[range.startOffset - 1], 'a') === true
            ) {
                lineage.push(range.startContainer.childNodes[range.startOffset - 1]);
            }

            return lineage;

        }

    };
})(Viper.Util, Viper.Selection, Viper._);
