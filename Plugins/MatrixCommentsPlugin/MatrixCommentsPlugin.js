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

function MatrixCommentsPlugin(viper)
{
    this.viper       = viper;

    // stores all comments data
    this._comments = {};
    // stores comments position data
    this._commentsPositions = [];



    // dynamic variables
	this._commentTargetElement   = null;
	this._commentId = 0;
    this._currentUserId = 0;
    this._currentUserFirstName = '';
    this._currentUserLastName = '';
    this._currentUserUsername = '';
    this._currentViewingCommentMark = null;
    this._commentColor = 0;
    this._containerId = 0;
    this._bodycopyId = 0;
    this._bodycopyContainer = null;


    // constants
    this._inEditPlus = true;
    this._availableCommentColor = ['#0583db', '#ad41bd', '#f17828', '#e8b01f', '#ea4c8b'];
    this._commentMarkVerticalSpace = 22;


    this.initInlineToolbar();
}

MatrixCommentsPlugin.prototype = {

    init: function()
    {
        var self = this;

        // get current user id
        if (typeof EasyEditAssetManager == 'undefined') {
            self._inEditPlus = false;
            self._currentUserId =  parseInt($('#userid').val());
            self._currentUserFirstName =  $('#userFirstName').val();
            self._currentUserLastName =  $('#userLastName').val();
            self._currentUserUsername = $('#username').val();
        }
        else {
           if (EasyEditAssetManager && EasyEditAssetManager.hasOwnProperty('_currentUserAsset') && EasyEditAssetManager._currentUserAsset !== null) {
                self._currentUserId = parseInt(EasyEditAssetManager._currentUserAsset.id);
                self._currentUserFirstName = EasyEditAssetManager._currentUserAsset.attr.first_name;
                self._currentUserLastName = EasyEditAssetManager._currentUserAsset.attr.last_name;
                if(self._currentUserFirstName == null) {
                    self._currentUserFirstName = '';
                }
                if(self._currentUserLastName == null) {
                    self._currentUserLastName = '';
                }
                self._currentUserUsername = EasyEditAssetManager._currentUserAsset.attr.username;
            }
        }
        // if current user doesn't have first name and last name, just use username
        if(self._currentUserFirstName == '' && self._currentUserLastName == '') {
            self._currentUserFirstName = self._currentUserUsername;
        }


        // init comment array
        $('div[data-container-id]').each(function() {
            var $container = $(this);
            var currentContainerId = $container.data('container-id');

            // init array index for current container
            if(typeof self._comments[currentContainerId] == 'undefined') {
                self._comments[currentContainerId] = [];
            }

            // restore previous stored comments, load them into self._comments array
            var previousComments = $container.data('comments');
            if(typeof previousComments != 'undefined' && previousComments != '') {
                self._comments[currentContainerId] = JSON.parse(decodeURIComponent(previousComments));
            }
        });


        // when click outside of viper
        this.viper.registerCallback('Viper:clickedOutside', 'MatrixCommentsPlugin', function(e) {
            // remove comment action dialog
            $('.Matrix-Viper-commentdialog-comment-action').remove();

            // if we have active comment dialog, let user finish it
            if($('.Matrix-Viper-commentdialog').length > 0)  return;

        });


        // insert all comments
        // let's re-insert those comment marks
        var loadAllComments = function () {
            self._commentsPositions = [];
            self._removeAllCommentMarks();
            $('div[data-container-id]').each(function() {
                var $container = $(this);
                self._containerId = $container.data('container-id');
                self._bodycopyId = $container.data('bodycopy-id');
                self._bodycopyContainer = $container;
                self._reinsertCommentMarks();
            });
        }
        if(typeof EasyEditEventManager != 'undefined') {
            // in Edit+, insert after containers loaded
            EasyEditEventManager.bind('EasyEditPageStandardLoadComplete', loadAllComments);
        }
        else {
            // in admin, when page is ready
            $(document).ready(loadAllComments);
        }



        // determine comment color code for current user
        if (self._currentUserId > 0) {
                self._commentColor = 0;
                var foundUsedColor = false;
                var lastUsedColor = -1;
                jQuery.each(self._comments, function(containerid, data) {
                    jQuery.each(data, function(key, value) {
                        if(value['userId'] == self._currentUserId) {
                            self._commentColor = value['color'];
                            foundUsedColor = true;
                        }
                        else {
                            // find the latest and highest used color index
                            if(value['color'] > lastUsedColor) {
                                lastUsedColor = value['color'];
                            }
                        }
                    });
                });
                // only 5 available colors ['0583db', 'ad41bd', 'f17828', 'e8b01f', 'ea4c8b'];
                // pick next color available
                if(!foundUsedColor) {
                    self._commentColor = (lastUsedColor + 1) % 5;
                }
        }


        // when click on editable viper content
        this.viper.registerCallback('Viper:viperElementFocused', 'MatrixCommentsPlugin', function() {

            // set current bodycopy id and container id
            var editableElement = self.viper.getEditableElement();
            self._containerId = $(editableElement).data('container-id');
            self._bodycopyId = $(editableElement).data('bodycopy-id');
            self._bodycopyContainer = $(editableElement);

            if(!self._containerId || !self._bodycopyId) {
                self._allowCommentSystem = false;
                return;
            }
            self._allowCommentSystem = true;


            // if we are creating a new comment and then click away, we should clean up the comment attribute properly
            if(typeof $('#Matrix-Viper-commentdialog-newCommentButton-cancel').get(0) !== 'undefined') {
                // click the cancel button will do it
                $('#Matrix-Viper-commentdialog-newCommentButton-cancel').click();
            }


            // remove comment action dialog
            $('.Matrix-Viper-commentdialog-comment-action').remove();
            // remove comment dialogs
            $('.Matrix-Viper-commentdialog').remove();
            // remove selected comment class
            $('.Matrix-Viper-comment-highlighted').removeClass('Matrix-Viper-comment-highlighted');
            // cancel opacity to commenr marks
            $('.Matrix-Viper-commentmark').css('opacity', 'none');

            // reset id counter
            self._commentId = 0;
            jQuery.each(self._comments[self._containerId], function(key, value) {
                if(value['id'] > self._commentId) {
                    self._commentId = value['id'];
                }
            });

        });



        // we have to re-position our comment makrs when we make contents changes in viper
        this.viper.registerCallback('Viper:nodesChanged', 'MatrixCommentsPlugin', function() {
            // set time delay because press enter will trigger this event even before new paragraph inserted
            setTimeout(function(){
                self.updateExistingComments();
            }, 250);
        });

        // when resize window, we have to re-position our comment makrs as well
        $( window ).resize(function() {
            // just close those comment action dialogs, no need to re-position them
            jQuery('.Matrix-Viper-commentdialog-comment-action').remove();
            self.updateExistingComments();
        });



    },

    initToolbar: function()
    {
		var toolbar = this.viper.ViperPluginManager.getPlugin('ViperToolbarPlugin');
		if (!toolbar) {
		    return;
		}
    },

    initInlineToolbar: function ()
    {
    	var self = this;
    	var tools = this.viper.ViperTools;
    	var inlineToolbar = this.viper.ViperPluginManager.getPlugin('ViperInlineToolbarPlugin');

        this.viper.registerCallback('ViperInlineToolbarPlugin:initToolbar', 'MatrixCommentsPlugin', function(toolbar) {
        	self.createInlineToolbar(toolbar);
        });

        this.viper.registerCallback('ViperInlineToolbarPlugin:updateToolbar', 'MatrixCommentsPlugin', function(data) {
            self.updateInlineToolbar(data);
        });


    },


    createInlineToolbar: function(toolbar)
    {
    	var self       = this;
    	var addCommmentBtn = self.viper.ViperTools.createButton('vitpMatrixComments', '', _('Add Comment'), 'Viper-add-comments', function() {
    		self.newComment();
    	});
		var buttonGroup = self.viper.ViperTools.createButtonGroup('vitpCommentsBtnGroup');
		self.viper.ViperTools.addButtonToGroup('vitpMatrixComments', 'vitpCommentsBtnGroup');
        toolbar.addButton(buttonGroup);
    },

    updateInlineToolbar: function(data)
    {
        if(this._allowCommentSystem) {
            data.toolbar.showButton('vitpMatrixComments');
            var range = this.viper.getViperRange();
            var node  = range.getNodeSelection();
            if(node && ViperUtil.hasAttribute(node, 'data-comment-id')) {
                this.viper.ViperTools.setButtonActive('vitpMatrixComments');
            }
        }
    },


    _findNextViewableComment: function ()
    {  
        
        var self  = this;
        $commentMarks = self.getSortedCommentMarks();
        currentMark = self._currentViewingCommentMark;

        for(var i = 0; i < $commentMarks.length; i++) {
            if(currentMark.id == $commentMarks[i].id && $commentMarks[i + 1]) {
                return $commentMarks[i + 1];
            }
        }
        return null;
    },

    _findPrevViewableComment: function () {
        var self  = this;
        $commentMarks = self.getSortedCommentMarks();
        currentMark = self._currentViewingCommentMark;

        for(var i = 0; i < $commentMarks.length; i++) {
            if(currentMark.id == $commentMarks[i].id && $commentMarks[i - 1]) {
                return $commentMarks[i - 1];
            }
        }
        return null;
    },

    /*
    * this function is called to determine if the view next/prev button should be on or off
    */
    _updateNextPrevCommentButtons: function ($prevCommentButton, $nextCommentButton) {
        var self  = this;
        commentArrayIndexNext = this._findNextViewableComment();
        commentArrayIndexPrev = this._findPrevViewableComment();


        if(commentArrayIndexNext == null) {
            $nextCommentButton.addClass('arrow-disabled');
        }
        else {
            $nextCommentButton.removeClass('arrow-disabled');
        }

        if(commentArrayIndexPrev == null) {
            $prevCommentButton.addClass('arrow-disabled');
        }
        else {
            $prevCommentButton.removeClass('arrow-disabled');
        }
    },

    /*
    * sort all comment marks based on their vertical appearance order
    */
    getSortedCommentMarks: function (containerId)
    {
        if(typeof containerId != 'undefined') {
            $commentMarks = $('.Matrix-Viper-commentmark[data-comment-container-id=' + containerId + ']');
        }
        else {
            $commentMarks = $('.Matrix-Viper-commentmark');
        }

        return $commentMarks.sort(function (a, b) {
          var topA =parseInt( $(a).offset().top);
          var topB =parseInt( $(b).offset().top);
          return topA > topB;
        });
    },

    /*
    * remove all comment marks in dom
    */
    _removeAllCommentMarks: function ()
    {
        var self = this;
        // remove all comments mark
        $('div[id ^=Matrix-Viper-commentmark-]').remove();
        // then remove each mark in the comments array
        jQuery.each(self._comments, function(containerid, value) {
            jQuery.each(value, function(index, comment){
                if(typeof comment['commentMark'] != 'undefined') {
                    $(comment['commentMark']).remove();
                    self._comments[containerid][index]['commentMark'] = null;
                }
            });
        });
    },

    /*
    * re-inserts comment marks after been removed
    */
    _reinsertCommentMarks: function ()
    {
        var self = this;
        if(!self._comments[self._containerId]) return;
        jQuery.each(self._comments[self._containerId], function(key, value) {
            // do not display unattached comments
            if(typeof value['status'] != 'undefined' && value['status'] == 'unattached') return;

            var targetElement = $('[data-comment-id=' + value['id'] + '][data-comment-container-id=' + self._containerId + ']').get(0);
            if(typeof value['commentMark'] == 'undefined' || value['commentMark'] == null) {
                if($('#Matrix-Viper-commentmark-' + self._containerId + '-' + value['id']).length) {
                    self._comments[self._containerId][key]['commentMark'] = $('#Matrix-Viper-commentmark-' + self._containerId + '-' + value['id']).get(0);
                }
                else {    
                    if(targetElement) {
                        self._comments[self._containerId][key]['commentMark'] = self.createCommentMark(targetElement, value['id'], self._containerId, value['color']);
                    }
                    else {
                        self._comments[self._containerId][key]['commentMark'] = null;
                    }
                }
            }
            else {
                $(value['commentMark']).show();
            }
            // position the comment mark
            if(targetElement && self._comments[self._containerId][key]['commentMark']) {
                self._positionCommentMark(targetElement, self._comments[self._containerId][key]['commentMark']);
                if($('#Matrix-Viper-commentdialog-' + value['containerid'] + '-' + value['id']).length) {
                    self._positionCommentDialog(value['commentMark'], $('#Matrix-Viper-commentdialog-' + value['containerid'] + '-' + value['id']).get(0));
                }
            }
        });
    },


    /*
    * when we click add comment button in inline toolbar
    */
    newComment: function()
    {
        var range = this.viper.getViperRange();
        var node  = range.getNodeSelection();

        // set the current editing bodycopy container
        self._bodycopyContainer = $(range.startContainer).closest('div[data-container-id]');

        if ((ViperUtil.isTag(node, 'span') === true || ViperUtil.isTag(node, 'div') === true) && ViperUtil.hasAttribute(node, 'data-comment-id')) {
        	this.actionOnCommentTarget([node]);
        } else if (ViperUtil.isBlockElement(node)) {
        	this.actionOnCommentTarget([node]);
        } else {
            return this.rangeToComment(range);
        }

        range.selectNode(node);
        ViperSelection.addRange(range);
        this.viper.fireSelectionChanged(range, true);
        this.viper.fireNodesChanged([node]);

    },


    /*
    * insert the comment data attribute / span tag
    */
 	rangeToComment: function(range)
    {
        
        // if the range containers block element, we just have to mark those block elements
        var nodes = this._getRangeSelectedNodes(range);
        var nodesToMark = [];
        for (var i = 0; i < nodes.length; i++) {
            // img is not really a block element, just tagret on others
            if(ViperUtil.isBlockElement(nodes[i]) && !ViperUtil.isTag(nodes[i], 'img')) {
                nodesToMark.push(nodes[i]);
            }
        }
        if(nodesToMark.length > 0) {
           this.actionOnCommentTarget(nodesToMark);
           this.viper.fireSelectionChanged(range, true);
           this.viper.fireNodesChanged(nodesToMark);
           return;
        }

        // the range should only container inline elements now, just wrap it with span.
        var span     = document.createElement('span');
        span = this.viper.surroundContents('span', null, range);
        span.setAttribute('data-commentspan', 1);
        this.actionOnCommentTarget([span]);


        this.viper.fireSelectionChanged(range, true);
        this.viper.fireNodesChanged([this.viper.getViperElement()]);

        return span;

    },

    _nextNode: function (node) {
        if (node.hasChildNodes()) {
            return node.firstChild;
        } else {
            while (node && !node.nextSibling) {
                node = node.parentNode;
            }
            if (!node) {
                return null;
            }
            return node.nextSibling;
        }
    },

    // get all selected dom nodes from range
    _getRangeSelectedNodes: function (range) {
        var node = range.startContainer;
        var endNode = range.endContainer;

        // Special case for a range that is contained within a single node
        if (node == endNode) {
            return [node];
        }

        // Iterate nodes until we hit the end container
        var rangeNodes = [];
        while (node && node != endNode) {
            rangeNodes.push( node = this._nextNode(node) );
        }

        // Add partially selected nodes at the start of the range
        node = range.startContainer;
        while (node && node != range.commonAncestorContainer) {
            rangeNodes.unshift(node);
            node = node.parentNode;
        }

        return rangeNodes;
    },


    removeComment: function(element)
    {
       $element = jQuery(element);
       $element.removeClass('Matrix-Viper-comment-highlighted');
       if($element.prop('tagName') == 'SPAN' || $element.prop('tagName') == 'DIV') {
        $element.replaceWith(function () {
            return $(this).html();
        });
       }
       else {
        $element.removeAttr('data-comment-id');
        $element.removeAttr('data-comment-container-id');
        $element.removeAttr('data-comment');
       }
    },

    /*
    * either creates a new comment or open the dialog for existing comment when the tagret element is actioned on
    */
	actionOnCommentTarget: function(elements)
    {
        var self = this;

        if(typeof elements[0] == 'undefined') return;

    	if(!ViperUtil.hasAttribute(elements[0], 'data-comment-id')) {
            // create a new comment mark
    		this._commentId = this._commentId + 1;
            for(i=0; i< elements.length; i++) {
                elements[i].setAttribute('data-comment', 1);
            	elements[i].setAttribute('data-comment-id', this._commentId);
                elements[i].setAttribute('data-comment-container-id', this._containerId);
            }
        	var commentMark = this.createCommentMark(elements[0], this._commentId, this._containerId, self._commentColor);
            self._positionCommentMark(elements[0], commentMark);

            // immediately open the dialog if it's a new comment
            // timeout is needed because for some reason positioning marks would take time, we have to for it to finish
            setTimeout(function() {
                self.createCommentDialog(self._commentId, self._containerId, commentMark);
            }, 100);


            // store the new comment in array
            if(typeof self._comments[self._containerId] == 'undefined') {
                self._comments[self._containerId] = [];
            }
            self._comments[self._containerId].push({'id' : this._commentId, 'commentMark' : commentMark, 'color' : self._commentColor, 'userId' : self._currentUserId, 'containerid' : self._containerId, 'comments' : [], 'status' : 'open'});
    	}
        else {
            // open the comment mark's dialog
            var commentId = $(elements[0]).data('comment-id');
            var containerId = $(elements[0]).data('comment-container-id');
            $('[data-comment-container-id=' + containerId + '][data-comment-id=' + commentId + '].Matrix-Viper-commentmark').mousedown();
        }
    },

    /*
    * insert a comment mark
    */
    createCommentMark: function(element, id, containerId, color)
    {
        var self = this;
    	var commentMark = document.createElement('div');


        $(commentMark).attr('id', 'Matrix-Viper-commentmark-' + containerId + '-' + id);
        $(commentMark).attr('data-comment-id', id);
        $(commentMark).attr('data-comment-container-id', containerId);
        $(commentMark).addClass('Matrix-Viper-commentmark');
        $(commentMark).addClass('Matrix-Viper-commentmark-color-' + color);

        // set the mark content with the number of comments in it
        var existingComment = false;
        for (var i = 0; i < self._comments[containerId].length; i++) {
            if(self._comments[containerId][i]['id'] == id) {
                existingComment = true;
                if(typeof self._comments[containerId][i]['status'] == 'undefined' || self._comments[containerId][i]['status'] === 'open') {
                    // if it's open issue, add number
                    $(commentMark).append('<div class="Matrix-Viper-commentmark-number-count">' + self._comments[containerId][i]['comments'].length + '</div>');
                }
                else {
                    // resolved issue just add this class
                    $(commentMark).append('<div class="Matrix-Viper-commentmark-number-count"></div>');
                    $(commentMark).addClass('Matrix-Viper-commentmark-resolved');
                }
            }
        }
        // if it's a new comment, we don't have it stored in array, also it can't be resolved
        if(!existingComment) {
            $(commentMark).append('<div class="Matrix-Viper-commentmark-number-count"></div>');
        }


        $(commentMark).mousedown(function(e) {
            // open a new dialog
            self.createCommentDialog(id, containerId, this);
        });

        // append it
        document.body.appendChild(commentMark);


        return commentMark;
    },

    /*
    * insert comment dialog interface
    */
    createCommentDialog: function(id, containerId, commentMark)
    {

        var self = this;
        var $commentDialog = jQuery(document.createElement('div'));
        var $commentDialogNewComment = jQuery(document.createElement('div'));
        var $commentDialogReplyComment = jQuery(document.createElement('div'));

        // remove all other dialogs
        $('.Matrix-Viper-commentdialog').remove();

        // add opacity to other commenr marks
        $('.Matrix-Viper-commentmark').css('opacity', 0.5);
        $(commentMark).css('opacity', 'none');

        // set current comment mark
        self._currentViewingCommentMark = commentMark;

        $commentDialog.attr('id', 'Matrix-Viper-commentdialog-' + containerId + '-' + id);
        $commentDialog.attr('data-comment-id', id);
        $commentDialog.attr('data-comment-container-id', containerId);
        $commentDialog.addClass('Matrix-Viper-commentdialog');
        if(!self._inEditPlus) {
            $commentDialog.addClass('Matrix-Viper-commentdialog-inAdmin');
        }
        // position the created dialog
        self._positionCommentDialog(commentMark, $commentDialog.get(0));

        // get comments from the thread
        var comments = [];
        var status = 'open';
        for(var i = 0; i < self._comments[containerId].length; i++) {
            if(self._comments[containerId][i]['id'] == id) {
                comments = self._comments[containerId][i]['comments'];
                // get the current thread status
                if(typeof self._comments[containerId][i]['status'] != 'undefined') {
                    status = self._comments[containerId][i]['status'];
                }
            }
        }
        if(typeof comments == 'undefined') return;

        if(comments.length == 0) {
            // show new comment dialog
            $commentDialog.append($commentDialogNewComment);

            $commentDialog.addClass('Matrix-Viper-commentdialog-new');

            $commentDialogNewComment.append('<div class="Matrix-Viper-commentdialog-currentUserName">'+ this._currentUserFirstName + ' ' + this._currentUserLastName + '</div>');
            $commentDialogNewComment.append('<div class="Matrix-Viper-commentdialog-newCommentText">'+ _('New Comment') + '</div>');

            var $replyCommentMainArea = $('<div class="Matrix-Viper-commentdialog-mainArea"></div>');
            var $newCommentTextArea = $('<textarea class="Matrix-Viper-commentdialog-newCommentTextArea"/>');
            $replyCommentMainArea.append($newCommentTextArea);
            $commentDialogNewComment.append($replyCommentMainArea);

            var $newCommentButtonArea = $('<div class="Matrix-Viper-commentdialog-buttonArea"></div>');
            var $newCommentButtonCancelButton = $('<a href="#" id="Matrix-Viper-commentdialog-newCommentButton-cancel" class="Matrix-Viper-commentdialog-button-grey">' + _('Cancel') + '</a>');
            var $newCommentButtonCommentButton = $('<a href="#" id="Matrix-Viper-commentdialog-newCommentButton-comment" class="Matrix-Viper-commentdialog-button-blue">' + _('Comment') + '</a>');
            $newCommentButtonArea.append($newCommentButtonCancelButton);
            $newCommentButtonArea.append($newCommentButtonCommentButton);
            $commentDialogNewComment.append($newCommentButtonArea);

            // cancel dialog
            $newCommentButtonCancelButton.click(function (e) {
                ViperUtil.preventDefault(e);

                // remove selected comment class
                // remove the actual target element's comment attribute
                var commentId = $(commentMark).data('comment-id');
                var commentContainerId = $(commentMark).data('comment-container-id');
                var $targetElement = self._bodycopyContainer.find('[data-comment-id=' + commentId + '][data-comment-container-id=' + commentContainerId + ']');
                $targetElement.removeClass('Matrix-Viper-comment-highlighted');
                $.each($targetElement, function( key, value ) {
                  self.removeComment(value);
                });

                $('.Matrix-Viper-comment-highlighted').removeClass('Matrix-Viper-comment-highlighted');

                $commentDialog.remove();
                $(commentMark).remove();

                // remove the comment from stored array and reset the id counter
                for(var i = 0; i < self._comments[containerId].length; i++) {
                    if(self._comments[containerId][i]['id'] == id) {
                        self._comments[containerId].splice(i, 1);
                        i--;
                    }
                    else if (self._comments[containerId][i]['id'] > id) {
                        self._commentId = self._comments[containerId][i]['id'];
                    }
                }

                // cancel opacity to commenr marks
                $('.Matrix-Viper-commentmark').css('opacity', 'none');

                self.updateExistingComments();
                ViperUtil.preventDefault(e);
            })


            // add comment
            $newCommentButtonCommentButton.click(function (e) {
                ViperUtil.preventDefault(e);
                var commentContent = $newCommentTextArea.val();
                // do not accept empty comment
                if(commentContent == '') return;

                var commentData = {'userid' : self._currentUserId, 'userFirstName' : self._currentUserFirstName, 'userLastName' : self._currentUserLastName, 'timestamp' : jQuery.now(), 'content' : JSON.stringify(commentContent), 'color' : self._commentColor};
                for(var i = 0; i < self._comments[containerId].length; i++) {
                    if(self._comments[containerId][i]['id'] == id) {
                        // add new comment
                        self._comments[containerId][i]['comments'].push(commentData);
                        // set comment mark counter
                        $(commentMark).find('.Matrix-Viper-commentmark-number-count').html(self._comments[containerId][i]['comments'].length);
                    }
                }

                // enable edit+ save button
                if(typeof EasyEditComponentsToolbar != 'undefined') {
                    EasyEditComponentsToolbar.enableSaveButton();
                }

                // re-open the dialog to show the reply interface
                $commentDialog.remove();
                ViperUtil.preventDefault(e);
                $(commentMark).mousedown();
            });


        }
        else {

            // reply comment dialog, displayed when there are comments under this thread
            $commentDialog.append($commentDialogReplyComment);

            // if it's a comment for deletion, add the css class
            if($(commentMark).hasClass('Matrix-Viper-commentmark-color-red')) {
                $commentDialog.addClass('Matrix-Viper-commentdialog-deletion');
            }


            $header_div = $('<div class="Matrix-Viper-commentdialog-reply-header"></div>');
            $scrollDiv = $('<div class="Matrix-Viper-commentdialog-scroll-comments"></div>');
            $prev_arrow = $('<div class="Matrix-Viper-commentdialog-reply-header-prev" title="' + _('Go to previous comment thread') + '"></div>');
            $next_arrow = $('<div class="Matrix-Viper-commentdialog-reply-header-next" title="' + _('Go to next comment thread') + '"></div>');

            $resolve_switch = jQuery('<div class="Matrix-Viper-commentdialog-reply-header-resolve GUI-switch"></div>');
            $resolve_switch_label = jQuery('<span class="Matrix-Viper-commentdialog-reply-header-resolve-label">' + _('Mark as resolved')+ '</span>');
            $resolve_switch_button = jQuery('<span class="GUI-switch-button" title="' + _('Mark this comment thread as resolved') + '"><span class="GUI-switch-slider"></span></span>');
            $resolve_switch.append($resolve_switch_label);
            $resolve_switch.append($resolve_switch_button);
            if(status == 'resolved') {
                $resolve_switch.addClass('GUI-active');
                $resolve_switch_label.html(_('Resolved'));
            }

            $header_div.append($prev_arrow);
            $header_div.append($next_arrow);
            $header_div.append($resolve_switch);

            $commentDialogReplyComment.append($header_div);

            // disable arrows if needed
            self._updateNextPrevCommentButtons($prev_arrow, $next_arrow);

             // click view next comment
            $next_arrow.click(function() {
                var nextCommentMark = self._findNextViewableComment();
                if(nextCommentMark != null) {
                    var commentMark = nextCommentMark
                    var commentId = $(nextCommentMark).data('comment-id');
                    var containerId = $(nextCommentMark).data('comment-container-id');
                    self.createCommentDialog(commentId, containerId, commentMark);
                    $('html, body').animate({
                        scrollTop: $(commentMark).offset().top - 200
                    }, 400);        

                }
            });


            // click prev comment
            $prev_arrow.click(function() {
                var prevCommentMakr = self._findPrevViewableComment();
                if(prevCommentMakr != null) {
                    var commentMark = prevCommentMakr
                    var commentId = $(prevCommentMakr).data('comment-id');
                    var containerId = $(prevCommentMakr).data('comment-container-id');
                    self.createCommentDialog(commentId, containerId, commentMark);
                    $('html, body').animate({
                        scrollTop: $(commentMark).offset().top - 200
                    }, 400);
                }
            });

            //  list comments
            for(var i = 0; i < comments.length; i++) {
                $comment_div = jQuery('<div class="Matrix-Viper-commentdialog-reply-comment" data-comment-id="' + id + '" data-comment-index="' + i + '" data-comment-userid="' + comments[i]['userid'] + '" ></div>');

                // if it's system comment, give it a special class
                var isSystemComment = false;
                if(typeof comments[i]['systemComment'] != 'undefined' && comments[i]['systemComment']) {
                    isSystemComment = true;
                }
                if(isSystemComment) {
                    $comment_div.addClass('Matrix-Viper-commentdialog-reply-comment-system-comment');
                }

                $replyCommentUserName = jQuery('<div class="Matrix-Viper-commentdialog-reply-comment-userName">'+ comments[i]['userFirstName'] + ' ' + comments[i]['userLastName'] + '</div>');

                $comment_div.append($replyCommentUserName);

                // only show the comment action button if current user is the one who created it
                if(self._currentUserId == comments[i]['userid'] && !isSystemComment) {
                    $replyCommentsAction = jQuery('<div class="Matrix-Viper-commentdialog-reply-comment-action" data-comment-id="' + id + '" data-comment-index="' + i + '"></div>');
                    $comment_div.append($replyCommentsAction);
                }

                var now = jQuery.now();
                $timestampDiv = $('<div class="Matrix-Viper-commentdialog-reply-comment-timestamp"></div>');
                $createdTimestamp = $('<span class="readableAge" data-timestamp="'+ comments[i]['timestamp'] + '" title="' + self._formattedDate(comments[i]['timestamp']) + '">'+ self._readableAge(comments[i]['timestamp'], now) + '</span>');
                $timestampDiv.append($createdTimestamp);

                if(typeof comments[i]['editTimestamp'] !== 'undefined') {
                    $editTimestamp = $('<span class="Matrix-Viper-commentdialog-reply-comment-edit-timestamp" title="' + self._formattedDate(comments[i]['editTimestamp']) + '"> - ' + _('Edited') + ' <span class="readableAge" data-timestamp="'+ comments[i]['editTimestamp'] + '">'+ self._readableAge(comments[i]['editTimestamp'], now) + '</span></span>');
                    $timestampDiv.append($editTimestamp);
                }
                $comment_div.append($timestampDiv);
               
                // main comment div
                var showMoreLink = false;
                var $mainContentDiv = $('<div class="Matrix-Viper-commentdialog-reply-comment-content"></div>');
                var mainContent = JSON.parse(comments[i]['content']);
                var contentLength = mainContent.length;
                // new line worth 40 characters
                var newlineLength = (mainContent.split(/\r\n|\r|\n/).length - 1) * 40;
                var totalLength = contentLength + newlineLength;

                // use Show more link if comment exceeds the limit
                var commentLengthHardLimit = 80;
                var commentLengthSoftLimit = 90;
                // the initial thread gets a long limit
                if(i == 0) {
                    commentLengthHardLimit = 80;
                    commentLengthSoftLimit = 90;
                }

                if(totalLength > commentLengthHardLimit) {
                    if(totalLength > commentLengthSoftLimit) {
                        mainContent = mainContent.substring(0, commentLengthHardLimit); 
                        // after we trim to the limit length, we need to find out all new lines in the remaining content 
                        // and keep shrinking the content until it is small enough
                        while(totalLength > commentLengthHardLimit) {
                            mainContent = mainContent.substring(0, mainContent.length - 1);
                            totalLength = mainContent.length + (mainContent.split(/\r\n|\r|\n/).length - 1) * 40;
                        }
                        showMoreLink = true;
                    }
                }
                $mainContentDiv.append(self._htmlEncode(mainContent));

                if(showMoreLink) {
                    $showMoreLinkDiv = $('<div class="Matrix-Viper-commentdialog-reply-comment-showMore"><a href="#" >' + _('Show more') + '<span></span></a></div>');
                    $mainContentDiv.append($showMoreLinkDiv);
                    $showMoreLinkDiv.click(function (e) {
                        ViperUtil.preventDefault(e);
                        var parentContentDiv = $(this).closest('.Matrix-Viper-commentdialog-reply-comment-content');
                        var parentCommentDiv = $(this).closest('.Matrix-Viper-commentdialog-reply-comment');
                        var fullContent = comments[parentCommentDiv.data('comment-index')];
                        parentContentDiv.html(self._htmlEncode(JSON.parse(fullContent['content'])));
                    })
                }
                // finally attach the trimmed main content div
                $comment_div.append($mainContentDiv);

                // edit comment text area
                var $editCommentTextArea = jQuery('<textarea class="Matrix-Viper-commentdialog-replyCommentTextArea Matrix-Viper-commentdialog-editCommentTextArea">'+ JSON.parse(comments[i]['content']) + '</textarea>');
                $comment_div.append($editCommentTextArea);

                 // edit comment buttons
                var $editCommentButtonCancelButtonArea = jQuery('<div class="Matrix-Viper-commentdialog-buttonArea Matrix-Viper-commentdialog-editButtonArea"></div>');
                var $editCommentButtonCancelButton = jQuery('<a href="#" id="Matrix-Viper-commentdialog-editCommentButton-cancel" class="Matrix-Viper-commentdialog-button-grey">' + _('Cancel') + '</a>');
                var $editCommentButtonSaveButton = jQuery('<a href="#" id="Matrix-Viper-commentdialog-editCommentButton-save" class="Matrix-Viper-commentdialog-button-blue">' + _('Save') + '</a>');
                $editCommentButtonCancelButtonArea.append($editCommentButtonCancelButton);
                $editCommentButtonCancelButtonArea.append($editCommentButtonSaveButton);
                $comment_div.append($editCommentButtonCancelButtonArea);

                // every minute update the readable time
                self._updateReadableAges($comment_div);

                // set the comment author name's color
                $replyCommentUserName.css('color', self._availableCommentColor[comments[i]['color']]);

                // if it's top comment, we wrap it in a div, rest of comments in a scrollable div
                if(i == 0) {
                    $topCommentDiv = $('<div class="Matrix-Viper-commentdialog-top-comment"></div>');
                    $topCommentDiv.append($comment_div);
                    $commentDialogReplyComment.append($topCommentDiv);
                }
                else {         
                    $scrollDiv.append($comment_div);
                }

                // actions on each comment
                if(typeof $replyCommentsAction !== 'undefined') {
                    $replyCommentsAction.click(function () {
                        var id = jQuery(this).data('comment-id');
                        var index = jQuery(this).data('comment-index');
                        var deleteText = _('Delete Comment');

                        if(index == 0 ) {
                            if($(commentMark).hasClass('Matrix-Viper-commentmark-color-red')) {
                                deleteText = _('Unmark For Deletion');
                            }
                            else {
                                deleteText = _('Mark For Deletion');
                            }
                        }
                        var $commentActionDiv = jQuery('<div class="Matrix-Viper-commentdialog-comment-action"></div>');
                        var $commentActionDivEdit = jQuery('<div class="Matrix-Viper-commentdialog-comment-action-edit" data-comment-id="' + id + '" data-comment-index="' + index + '" >' + _('Edit Comment') + '</div>');
                        var $commentActionDivDelete = jQuery('<div class="Matrix-Viper-commentdialog-comment-action-edit" data-comment-id="' + id + '" data-comment-index="' + index + '" >' + deleteText + '</div>');
                        $commentActionDiv.append($commentActionDivEdit);
                        $commentActionDiv.append($commentActionDivDelete);
                        document.body.appendChild($commentActionDiv.get(0));
                        $commentActionDiv.css({
                            left: ($(this).offset().left - $commentActionDiv.width() + $(this).width() + 5) + "px",
                            top: ($(this).offset().top + $(this).height() + 10) + "px"
                        });

                        // click on delete comment
                        $commentActionDivDelete.mousedown(function (e) {
                            var commentIndex = jQuery(this).data('comment-index');
                            var commentId = jQuery(this).data('comment-id');

                            for(var y = 0; y < self._comments[containerId].length; y++) {
                                if(self._comments[containerId][y]['id'] == commentId) {
                                    if(commentIndex == 0) {
                                        $commentMark = $('#Matrix-Viper-commentmark-' + containerId + '-' + self._comments[containerId][y]['id']);
                                        if(self._comments[containerId][y]['deletion']) {
                                            // unmark for deletion
                                            self._comments[containerId][y]['deletion'] = false;
                                            $commentMark.removeClass('Matrix-Viper-commentmark-color-red');
                                            // put back the comments number
                                            if(self._comments[containerId][y]['status'] == 'open') {
                                                var commentNumber = 0;
                                                for(var i = 0; i< self._comments[containerId].length; i++) {
                                                    if(self._comments[containerId][i]['id'] == commentId) {
                                                        commentNumber = self._comments[containerId][i]['comments'].length;
                                                    }
                                                }
                                                $commentMark.find('.Matrix-Viper-commentmark-number-count').html(commentNumber);
                                            }
                                        }
                                        else {
                                            // mark for deletion
                                            self._comments[containerId][y]['deletion'] = true;                                            
                                            $commentMark.addClass('Matrix-Viper-commentmark-color-red');
                                            // clear out the number
                                            $commentMark.find('.Matrix-Viper-commentmark-number-count').html('');
                                        }
                                        jQuery('#Matrix-Viper-commentdialog-' + containerId + '-' + commentId).remove();
                                        $('.Matrix-Viper-commentdialog-comment-action').remove();
                                        ViperUtil.preventDefault(e);

                                    }
                                    else {
                                        var userComment = self._comments[containerId][y]['comments'][commentIndex];
                                        if(typeof userComment !== 'undefined') {
                                            // remove this comment
                                            self._comments[containerId][y]['comments'].splice(commentIndex, 1);
                                        }
                                        $(commentMark).mousedown();
                                    }
                                }
                            }
                            // enable edit+ save button
                            if(typeof EasyEditComponentsToolbar != 'undefined') {
                                EasyEditComponentsToolbar.enableSaveButton();
                            }
                        });


                        // click on edit comment
                        $commentActionDivEdit.mousedown(function (e) {
                            var commentIndex = jQuery(this).data('comment-index');
                            var commentId = jQuery(this).data('comment-id');
                            var $commentDiv = jQuery('.Matrix-Viper-commentdialog-reply-comment[data-comment-id=' + commentId + '][data-comment-index=' + commentIndex + ']');

                            for(var y = 0; y < self._comments[containerId].length; y++) {
                                if(self._comments[containerId][y]['id'] == commentId) {
                                    var userComment = self._comments[containerId][y]['comments'][commentIndex];
                                    if(typeof userComment !== 'undefined') {
                                        // edit this comment
                                        $commentDiv.find('.Matrix-Viper-commentdialog-reply-comment-content').hide();
                                        $commentDiv.find('.Matrix-Viper-commentdialog-editCommentTextArea').show().focus();
                                        $commentDiv.find('.Matrix-Viper-commentdialog-editButtonArea').show();
                                    }
                                }
                            }
                            $('.Matrix-Viper-commentdialog-comment-action').remove();
                            // enable edit+ save button
                            if(typeof EasyEditComponentsToolbar != 'undefined') {
                                EasyEditComponentsToolbar.enableSaveButton();
                            }
                            ViperUtil.preventDefault(e);
                        });
                    })
                }


                // click edit save comment button
                $editCommentButtonSaveButton.mousedown(function (e) {
                    ViperUtil.preventDefault(e);
                    var $commentDiv = jQuery(this).closest('.Matrix-Viper-commentdialog-reply-comment');
                    var commentId = $commentDiv.data('comment-id');
                    var commentIndex = $commentDiv.data('comment-index');
                    var $commentContent = $commentDiv.find('.Matrix-Viper-commentdialog-reply-comment-content');
                    var $commentEditTextArea = $commentDiv.find('.Matrix-Viper-commentdialog-editCommentTextArea');
                    var $commentTimestampArea = $commentDiv.find('.Matrix-Viper-commentdialog-reply-comment-timestamp');
                    var content = $commentEditTextArea.val();
                    for(var i = 0; i < self._comments[containerId].length; i++) {
                        if(self._comments[containerId][i]['id'] == commentId) {
                            for (var y = 0; y < self._comments[containerId][i]['comments'].length; y++) {
                                if(y == commentIndex) {
                                    self._comments[containerId][i]['comments'][y]['content'] = JSON.stringify(content);
                                    self._comments[containerId][i]['comments'][y]['editTimestamp'] = jQuery.now();
                                    if($commentTimestampArea.find('.Matrix-Viper-commentdialog-reply-comment-edit-timestamp').length > 0) {
                                        $commentTimestampArea.find('.Matrix-Viper-commentdialog-reply-comment-edit-timestamp').remove();
                                    }
                                    var now = jQuery.now();
                                    $editTimestamp = $('<span class="Matrix-Viper-commentdialog-reply-comment-edit-timestamp" title="' + self._formattedDate(now) + '"> - ' + _('Edited') + ' <span class="readableAge" data-timestamp="'+ now + '">'+ self._readableAge(now, now) + '</span></span>');
                                    $commentTimestampArea.append($editTimestamp);
                                }
                            }
                        }
                    }
                    $commentContent.html(self._htmlEncode($commentEditTextArea.val())).show();
                    $commentEditTextArea.hide();
                    $commentDiv.find('.Matrix-Viper-commentdialog-editButtonArea').hide();
                    ViperUtil.preventDefault(e);

                });

                // click cancel save comment button
                $editCommentButtonCancelButton.mousedown(function (e) {
                    ViperUtil.preventDefault(e);
                    var $commentDiv = jQuery(this).closest('.Matrix-Viper-commentdialog-reply-comment');
                    var commentId = $commentDiv.data('comment-id');
                    var commentIndex = $commentDiv.data('comment-index');
                    var $commentContent = $commentDiv.find('.Matrix-Viper-commentdialog-reply-comment-content');
                    var $commentEditTextArea = $commentDiv.find('.Matrix-Viper-commentdialog-editCommentTextArea')
                    var originalContent = '';
                    for(var i = 0; i < self._comments[containerId].length; i++) {
                        if(self._comments[containerId][i]['id'] == commentId) {
                            for (var y = 0; y < self._comments[containerId][i]['comments'].length; y++) {
                                if(y == commentIndex) {
                                    originalContent = self._comments[containerId][i]['comments'][y]['content'];
                                }
                            }
                        }
                    }
                    $commentContent.show();
                    $commentEditTextArea.val(originalContent).hide();
                    $commentDiv.find('.Matrix-Viper-commentdialog-editButtonArea').hide();
                });
            } // end of list comments

            // append the scroll div
            $commentDialogReplyComment.append($scrollDiv);

            // reply comment text area
            var $replyCommentMainArea = $('<div class="Matrix-Viper-commentdialog-mainArea"></div>');
            var $replyCommentTextArea = jQuery('<textarea class="Matrix-Viper-commentdialog-replyCommentTextArea" placeholder="' + _('Add a reply...') + '">');
            $replyCommentMainArea.append($replyCommentTextArea);
            $commentDialogReplyComment.append($replyCommentMainArea);

            // reply comment button area
            var $replyCommentButtonArea = $('<div class="Matrix-Viper-commentdialog-buttonArea"></div>');
            var $replyCommentButtonCancelButton = $('<a href="#" id="Matrix-Viper-commentdialog-replyCommentButton-cancel" class="Matrix-Viper-commentdialog-button-grey">' + _('Cancel') + '</a>');
            var $replyCommentButtonCommentButton = $('<a href="#" id="Matrix-Viper-commentdialog-replyCommentButton-comment" class="Matrix-Viper-commentdialog-button-blue">' + _('Reply') + '</a>');

            $replyCommentButtonArea.append($replyCommentButtonCancelButton);
            $replyCommentButtonArea.append($replyCommentButtonCommentButton);
            $commentDialogReplyComment.append($replyCommentButtonArea);

            // if current thread is resolved, no need to show reply buttons
            if(status == 'resolved') {
                $replyCommentMainArea.hide();
                $replyCommentButtonArea.hide();
            }

            // click cancel button
            $replyCommentButtonCancelButton.click(function (e) {
                ViperUtil.preventDefault(e);
                $commentDialog.remove();
                // remove selected comment class
                $('.Matrix-Viper-comment-highlighted').removeClass('Matrix-Viper-comment-highlighted');
                // cancel opacity to commenr marks
                $('.Matrix-Viper-commentmark').css('opacity', 'none');
                ViperUtil.preventDefault(e);
            })


            // click reply comment button
            $replyCommentButtonCommentButton.click(function (e) {
                ViperUtil.preventDefault(e);
                var commentContent = $replyCommentTextArea.val();
                // do not accept empty comment
                if(commentContent == '') return;
                var commentData = {'userid' : self._currentUserId, 'userFirstName' : self._currentUserFirstName, 'userLastName' : self._currentUserLastName, 'timestamp' : jQuery.now(), 'content' : JSON.stringify(commentContent), 'color' : self._commentColor};
                for(var i = 0; i < self._comments[containerId].length; i++) {
                    if(self._comments[containerId][i]['id'] == id) {
                        // add the new comment
                        self._comments[containerId][i]['comments'].push(commentData);
                        // set comment mark counter
                        $(commentMark).find('div').html(self._comments[containerId][i]['comments'].length);
                    }
                }

                // enable edit+ save button
                if(typeof EasyEditComponentsToolbar != 'undefined') {
                    EasyEditComponentsToolbar.enableSaveButton();
                }


                // re-open the dialog to show the reply interface
                $commentDialog.remove();
                ViperUtil.preventDefault(e);

                $(commentMark).mousedown();
            });


            // click resolve button
            $resolve_switch.click(function() {
                var $commentDialog = $(this).closest('.Matrix-Viper-commentdialog');
                var containerId = $commentDialog.data('comment-container-id');
                var commentId = $commentDialog.data('comment-id');
                var $commentMark = $('[data-comment-id=' + commentId + '][data-comment-container-id=' + containerId + '].Matrix-Viper-commentmark');
                $commentMark.toggleClass('Matrix-Viper-commentmark-resolved');
                $resolve_switch.toggleClass('GUI-active');

                var status = 'open';
                var commentContent = _('Marked as resolved');
                if($commentMark.hasClass('Matrix-Viper-commentmark-resolved')) {
                    $resolve_switch_label.html(_('Resolved'));
                    status = 'resolved';
                    // clear out the number
                    $commentMark.find('.Matrix-Viper-commentmark-number-count').html('');
                }
                else {
                    commentContent = _('Re-opened');
                    $resolve_switch_label.html(_('Mark as resolved'));
                    // put back the comments number
                    if(!$commentMark.hasClass('Matrix-Viper-commentmark-color-red')) {
                        var commentNumber = 0;
                        for(var i = 0; i< self._comments[containerId].length; i++) {
                            if(self._comments[containerId][i]['id'] == commentId) {
                                commentNumber = self._comments[containerId][i]['comments'].length;
                            }
                        }
                        $commentMark.find('.Matrix-Viper-commentmark-number-count').html(commentNumber);
                    }
                }

                // set the resolve / open status
                for(var i = 0; i< self._comments[containerId].length; i++) {
                    if(self._comments[containerId][i]['id'] == commentId) {
                        self._comments[containerId][i]['status'] = status;
                        // insert the system comment  
                        var commentData = {'userid' : self._currentUserId, 'userFirstName' : self._currentUserFirstName, 'userLastName' : self._currentUserLastName, 'timestamp' : jQuery.now(), 'content' : JSON.stringify(commentContent), 'color' : self._commentColor, 'systemComment' : true};
                        self._comments[containerId][i]['comments'].push(commentData);
                    }
                }

                // enable edit+ save button
                if(typeof EasyEditComponentsToolbar != 'undefined') {
                    EasyEditComponentsToolbar.enableSaveButton();
                }

                // re-open the dialog to show the reply interface
                $commentDialog.remove();
                $(commentMark).mousedown();

            })
        }

        // append it
        document.body.appendChild($commentDialog.get(0));

        // focus on the textarea
        if($newCommentTextArea) {
            $newCommentTextArea.mousedown().focus();
        }

        // scroll to bottom of div
        if($('.Matrix-Viper-commentdialog-scroll-comments').length) {
            $('.Matrix-Viper-commentdialog-scroll-comments').scrollTop($('.Matrix-Viper-commentdialog-scroll-comments')[0].scrollHeight);
        }

        // remove highlighted comment class
        $('.Matrix-Viper-comment-highlighted').removeClass('Matrix-Viper-comment-highlighted');
        // highlight the target element
        $('[data-comment-container-id=' + containerId + '][data-comment-id=' + id + '][data-comment=1]').addClass('Matrix-Viper-comment-highlighted');



        return $commentDialog.get(0);
    },

    /*
    * save all comments
    */
    saveComments: function (callback)
    {
        var self = this;
        var tools = this.viper.ViperTools;
        var inlineToolbar = this.viper.ViperPluginManager.getPlugin('ViperInlineToolbarPlugin');


        // remove comment action dialog
        $('.Matrix-Viper-commentdialog-comment-action').remove();
        // remove comment dialogs
        $('.Matrix-Viper-commentdialog').remove();
        // remove selected comment class
        $('.Matrix-Viper-comment-highlighted').removeClass('Matrix-Viper-comment-highlighted');
        // cancel opacity to commenr marks
        $('.Matrix-Viper-commentmark').css('opacity', 'none');



        // find and delete those comments / comment target element that don't match
        $('div[data-container-id]').each(function() {
            var $container = $(this);
            var containerid = $container.data('container-id');


            // delete those marked for deletion
            for (var i = 0; i < self._comments[containerid].length; i++) {
                if(self._comments[containerid][i]['deletion'] == true ) {
                    self._comments[containerid].splice(i, 1);
                    i--;
                }
            }


             // delete stored comments that doesn't have matching target
            for (var i = 0; i < self._comments[containerid].length; i++) {
                var comment = self._comments[containerid][i];
                if(typeof comment == 'undefined' || !$container.find('[data-comment-id=' + comment['id'] + '][data-comment-container-id=' + containerid + ']').length){
                    comment['status'] = 'unattached';
                }
            };


             // delete comment data attribute on the target elements that doesn't exist in stored comments
            $container.find('[data-comment-id][data-comment-container-id]').each(function() {
                var commentId = jQuery(this).data('comment-id');
                var containerid = jQuery(this).data('comment-container-id');

                if(containerid !== $container.data('container-id')) {
                    // a comment in wrong container, probably from copy and paste
                    self.removeComment(this);
                }

                if(typeof self._comments[containerid] == 'undefined' || self._comments[containerid] == []) {
                    self.removeComment(this);
                }
                else {
                    var found = false;
                    jQuery.each(self._comments[containerid], function(containerid, value) {
                        if(value['id'] == commentId) {
                            found = true;
                        }
                    });
                    if(!found) {
                        self.removeComment(this);
                    }
                }
            });


            // delete stored comment mark object, for some reason JSON.stringify will convert it to jQuery object
            for (var i = 0; i < self._comments[containerid].length; i++) {
                self._comments[containerid][i]['commentMark'] = null;
            };

        })


        // if we are in Edit+
        if(typeof EasyEditEventManager != 'undefined') {
            callback.call(this, self._comments);
        }
        else {
            jQuery.each(self._comments, function(containerid, value) {
                    jQuery('#container_comments_' + containerid).val(encodeURIComponent(JSON.stringify(value)));
            });
        }

    },


    /*
    * position comment mark on the screen
    */
    _positionCommentMark: function(element, commentMark)
    {
        // those offsets are used in Edit+
        var commentMarkOffetLeft = 14;
        if(this._inEditPlus) {
            commentMarkOffetLeft = 35;
        }
        var commentMarkOffetTop = -14;

        // sometime we have position comment before viper has editable element set,then we have to use registered element.
        var editableElement = this._bodycopyContainer.get(0);
        if(typeof editableElement == 'undefined' || !editableElement) return;

        var editableOffset = $(editableElement).offset();
        var currentElementOffset = $(element).offset();
        var commentMarkTop = currentElementOffset.top + $(element).height()/2 + commentMarkOffetTop;
        var commentMarkLeft = editableOffset.left + $(editableElement).width() + commentMarkOffetLeft;
        var elementLeft = currentElementOffset.left;
        var elementTop = currentElementOffset.top;


        this._moveCommentMarkRecursive(commentMark, commentMarkTop, commentMarkLeft, elementTop, elementLeft);

    },

    _moveCommentMarkRecursive: function(commentMark, commentMarkTop, commentMarkLeft, elementTop, elementLeft)
    {
        var self = this;
        var commentId = $(commentMark).data('comment-id');
        var containerId = $(commentMark).data('comment-container-id');
        var overlappingFound = false;

        if(typeof this._commentsPositions[containerId] == 'undefined') {
            this._commentsPositions[containerId] = [];
        }

        // loop through existing comments positions
        jQuery.each(this._commentsPositions[containerId], function(key, value) {
            // we found an overlapping mark
            if(value['id'] != commentId && (commentMarkTop < value['commentMarkTop'] + self._commentMarkVerticalSpace) && (value['commentMarkTop'] < commentMarkTop + self._commentMarkVerticalSpace)) {
                overlappingFound = true;
                insertBelow = true;
                if(value['commentMarkTop'] < commentMarkTop) {
                    // insert it below the overlapping mark
                    insertBelow = true;
                }
                else if (value['commentMarkTop'] > commentMarkTop) {
                    // remove the offending element, and insert it after we inserted ours first
                    insertBelow = false;
                }
                else {
                     if(value['elementTop'] > elementTop) {
                        insertBelow = false;
                    }
                    else if (value['elementTop'] < elementTop) {
                        insertBelow = true;
                    }
                    else {
                        if(value['elementLeft'] > elementLeft) {
                            // new mark should be placed before existing one
                            insertBelow = false;
                        }
                        else if (value['elementLeft'] < elementLeft){
                            // existing one goes first
                            insertBelow = true;
                        }
                        else {
                            // element top and left is exactly same as the existing marked elemnt, it is possible, e.g first word of the sentance
                            insertBelow = true;
                        }
                    }
                }

                if(insertBelow) {
                    newTop = value['commentMarkTop'] + self._commentMarkVerticalSpace;
                    self._moveCommentMarkRecursive(commentMark, newTop, commentMarkLeft, elementTop, elementLeft);
                }
                else {
                    // splice out the current mark position, and re-insert the new mark and the current one as well
                    self._commentsPositions[containerId].splice(key, 1);
                    self._moveCommentMarkRecursive(commentMark, commentMarkTop, commentMarkLeft, elementTop, elementLeft);
                    self._moveCommentMarkRecursive(value['commentMark'], value['commentMarkTop'], value['commentMarkLeft'], value['elementTop'], value['elementLeft']);
                }


                // let's quit the loop, already found the place to insert
                return false;
            }
        });

        if (!overlappingFound) {
            // move the mark to position
            $(commentMark).css({top: commentMarkTop, left: commentMarkLeft});

            // store the positions
            var newPosition = {'id' : commentId, 'commentMarkTop' : commentMarkTop, 'commentMarkLeft' : commentMarkLeft, 'elementTop' : elementTop, 'elementLeft' : elementLeft, 'commentMark' : commentMark};
            var found = false;
            jQuery.each(self._commentsPositions[containerId], function(key, value) {
                if(value['id'] == commentId) {
                    self._commentsPositions[containerId][key] = newPosition;
                    found = true;
                }
            });
            if(!found) {
                self._commentsPositions[containerId].push(newPosition);
            }
        }
    },


    _positionCommentDialog: function(commentMark, commentDialog)
    {
        var pos = $(commentMark).position();
        var width = $(commentMark).outerWidth();

        // the dialog position is different in admin interface
        if(this._inEditPlus) {
            var widthoffset = 20;
            var heightoffset = -50;
        }
        else {
            var widthoffset = -242;
            var heightoffset = 41;
        }

        $(commentDialog).css({
            position: "absolute",
            top: pos.top + heightoffset + "px",
            left: (pos.left + width + widthoffset) + "px"
        }).show();

    },

    updateExistingComments: function()
    {
        var self = this;
        self._commentsPositions = [];
        if(typeof this._comments[self._containerId] == 'undefined') return;

        $('div[data-container-id]').each(function () {
            var $container = $(this);
            self._bodycopyContainer = $container;
            var currentContainerId = $container.data('container-id');
            for(var i = 0; i< self._comments[currentContainerId].length; i++) {
                value = self._comments[currentContainerId][i];
                    var targetElement = $container.find('[data-comment-id=' + value['id'] + '][data-comment-container-id=' + currentContainerId + ']').get(0);
                    if(targetElement) {
                        self._positionCommentMark(targetElement, value['commentMark']);
                        if($('#Matrix-Viper-commentdialog-' + value['containerid'] + '-' + value['id']).length) {
                            self._positionCommentDialog(value['commentMark'], $('#Matrix-Viper-commentdialog-' + value['containerid'] + '-' + value['id']).get(0));
                        }
                        $(value['commentMark']).show();
                    }
                    else {
                        $(value['commentMark']).hide();
                    }
            }
        })

    },



    /**
     * Updates readable age strings.
     *
     * @param DomElement startElement The element to search under (optional).
     *
     * @see    dfx.readableAge()
     * @return void
     */
    _updateReadableAges : function (startElement)
    {
        var self = this;
        // A 1 minute interval for counting up.
        setInterval(function() {
            var dates = jQuery(startElement).find('.readableAge');
            jQuery.each(dates, function(id) {
                var elem      = dates[id];
                var timestamp = parseInt(jQuery.attr(elem, 'data-timestamp'), 10);
                var now       = jQuery.now();

                var secs = Math.abs((now / 1000 - timestamp / 1000));
                // If 30+ days no longer needs updating.
                // Give extra 60 sec to allow tick over.
                if (secs > (2592000 + 60)) {
                    jQuery.removeClass(elem, 'readableAge');
                } else {
                    jQuery(elem).html(self._readableAge(timestamp, now));
                }
            });
        }, 60000);

    },


    /**
     * Returns readable age of specified timestamp.
     * Keep up to date with dfx.updateReadableAges().
     *
     * @param int timestamp Timestamp in seconds.
     * @param int now       Current timestamp in seconds.
     *
     * @return string
     */
    _readableAge : function(timestamp, now)
    {
        var month = new Array();
        month[0] = "Jan";
        month[1] = "Feb";
        month[2] = "Mar";
        month[3] = "Apr";
        month[4] = "May";
        month[5] = "Jun";
        month[6] = "Jul";
        month[7] = "Aug";
        month[8] = "Sep";
        month[9] = "Oct";
        month[10] = "Nov";
        month[11] = "Dec";
        date = new Date(timestamp);
        currentDate = new Date(now);
        var secs = (now / 1000 - timestamp / 1000);
        var ago  = ' ago';
        var fn   = 'floor';
        if (secs < 0) {
            secs = (-secs);
            ago  = '';
            fn   = 'ceil';
        }

        if (secs > 2592000) {
            // More than 30 days.
            var result = month[date.getMonth()+1] + ' ' + date.getDate();
            var year = date.getFullYear();
            var currentYear = currentDate.getFullYear();
            if (year !== currentYear) {
                result = result + ', ' + year;
            }
        } else if (secs > 86400) {
            // More than 24 hours.
            var unit = Math[fn]((secs / 86400));
            if (unit > 1) {
                var result = unit + ' days' + ago;
            } else {
                var result = unit + ' day' + ago;
            }
        } else if (secs > 3600) {
            // More than 60 minutes.
            var unit = Math[fn]((secs / 3600));
            if (unit > 1) {
                var result = unit + ' hours' + ago;
            } else {
                var result = unit + ' hour' + ago;
            }
        } else if (secs > 60) {
            // More than 1 minute.
            var unit = Math[fn]((secs / 60));
            if (unit > 1) {
                var result = unit + ' minutes' + ago;
            } else {
                var result = unit + ' minute' + ago;
            }
        } else {
            var result = 'Just now';
        }//end if

        return result;

    },


    /**
     * Returns the formatted date
     *
     * @param int timestamp Timestamp in seconds.
     *
     * @return string
     */
    _formattedDate : function(timestamp)
    {
        var date = new Date(timestamp);

        var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        var months = ["January", "February", "March", "April", "May", 
        "June", "July", "August", "September", "October", "November", "December"];
        var pad = function(str) { str = String(str); return (str.length < 2) ? "0" + str : str; }

        var meridian = (parseInt(date.getHours() / 12) == 1) ? 'PM' : 'AM';
        var hours = date.getHours() > 12 ? date.getHours() - 12 : date.getHours();
        return hours + ':' + pad(date.getMinutes()) + ':' + pad(date.getSeconds())
        + ' ' + meridian + ' ' + days[date.getDay()] + ' ' + date.getDate() + ' ' + months[date.getMonth()] + ' ' 
        + date.getFullYear();
    },


    /*
    *   encode the comment content for security
    */
    _htmlEncode : function (value){
        //create a in-memory div, set it's inner text(which jQuery automatically encodes)
        //then grab the encoded contents back out.  The div never exists on the page.
        var newValue = $('<div/>').text(value).html();
        // replace new line with <br/>
        newValue = String(newValue).replace(/(?:\r\n|\r|\n)/g, '<br />');

        // we need <a> link still work though

        newValue = String(newValue).replace(/(http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?/, function(match, $1, $2, offset, original) {
            return '<a href="' + match + '">' + match + '</a>';
        })

        return newValue;
    }

};
