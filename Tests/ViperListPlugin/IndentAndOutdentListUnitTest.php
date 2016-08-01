<?php

require_once 'AbstractViperListPluginUnitTest.php';

class Viper_Tests_ViperListPlugin_IndentAndOutdentListUnitTest extends AbstractViperListPluginUnitTest
{

    /**
     * Test outdent and indent a list item works.
     *
     * @return void
     */
    public function testListItem()
    {
        //Test indent and outdent when clicking inside a list item
        foreach (array('ol', 'ul') as $listType) {
            foreach ($this->getTestMethods(TRUE, FALSE, TRUE) as $method) {
                if ($listType === 'ul') {
                    $this->useTest(1);
                    $ulStatus = 'active';
                    $olStatus = TRUE;
                } else {
                    $this->useTest(2);
                    $ulStatus = TRUE;
                    $olStatus = 'active';
                }

                $this->moveToKeyword(2);
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);
                $this->doAction($method, 'listOutdent');
                sleep(1);
                $this->assertIconStatusesCorrect(TRUE, TRUE, TRUE, NULL);
                $this->assertHTMLMatch('<p>List:</p><'.$listType.'><li>%1% first item</li></'.$listType.'><p>second item %2%</p><'.$listType.'><li>third %3% item</li></'.$listType.'>');
                $this->doTopToolbarAction($method, 'listIndent');
                sleep(1);
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);
                $this->assertHTMLMatch('<p>List:</p><'.$listType.'><li>%1% first item</li><li>second item %2%</li><li>third %3% item</li></'.$listType.'>');
            }
        }

        //Test indent and outdent unordered when selecting a word in the list item
        foreach (array('ol', 'ul') as $listType) {
            foreach ($this->getTestMethods(TRUE, TRUE, TRUE) as $method) {
                if ($listType === 'ul') {
                    $this->useTest(1);
                    $ulStatus = 'active';
                    $olStatus = TRUE;
                } else {
                    $this->useTest(2);
                    $ulStatus = TRUE;
                    $olStatus = 'active';
                }

                $this->selectKeyword(2);
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);
                $this->doAction($method, 'listOutdent');
                $this->assertIconStatusesCorrect(TRUE, TRUE, TRUE, NULL);
                $this->assertHTMLMatch('<p>List:</p><'.$listType.'><li>%1% first item</li></'.$listType.'><p>second item %2%</p><'.$listType.'><li>third %3% item</li></'.$listType.'>');
                $this->doTopToolbarAction($method, 'listIndent');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);
                $this->assertHTMLMatch('<p>List:</p><'.$listType.'><li>%1% first item</li><li>second item %2%</li><li>third %3% item</li></'.$listType.'>');
            }
        }

        //Test indent and outdent unordered when selecting the list item
        foreach (array('ol', 'ul') as $listType) {
            foreach ($this->getTestMethods(TRUE, TRUE, TRUE) as $method) {
                if ($listType === 'ul') {
                    $this->useTest(1);
                    $ulStatus = 'active';
                    $olStatus = TRUE;
                } else {
                    $this->useTest(2);
                    $ulStatus = TRUE;
                    $olStatus = 'active';
                }

                $this->selectKeyword(2);
                $this->selectInlineToolbarLineageItem(1);
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);
                $this->doAction($method, 'listOutdent');
                $this->assertIconStatusesCorrect(TRUE, TRUE, TRUE, NULL);
                $this->assertHTMLMatch('<p>List:</p><'.$listType.'><li>%1% first item</li></'.$listType.'><p>second item %2%</p><'.$listType.'><li>third %3% item</li></'.$listType.'>');
                $this->doTopToolbarAction($method, 'listIndent');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);
                $this->assertHTMLMatch('<p>List:</p><'.$listType.'><li>%1% first item</li><li>second item %2%</li><li>third %3% item</li></'.$listType.'>');
            }
        }

    }//end testListItem()


    /**
     * Test that when you select a list, the outdent and indent icon works.
     *
     * @return void
     */
    public function testAllItemsInAList()
    {
        foreach (array('ol', 'ul') as $listType) {
            foreach ($this->getTestMethods(TRUE, TRUE, TRUE) as $method) {
                if ($listType === 'ul') {
                    $this->useTest(1);
                    $ulStatus = 'active';
                    $olStatus = TRUE;
                } else {
                    $this->useTest(2);
                    $ulStatus = TRUE;
                    $olStatus = 'active';
                }

                $this->selectKeyword(2);
                sleep(1);
                $this->selectInlineToolbarLineageItem(0);
                sleep(1);
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, FALSE, TRUE);
                $this->doAction($method, 'listOutdent');
                $this->assertIconStatusesCorrect(TRUE, TRUE, TRUE, FALSE);
                $this->assertHTMLMatch('<p>List:</p><p>%1% first item</p><p>second item %2%</p><p>third %3% item</p>');
                $this->doTopToolbarAction($method, 'listIndent');
                $this->assertIconStatusesCorrect('active', TRUE, FALSE, TRUE);
                $this->assertHTMLMatch('<p>List:</p><ul><li>%1% first item</li><li>second item %2%</li><li>third %3% item</li></ul>');
            }
        }

    }//end testAllItemsInAList()


    /**
     * Test that outdent and indent works for the first item in the list.
     *
     * @return void
     */
    public function testFirstItemInList()
    {
        foreach (array('ol', 'ul') as $listType) {
            foreach ($this->getTestMethods(TRUE, TRUE, TRUE) as $method) {
                if ($listType === 'ul') {
                    $this->useTest(1);
                    $ulStatus = 'active';
                    $olStatus = TRUE;
                } else {
                    $this->useTest(2);
                    $ulStatus = TRUE;
                    $olStatus = 'active';
                }

                $this->selectKeyword(1);
                $this->doAction($method, 'listOutdent');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, FALSE);
                $this->assertHTMLMatch('<p>List:</p><p>%1% first item</p><'.$listType.'><li>second item %2%</li><li>third %3% item</li></'.$listType.'>');
                $this->doTopToolbarAction($method, 'listIndent');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, FALSE, TRUE);
                $this->assertHTMLMatch('<p>List:</p><'.$listType.'><li>%1% first item</li><li>second item %2%</li><li>third %3% item</li></'.$listType.'>');
            }
        }

    }//end testFirstItemInList()


    /**
     * Test that outdent and indent works for the last item in the list.
     *
     * @return void
     */
    public function testLastItemInList()
    {
        foreach (array('ol', 'ul') as $listType) {
            foreach ($this->getTestMethods(TRUE, TRUE, TRUE) as $method) {
                if ($listType === 'ul') {
                    $this->useTest(1);
                    $ulStatus = 'active';
                    $olStatus = TRUE;
                } else {
                    $this->useTest(2);
                    $ulStatus = TRUE;
                    $olStatus = 'active';
                }

                $this->selectKeyword(3);
                $this->doAction($method, 'listOutdent');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, FALSE);
                $this->assertHTMLMatch('<p>List:</p><'.$listType.'><li>%1% first item</li><li>second item %2%</li></'.$listType.'><p>third %3% item</p>');
                $this->doTopToolbarAction($method, 'listIndent');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);
                $this->assertHTMLMatch('<p>List:</p><'.$listType.'><li>%1% first item</li><li>second item %2%</li><li>third %3% item</li></'.$listType.'>');
            }
        }

    }//end testLastItemInList()


    /**
     * Test that indent creates a sub list and outdent adds it back to the master list.
     *
     * @return void
     */
    public function testCreateListWithIndent()
    {
        foreach (array('ol', 'ul') as $listType) {
            foreach ($this->getTestMethods(TRUE, FALSE, TRUE) as $method) {
                if ($listType === 'ul') {
                    $this->useTest(1);
                    $ulStatus = 'active';
                    $olStatus = TRUE;
                } else {
                    $this->useTest(2);
                    $ulStatus = TRUE;
                    $olStatus = 'active';
                }

                $this->moveToKeyword(3);
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);
                $this->doAction($method, 'listIndent');
                $this->assertHTMLMatch('<p>List:</p><'.$listType.'><li>%1% first item</li><li>second item %2%<'.$listType.'><li>third %3% item</li></'.$listType.'></li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, FALSE, TRUE);
                $this->doAction($method, 'listOutdent');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);
                $this->assertHTMLMatch('<p>List:</p><'.$listType.'><li>%1% first item</li><li>second item %2%</li><li>third %3% item</li></'.$listType.'>');
            }
        }

    }//end testCreateListWithIndent()


    /**
     * Test that you can indent and outdent mulitple items multiple time.
     *
     * @return void
     */
    public function testOutdentAndIndentListItemsMultipleTimes()
    {
        foreach (array('ol', 'ul') as $listType) {
            foreach ($this->getTestMethods(TRUE, FALSE, TRUE) as $method) {
                if ($listType === 'ul') {
                    $this->useTest(1);
                } else {
                    $this->useTest(2);
                }

                $this->selectKeyword(1, 2);
                $this->doAction($method, 'listOutdent');
                $this->assertHTMLMatch('<p>List:</p><p>%1% first item</p><p>second item %2%</p><'.$listType.'><li>third %3% item</li></'.$listType.'>');
                $this->selectKeyword(1, 2);
                $this->doAction($method, 'listIndent');
                $this->assertHTMLMatch('<p>List:</p><'.$listType.'><li>%1% first item</li><li>second item %2%</li><li>third %3% item</li></'.$listType.'>');
                $this->selectKeyword(1, 2);
                $this->doAction($method, 'listOutdent');
                $this->assertHTMLMatch('<p>List:</p><p>%1% first item</p><p>second item %2%</p><'.$listType.'><li>third %3% item</li></'.$listType.'>');
                $this->selectKeyword(1, 2);
                $this->doAction($method, 'listIndent');
                $this->assertHTMLMatch('<p>List:</p><'.$listType.'><li>%1% first item</li><li>second item %2%</li><li>third %3% item</li></'.$listType.'>');
            }
        }

    }//end testOutdentAndIndentListItemsMultipleTimes()


    /**
     * Test that you cannot indent the first item in the list.
     *
     * @return void
     */
    public function testCannotIndentFirstItemInList()
    {
        foreach (array('ol', 'ul') as $listType) {

                if ($listType === 'ul') {
                    $this->useTest(1);
                    $ulStatus = 'active';
                    $olStatus = TRUE;
                } else {
                    $this->useTest(2);
                    $ulStatus = TRUE;
                    $olStatus = 'active';
                }

            $this->moveToKeyword(1);
            $this->assertIconStatusesCorrect($ulStatus, $olStatus, FALSE, TRUE);
            // Make sure multiple tabs dont cause issues.
            $this->sikuli->keyDown('Key.TAB');
            $this->sikuli->keyDown('Key.TAB');
            $this->sikuli->keyDown('Key.TAB');
            $this->sikuli->keyDown('Key.TAB');
            $this->assertHTMLMatch('<p>List:</p><'.$listType.'><li>%1% first item</li><li>second item %2%</li><li>third %3% item</li></'.$listType.'>');
        }

    }//end testCannotIndentFirstItemInList()


    /**
     * Test indent and outdent keeps text selection and styles can be applied to multiple list elements.
     *
     * @return void
     */
    public function testKeepingSelectionAndStylesApplied()
    {
        foreach (array('ol', 'ul') as $listType) {

                if ($listType === 'ul') {
                    $this->useTest(1);
                    $ulStatus = 'active';
                    $olStatus = TRUE;
                } else {
                    $this->useTest(2);
                    $ulStatus = TRUE;
                    $olStatus = 'active';
                }

            $this->selectKeyword(2, 3);
            $this->sikuli->keyDown('Key.TAB');
            $this->sikuli->keyDown('Key.CMD + b');
            $this->assertHTMLMatch('<p>List:</p><'.$listType.'><li>%1% first item<'.$listType.'><li>second item <strong>%2%</strong></li><li><strong>third %3%</strong> item</li></'.$listType.'></li></'.$listType.'>');
            $this->sikuli->keyDown('Key.SHIFT + Key.TAB');
            $this->sikuli->keyDown('Key.CMD + b');
            $this->assertHTMLMatch('<p>List:</p><'.$listType.'><li>%1% first item</li><li>second item %2%</li><li>third %3% item</li></'.$listType.'>');
        }

    }//end testKeepingSelectionAndStylesApplied()


    /**
     * Tests that shift+tab in a non list item does nothing.
     *
     * @return void
     */
    public function testShiftTabInNonListItem()
    {
        //Test unordered list
        $this->useTest(1);
        $this->moveToKeyword(1);
        $this->sikuli->keyDown('Key.UP');
        $this->sikuli->keyDown('Key.SHIFT + Key.TAB');
        $this->sikuli->keyDown('Key.SHIFT + Key.TAB');
        $this->sikuli->keyDown('Key.SHIFT + Key.TAB');
        $this->assertHTMLMatch('<p>List:</p><ul><li>%1% first item</li><li>second item %2%</li><li>third %3% item</li></ul>');

        //Test ordered list
        $this->useTest(2);
        $this->moveToKeyword(1);
        $this->sikuli->keyDown('Key.UP');
        $this->sikuli->keyDown('Key.SHIFT + Key.TAB');
        $this->sikuli->keyDown('Key.SHIFT + Key.TAB');
        $this->sikuli->keyDown('Key.SHIFT + Key.TAB');
        $this->assertHTMLMatch('<p>List:</p><ol><li>%1% first item</li><li>second item %2%</li><li>third %3% item</li></ol>');

    }//end testShiftTabInNonListItem()


    /**
     * Test indenting and outdenting items in a list using keyboard navigation only.
     *
     * @return void
     */
    public function testListKeyboardNavForList()
    {
        foreach (array('ol', 'ul') as $listType) {

                if ($listType === 'ul') {
                    $this->useTest(1);
                    $ulStatus = 'active';
                    $olStatus = TRUE;
                } else {
                    $this->useTest(2);
                    $ulStatus = TRUE;
                    $olStatus = 'active';
                }

            $this->moveToKeyword(2);
            $this->sikuli->keyDown('Key.TAB');
            sleep(1);
            $this->sikuli->keyDown('Key.DOWN');
            sleep(1);
            $this->sikuli->keyDown('Key.TAB');
            sleep(1);
            $this->assertHTMLMatch('<p>List:</p><'.$listType.'><li>%1% first item<'.$listType.'><li>second item %2%</li><li>third %3% item</li></'.$listType.'></li></'.$listType.'>');

            $this->sikuli->keyDown('Key.UP');
            sleep(1);
            $this->sikuli->keyDown('Key.SHIFT + Key.TAB');
            sleep(1);
            $this->assertHTMLMatch('<p>List:</p><'.$listType.'><li>%1% first item</li><li>second item %2%<'.$listType.'><li>third %3% item</li></'.$listType.'></li></'.$listType.'>');

            $this->sikuli->keyDown('Key.TAB');
            sleep(1);
            $this->sikuli->keyDown('Key.DOWN');
            sleep(1);
            $this->sikuli->keyDown('Key.TAB');
            sleep(1);
            $this->assertHTMLMatch('<p>List:</p><'.$listType.'><li>%1% first item<'.$listType.'><li>second item %2%<'.$listType.'><li>third %3% item</li></'.$listType.'></li></'.$listType.'></li></'.$listType.'>');

            $this->sikuli->keyDown('Key.UP');
            sleep(1);
            $this->sikuli->keyDown('Key.SHIFT + Key.TAB');
            sleep(1);
            $this->assertHTMLMatch('<p>List:</p><'.$listType.'><li>%1% first item</li><li>second item %2%<'.$listType.'><li>third %3% item</li></'.$listType.'></li></'.$listType.'>');
        }

    }//end testListKeyboardNavForList()


    /**
     * Test that you can outdent all items in the sub list and indent them again.
     *
     * @return void
     */
    public function testAllSubListItems()
    {
        foreach (array('ol', 'ul') as $listType) {
            foreach ($this->getTestMethods(TRUE, TRUE, TRUE) as $method) {

                if ($listType === 'ul') {
                    $this->useTest(3);
                    $ulStatus = 'active';
                    $olStatus = TRUE;
                } else {
                    $this->useTest(4);
                    $ulStatus = TRUE;
                    $olStatus = 'active';
                }

                $this->selectKeyword(2);
                sleep(1);
                $this->selectInlineToolbarLineageItem(2);
                sleep(1);
                $this->doAction($method, 'listOutdent');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);
                $this->assertHTMLMatch('<p>List:</p><'.$listType.'><li>first item</li><li>second item</li><li>first sub item %1%</li><li>second sub item %2%</li><li>third sub item %3%</li><li>third item</li></'.$listType.'>');
                sleep(1);
                $this->doTopToolbarAction($method, 'listIndent');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, FALSE, TRUE);
                $this->assertHTMLMatch('<p>List:</p><'.$listType.'><li>first item</li><li>second item<'.$listType.'><li>first sub item %1%</li><li>second sub item %2%</li><li>third sub item %3%</li></'.$listType.'></li><li>third item</li></'.$listType.'>');
            }
        }

    }//end testAllSubListItems()


    /**
     * Test outdent and indent first item in a sub lit.
     *
     * @return void
     */
    public function testFirstItemInSubList()
    {
        foreach (array('ol', 'ul') as $listType) {
            foreach ($this->getTestMethods(TRUE, TRUE, TRUE) as $method) {

                if ($listType === 'ul') {
                    $this->useTest(3);
                    $ulStatus = 'active';
                    $olStatus = TRUE;
                } else {
                    $this->useTest(4);
                    $ulStatus = TRUE;
                    $olStatus = 'active';
                }

                $this->selectKeyword(1);
                $this->doAction($method, 'listOutdent');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);
                $this->assertHTMLMatch('<p>List:</p><'.$listType.'><li>first item</li><li>second item</li><li>first sub item %1%<'.$listType.'><li>second sub item %2%</li><li>third sub item %3%</li></'.$listType.'></li><li>third item</li></'.$listType.'>');
                $this->doTopToolbarAction($method, 'listIndent');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, FALSE, TRUE);
                $this->assertHTMLMatch('<p>List:</p><'.$listType.'><li>first item</li><li>second item<'.$listType.'><li>first sub item %1%</li><li>second sub item %2%</li><li>third sub item %3%</li></'.$listType.'></li><li>third item</li></'.$listType.'>');
            }
        }

    }//end testFirstItemInSubList()


    /**
     * Test outdent and indent two unordered sub list items when selecting both list items.
     *
     * @return void
     */
    public function testTwoSubListItems()
    {
        foreach (array('ol', 'ul') as $listType) {
            foreach ($this->getTestMethods(TRUE, TRUE, TRUE) as $method) {

                if ($listType === 'ul') {
                    $this->useTest(5);
                    $ulStatus = 'active';
                    $olStatus = TRUE;
                } else {
                    $this->useTest(6);
                    $ulStatus = TRUE;
                    $olStatus = 'active';
                }

                $this->selectKeyword(2, 4);

                // Outdent once so list items are at the top level
                $this->doAction($method, 'listOutdent');
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1%<'.$listType.'><li>Audit of Homepage and 6 Section Landing pages</li></'.$listType.'></li><li>%2% test %3%</li><li>Accessibility audit report %4%</li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);

                // Outdent again so list items are no longer in list
                $this->doAction($method, 'listOutdent');
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1%<'.$listType.'><li>Audit of Homepage and 6 Section Landing pages</li></'.$listType.'></li></'.$listType.'><p>%2% test %3%</p><p>Accessibility audit report %4%</p>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, FALSE);

                // Indent once so they are back in the list
                $this->doTopToolbarAction($method, 'listIndent');
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1%<'.$listType.'><li>Audit of Homepage and 6 Section Landing pages</li></'.$listType.'></li><li>%2% test %3%</li><li>Accessibility audit report %4%</li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);

                // Indent again so they added to the sub list
                $this->doAction($method, 'listIndent');
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1%<'.$listType.'><li>Audit of Homepage and 6 Section Landing pages</li><li>%2% test %3%</li><li>Accessibility audit report %4%</li></'.$listType.'></li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);

                // Indent again so a third level list is created
                $this->doAction($method, 'listIndent');
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1%<'.$listType.'><li>Audit of Homepage and 6 Section Landing pages<'.$listType.'><li>%2% test %3%</li><li>Accessibility audit report %4%</li></'.$listType.'></li></'.$listType.'></li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, FALSE, TRUE);
            }
        }

    }//end testTwoSubListItems()


    /**
     * Test selecting one list item, pressing shift + right once to select the start of the next list item and outdenting and indenting content
     *
     * @return void
     */
    public function testTwoSubListItemsWithLiSelection()
    {
        foreach (array('ol', 'ul') as $listType) {
            foreach ($this->getTestMethods(TRUE, TRUE, TRUE) as $method) {

                if ($listType === 'ul') {
                    $this->useTest(5);
                    $ulStatus = 'active';
                    $olStatus = TRUE;
                } else {
                    $this->useTest(6);
                    $ulStatus = TRUE;
                    $olStatus = 'active';
                }

                $this->selectKeyword(2);
                sleep(1);
                $this->selectInlineToolbarLineageItem(3);
                $this->sikuli->keyDown('Key.SHIFT + Key.RIGHT');

                // Outdent once so list items are at the top level
                $this->doAction($method, 'listOutdent');
                sleep(1);
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1%<'.$listType.'><li>Audit of Homepage and 6 Section Landing pages</li></'.$listType.'></li><li>%2% test %3%<'.$listType.'><li>Accessibility audit report %4%</li></'.$listType.'></li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);

                // Outdent again so list items are no longer in list
                $this->doAction($method, 'listOutdent');
                sleep(1);
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1%<'.$listType.'><li>Audit of Homepage and 6 Section Landing pages</li></'.$listType.'></li></'.$listType.'><p>%2% test %3%</p><'.$listType.'><li>Accessibility audit report %4%</li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, FALSE);

                // Indent once so they are back in the list
                $this->doTopToolbarAction($method, 'listIndent');
                sleep(1);
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1%<'.$listType.'><li>Audit of Homepage and 6 Section Landing pages</li></'.$listType.'></li><li>%2% test %3%</li><li>Accessibility audit report %4%</li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);

                // Indent again so they added to the sub list
                $this->doAction($method, 'listIndent');
                sleep(1);
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1%<'.$listType.'><li>Audit of Homepage and 6 Section Landing pages</li><li>%2% test %3%</li></'.$listType.'></li><li>Accessibility audit report %4%</li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);

                // Indent again so a third level list is created
                $this->doAction($method, 'listIndent');
                sleep(1);
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1% <'.$listType.'><li>Audit of Homepage and 6 Section Landing pages<'.$listType.'><li>%2% test %3%</li></'.$listType.'></li></'.$listType.'></li><li>Accessibility audit report %4%</li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, FALSE, TRUE);
            }
        }

    }//end testTwoSubListItemsWithLiSelection()


    /**
     * Test selecting one list item, pressing shift + right twice and outdenting and indenting content
     *
     * @return void
     */
    public function testTwoSubListItemsWithPartialSelection()
    {
        foreach (array('ol', 'ul') as $listType) {
            foreach ($this->getTestMethods(TRUE, TRUE, TRUE) as $method) {

                if ($listType === 'ul') {
                    $this->useTest(5);
                    $ulStatus = 'active';
                    $olStatus = TRUE;
                } else {
                    $this->useTest(6);
                    $ulStatus = TRUE;
                    $olStatus = 'active';
                }

                $this->selectKeyword(2);
                $this->selectInlineToolbarLineageItem(3);
                $this->sikuli->keyDown('Key.SHIFT + Key.RIGHT');
                $this->sikuli->keyDown('Key.SHIFT + Key.RIGHT');

                // Outdent once so list items are at the top level
                $this->doAction($method, 'listOutdent');
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1%<'.$listType.'><li>Audit of Homepage and 6 Section Landing pages</li></'.$listType.'></li><li>%2% test %3%</li><li>Accessibility audit report %4%</li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);

                // Outdent again so list items are no longer in list
                $this->doAction($method, 'listOutdent');
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1%<'.$listType.'><li>Audit of Homepage and 6 Section Landing pages</li></'.$listType.'></li></'.$listType.'><p>%2% test %3%</p><p>Accessibility audit report %4%</p>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, FALSE);

                // Indent once so they are back in the list
                $this->doTopToolbarAction($method, 'listIndent');
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1%<'.$listType.'><li>Audit of Homepage and 6 Section Landing pages</li></'.$listType.'></li><li>%2% test %3%</li><li>Accessibility audit report %4%</li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);

                // Indent again so they added to the sub list
                $this->doAction($method, 'listIndent');
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1%<'.$listType.'><li>Audit of Homepage and 6 Section Landing pages</li><li>%2% test %3%</li><li>Accessibility audit report %4%</li></'.$listType.'></li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);

                // Indent again so a third level list is created
                $this->doAction($method, 'listIndent');
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1%<'.$listType.'><li>Audit of Homepage and 6 Section Landing pages<'.$listType.'><li>%2% test %3%</li><li>Accessibility audit report %4%</li></'.$listType.'></li></'.$listType.'></li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, FALSE, TRUE);
            }
        }

    }//end testTwoSubListItemsWithPartialSelection()


    /**
     * Test selecting a second level sub list item, pressing shift + right once to select start of its empty sub list item and using indent and outdent.
     *
     * @return void
     */
    public function testSubListItemWithStartOfEmptySubList()
    {
        foreach (array('ol', 'ul') as $listType) {
            foreach ($this->getTestMethods(TRUE, TRUE, TRUE) as $method) {

                if ($listType === 'ul') {
                    $this->useTest(7);
                    $ulStatus = 'active';
                    $olStatus = TRUE;
                } else {
                    $this->useTest(8);
                    $ulStatus = TRUE;
                    $olStatus = 'active';
                }

                $this->moveToKeyword(2, 'left');

                // Use foreach so the selection works in ie
                for ($i = 0; $i < 9; $i++) {
                    $this->sikuli->keyDown('Key.SHIFT + Key.RIGHT');
                }

                // Outdent once so the parent gets added to the top level list
                $this->doAction($method, 'listOutdent');
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1% <'.$listType.'><li>Audit of Homepage and 6 Section Landing pages</li></'.$listType.'></li><li>%2% test<'.$listType.'><li>&nbsp;</li><li>Accessibility audit report</li></'.$listType.'></li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);

                // Outdent again so parent is removed from the list
                $this->doAction($method, 'listOutdent');
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1% <'.$listType.'><li>Audit of Homepage and 6 Section Landing pages</li></'.$listType.'></li></'.$listType.'><p>%2% test</p><'.$listType.'><li>&nbsp;</li><li>Accessibility audit report</li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, FALSE);

                // Indent once paragraph is added back to the top list
                $this->doTopToolbarAction($method, 'listIndent');
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1%<'.$listType.'><li>Audit of Homepage and 6 Section Landing pages</li></'.$listType.'></li><li>%2% test<'.$listType.'><li></li><li>Accessibility audit report</li></'.$listType.'></li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);

                // Indent again so parent is added back to second level list
                $this->doAction($method, 'listIndent');
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1% <'.$listType.'><li>Audit of Homepage and 6 Section Landing pages</li><li>%2% test<'.$listType.'><li>&nbsp;</li><li>Accessibility audit report</li></'.$listType.'></li></'.$listType.'></li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);

                // Indent again so parent is moved to the third level
                $this->doAction($method, 'listIndent');
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1% <'.$listType.'><li>Audit of Homepage and 6 Section Landing pages<'.$listType.'><li>%2% test <'.$listType.'><li>&nbsp;</li><li>Accessibility audit report</li></'.$listType.'></li></'.$listType.'></li></'.$listType.'></li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, FALSE, TRUE);
            }
        }

    }//end testSubListItemWithStartOfEmptySubList()


    /**
     * Test selecting a second level sub list item, pressing shift + right twice to select its empty sub list item and the next list item and using indent
     * and outdent.
     *
     * @return void
     */
    public function testSubListItemWithEmptySubListAndLiOfNextListItem()
    {

        foreach (array('ol', 'ul') as $listType) {
            foreach ($this->getTestMethods(TRUE, TRUE, TRUE) as $method) {

                if ($listType === 'ul') {
                    $this->useTest(7);
                    $ulStatus = 'active';
                    $olStatus = TRUE;
                } else {
                    $this->useTest(8);
                    $ulStatus = TRUE;
                    $olStatus = 'active';
                }

                $this->sikuli->tripleClick($this->findKeyword(2));

                for ($i = 0; $i < 2; $i++) {
                    $this->sikuli->keyDown('Key.SHIFT + Key.RIGHT');
                }

                // Outdent once so the parent and following list item get added to the top level list.
                $this->doAction($method, 'listOutdent');
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1% <'.$listType.'><li>Audit of Homepage and 6 Section Landing pages</li></'.$listType.'></li><li>%2% test<'.$listType.'><li>&nbsp;</li><li>Accessibility audit report</li></'.$listType.'></li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);

                // Outdent again so parent and following list items are removed from the list.
                $this->doAction($method, 'listOutdent');
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1% <'.$listType.'><li>Audit of Homepage and 6 Section Landing pages</li></'.$listType.'></li></'.$listType.'><p>%2% test</p><'.$listType.'><li>&nbsp;</li><li>Accessibility audit report</li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, FALSE);

                // Indent once so items are added back to list
                $this->doTopToolbarAction($method, 'listIndent');
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1% <'.$listType.'><li>Audit of Homepage and 6 Section Landing pages</li></'.$listType.'></li><li>%2% test<'.$listType.'><li>&nbsp;</li><li>Accessibility audit report</li></'.$listType.'></li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);

                // Indent again so items are added back to sub list
                $this->doAction($method, 'listIndent');
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1% <'.$listType.'><li>Audit of Homepage and 6 Section Landing pages</li><li>%2% test<'.$listType.'><li>&nbsp;</li><li>Accessibility audit report</li></'.$listType.'></li></'.$listType.'></li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);

                // Indent again so items are added to the third level list
                $this->doAction($method, 'listIndent');
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1% <'.$listType.'><li>Audit of Homepage and 6 Section Landing pages<'.$listType.'><li>%2% test<'.$listType.'><li>&nbsp;</li><li>Accessibility audit report</li></'.$listType.'></li></'.$listType.'></li></'.$listType.'></li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, FALSE, TRUE);
            }
        }

    }//end testSubListItemWithEmptySubListAndLiOfNextListItem()


    /**
     * Test selecting an unordered sub list item and its sub list and using indent and outdent.
     *
     * @return void
     */
    public function testSubListItemWithItsSubList()
    {
        foreach (array('ol', 'ul') as $listType) {
            foreach ($this->getTestMethods(TRUE, TRUE, TRUE) as $method) {

                if ($listType === 'ul') {
                    $this->useTest(9);
                    $ulStatus = 'active';
                    $olStatus = TRUE;
                } else {
                    $this->useTest(10);
                    $ulStatus = TRUE;
                    $olStatus = 'active';
                }

                $this->selectKeyword(2);
                $this->selectInlineToolbarLineageItem(3);

                // Outdent once so the parent gets added to the top level list
                $this->doAction($method, 'listOutdent');
                sleep(1);
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1%<'.$listType.'><li>Audit of Homepage and 6 Section Landing pages</li></'.$listType.'></li><li>%2% test<'.$listType.'><li>test data %3%</li><li>Accessibility audit report</li></'.$listType.'></li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);

                // Outdent again so parent is removed from the list
                $this->doAction($method, 'listOutdent');
                sleep(1);
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1% <'.$listType.'><li>Audit of Homepage and 6 Section Landing pages</li></'.$listType.'></li></'.$listType.'><p>%2% test </p><'.$listType.'><li>test data %3%</li><li>Accessibility audit report</li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, FALSE);

                // Indent once paragraph is added back to the top list
                $this->doTopToolbarAction($method, 'listIndent');
                sleep(1);
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1% <'.$listType.'><li>Audit of Homepage and 6 Section Landing pages</li></'.$listType.'></li><li>%2% test <'.$listType.'><li>test data %3%</li><li>Accessibility audit report</li></'.$listType.'></li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);

                // Indent again so parent is added back to second level list
                $this->doAction($method, 'listIndent');
                sleep(1);
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1% <'.$listType.'><li>Audit of Homepage and 6 Section Landing pages</li><li>%2% test <'.$listType.'><li>test data %3%</li><li>Accessibility audit report</li></'.$listType.'></li></'.$listType.'></li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, TRUE, TRUE);

                // Indent again so parent is moved to the third level
                $this->doAction($method, 'listIndent');
                sleep(1);
                $this->assertHTMLMatch('<h2>Meh</h2><'.$listType.'><li>%1%<'.$listType.'><li>Audit of Homepage and 6 Section Landing pages<'.$listType.'><li>%2% test<'.$listType.'><li>test data %3%</li><li>Accessibility audit report</li></'.$listType.'></li></'.$listType.'></li></'.$listType.'></li></'.$listType.'>');
                $this->assertIconStatusesCorrect($ulStatus, $olStatus, FALSE, TRUE);
            }
        }

    }//end testSubListItemWithItsSubList()


}//end class

?>