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

ViperReadyCallback = null;
(function() {
        var dfxScripts = document.getElementsByTagName('script');
        var path       = null;

        // Loop through all the script tags that exist in the document and find the one
        // that has included this file.
        var dfxScriptsLen = dfxScripts.length;
        for (var i = 0; i < dfxScriptsLen; i++) {
            if (dfxScripts[i].src) {
                if (dfxScripts[i].src.match(/Viper-matrix\.js/)) {
                    // We have found our appropriate <script> tag that includes the
                    // DfxJSLib library, so we can extract the path and include the rest.
                    path = dfxScripts[i].src.replace(/Viper-matrix\.js/,'');
                    break;
                }
            }
        }

        var _loadScript = function(path, scriptName, callback, scriptNameAsPath) {
            var script = document.createElement('script');

            if (navigator.appName == 'Microsoft Internet Explorer') {
                var rv = -1;
                var re = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
                if (re.exec(navigator.userAgent) != null) {
                    rv = parseFloat(RegExp.$1);
                }

                if (rv <= 8.0) {
                    script.onreadystatechange = function() {
                        if (/^(loaded|complete)$/.test(this.readyState) === true) {
                            callback.call(window);
                        }
                    };
                }
            }//end if

            script.onload = function() {
                callback.call(window);
            };

            if (scriptNameAsPath === true) {
                script.src = path + scriptName + '/' + scriptName + '.js';
            } else {
                script.src = path + scriptName;
            }

            if (document.head) {
                document.head.appendChild(script);
            } else {
                document.getElementsByTagName('head')[0].appendChild(script);
            }
        };
        var _loadScripts = function(path, scripts, callback, scriptNameAsPath) {
            if (scripts.length === 0) {
                callback.call(window);
                return;
            }

            var script = scripts.shift();
            _loadScript(path, script, function() {
                _loadScripts(path, scripts, callback, scriptNameAsPath);
            }, scriptNameAsPath);
        };

        // Viper core files.
        var jsFiles = 'ViperUtil.js|Viper.js|ViperTranslation.js|ViperChangeTracker.js|ViperTools.js|ViperDOMRange.js|ViperIERange.js|ViperMozRange.js|ViperSelection.js|ViperPluginManager.js|ViperHistoryManager.js';
        jsFiles     = jsFiles.split('|');

        _loadScripts(path + '/Lib/', jsFiles, function() {
            var plugins    = 'ViperCopyPastePlugin|ViperToolbarPlugin|ViperInlineToolbarPlugin|ViperCoreStylesPlugin|ViperFormatPlugin|ViperKeyboardEditorPlugin|ViperListPlugin|ViperHistoryPlugin|ViperTableEditorPlugin|ViperTrackChangesPlugin|ViperLinkPlugin|MatrixLinkPlugin|ViperAccessibilityPlugin|ViperSourceViewPlugin|ViperImagePlugin|MatrixImagePlugin|ViperSearchReplacePlugin|ViperLangToolsPlugin|ViperCharMapPlugin|MatrixLinkPlugin|MatrixCopyPastePlugin';
            plugins        = plugins.split('|');

            _loadScripts(path + 'Plugins/', plugins.concat([]), function() {
                if (ViperReadyCallback) {
                    ViperReadyCallback.call(window);
                }
            }, true);


            var coreCSS = 'viper|viper_tools|viper_tools.ees|viper_moz'.split('|');
            for (var j = 0; j < coreCSS.length; j++) {
                var link   = document.createElement('link');
                link.rel   = 'stylesheet';
                link.media = 'screen';
                link.href  = path + 'Css/' + coreCSS[j] + '.css';
                document.getElementsByTagName('head')[0].appendChild(link);
            }

            for (var j = 0; j < plugins.length; j++) {
                var link   = document.createElement('link');
                link.rel   = 'stylesheet';
                link.media = 'screen';
                link.href  = path + 'Plugins/' + plugins[j] + '/' + plugins[j] + '.css';
                document.getElementsByTagName('head')[0].appendChild(link);
            }
        });
}) ();