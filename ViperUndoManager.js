/**
 * JS Class for the Viper Undo Manager.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program as the file license.txt. If not, see
 * <http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt>
 *
 * @package    CMS
 * @subpackage Editing
 * @author     Squiz Pty Ltd <products@squiz.net>
 * @copyright  2010 Squiz Pty Ltd (ACN 084 670 600)
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GPLv2
 */

function ViperUndoManager(viper)
{
    this.viper = viper;

    this.undoHistory    = [];
    this.redoHistory    = [];
    this.batchCount     = 0;
    this.batchTask      = null;
    this.historyStore   = {};
    this._activeElement = null;
    this.historyLimit   = 30;
    this._ignoreAdd     = false;
    this._maxChars      = 50;
    this._charCount     = 0;

}

ViperUndoManager.prototype = {

    /**
     * Creates a new undo task.
     *
     * @param string source  The source of the action.
     * @param string action  Type of the action.
     */
    add: function(source, action)
    {
        if (this._ignoreAdd === true) {
            return;
        }

        // If a sub element is active then do not add this change.
        if (this.viper._subElementActive === true) {
            return;
        }

        var task = {
            content: this.viper.getRawHTML()
        };

        var modify = false;
        if (action === 'text_change' && this._lastAction === action) {
            if (this._charCount < this._maxChars) {
                modify = true;
            } else {
                this._charCount = 0;
            }

            this._charCount++;
        } else {
            this._charCount = 0;
        }

        this._lastAction = action;

        // If batching is active then do not add the task to undoHistory.
        if (this.batchTask === null) {
            if (modify === true) {
                this.undoHistory[(this.undoHistory.length - 1)] = task;
            } else {
                this.undoHistory.push(task);
                if (this.undoHistory.length > this.historyLimit) {
                    this.undoHistory.shift();
                }
            }

            // Reset the redo history.
            this.redoHistory = [];
        } else {
            this.batchTask = task;
        }

        this.viper.fireCallbacks('ViperUndoManager:add');

    },

    /**
     * Undo the last task and move it from undo history to redo history.
     */
    undo: function()
    {
        if (this.viper._subElementActive === true) {
            return;
        }

        var undoLength = this.undoHistory.length;
        if (undoLength <= 1) {
            return;
        }

        // Get the current state of the content and add it to redo list.
        var currentState = {
            content: this.viper.getRawHTML()
        };

        // Add this undo to redo.
        this.redoHistory.push(currentState);

        this.undoHistory.pop();

        if (this.undoHistory.length > 0) {
            task = this.undoHistory[(this.undoHistory.length - 1)];
        }

        // Set the contents.
        this.viper.setRawHTML(task.content);

        // Fire nodesChanged event.
        this._ignoreAdd = true;
        this.viper.fireNodesChanged([this.viper.getViperElement()]);
        this.viper.fireCallbacks('ViperUndoManager:undo');
        this._ignoreAdd = false;

    },

    redo: function()
    {
        if (this.viper._subElementActive === true) {
            return;
        }

        if (this.redoHistory.length === 0) {
            return;
        }

        var task = this.redoHistory.pop();

        // Add this redo to undo.
        this.undoHistory.push(task);

        // Set the contents.
        this.viper.setRawHTML(task.content);

        // Fire nodesChanged event.
        this._ignoreAdd = true;
        this.viper.fireNodesChanged([this.viper.getViperElement()]);
        this.viper.fireCallbacks('ViperUndoManager:redo');
        this._ignoreAdd = false;

        return this.redoHistory.length;

    },

    setActiveElement: function(elem)
    {
        if (this._activeElement) {
            if (this.historyStore[this._activeElement] && this.historyStore[this._activeElement].element !== elem) {
                // There is an active history alrady, save it.
                this._saveHistory(this._activeElement);
            }
        }

        var self   = this;
        var loaded = false;
        dfx.foreach(this.historyStore, function(key) {
            if (self.historyStore[key].element === elem) {
                self._loadHistory(key);
                loaded = true;
                return false;
            }
        });

        if (loaded === false) {
            // Need to add a new historyStore.
            var key = dfx.getUniqueId();
            this.historyStore[key] = {
                undo: [],
                redo: [],
                element: elem
            };

            this._loadHistory(key);

            // Add the initial content.
            this.add();
        }

    },

    _loadHistory: function(key)
    {
        if (this.historyStore[key]) {
            this._activeElement   = key;
            this.undoHistory      = this.historyStore[key].undo;
            this.redoHistory      = this.historyStore[key].redo;
            this.batchTask            = null;
            this.batchCount       = 0;
        }

    },

    _saveHistory: function(key)
    {
        if (this.historyStore[key]) {
            this.historyStore[key].undo = this.undoHistory;
            this.historyStore[key].redo = this.redoHistory;
        }

    },

    /**
     * Starts a new batch undo block.
     * All undo tasks added while batch undo process is active will count as a
     * single undo task.
     */
    begin: function()
    {
        this.batchCount++;
        if (this.batchTask === null) {
             // Set batch to true so that add() will add the task to this.batch.
            this.batchTask = true;
        }

    },

    /**
     * Ends batch undo block.
     */
    end: function()
    {
        this.batchCount--;
        if (this.batchCount === 0 && this.batchTask !== null) {
            if (this.batchTask !== true) {
                this.undoHistory.push(this.batch);
            }

            this.batchTask = null;
        }

    },

    getUndoCount: function()
    {
        return this.undoHistory.length;

    },

    getRedoCount: function()
    {
        return this.redoHistory.length;

    }

};
