<?php

require_once 'AbstractViperUnitTest.php';

class Viper_Tests_BlockTag_BlankBlockTagWithSubscriptUnitTest extends AbstractViperUnitTest
{
    /**
     * Test adding subscript formatting to content
     *
     * @return void
     */
    public function testAddingSubscriptFormattingToContent()
    {
        $this->useTest(1);
        $this->sikuli->execJS('viper.setSetting("defaultBlockTag", "")');

        // Test applying subscript formatting to one word using the top toolbar
        $this->useTest(2);
        $this->selectKeyword(1);
        $this->clickTopToolbarButton('subscript');
        $this->assertHTMLMatch('This is <sub>%1%</sub> %2% some content');

        // Test applying subscript formatting to multiple words using the top toolbar
        $this->useTest(2);
        $this->selectKeyword(1, 2);
        $this->clickTopToolbarButton('subscript');
        $this->assertHTMLMatch('This is <sub>%1% %2%</sub> some content');

    }//end testAddingSubscriptFormattingToContent()


    /**
     * Test removing subscript formatting from content
     *
     * @return void
     */
    public function testRemovingSubscriptFormatting()
    {
        $this->useTest(1);
        $this->sikuli->execJS('viper.setSetting("defaultBlockTag", "")');

        // Test removing subscript formatting from one word using the top toolbar
        $this->useTest(3);
        $this->selectKeyword(1);
        $this->clickTopToolbarButton('subscript', 'active');
        $this->assertHTMLMatch('This is %1% some content');

        // Test removing subscript formatting from multiple words using the top toolbar
        $this->useTest(4);
        $this->selectKeyword(1);
        $this->selectInlineToolbarLineageItem(0);
        $this->clickTopToolbarButton('subscript', 'active');
        $this->assertHTMLMatch('Some subscript %1% %2% content to test');

        // Test removing subscript formatting from all content using top toolbar
        $this->useTest(5);
        $this->selectKeyword(1);
        $this->selectInlineToolbarLineageItem(0);
        $this->clickTopToolbarButton('subscript', 'active');
        $this->assertHTMLMatch('Subscript %1% content');

    }//end testRemovingSubscriptFormatting()


    /**
     * Test editing subscript content
     *
     * @return void
     */
    public function testEditingSubscriptContent()
    {
        $this->useTest(1);
        $this->sikuli->execJS('viper.setSetting("defaultBlockTag", "")');

        // Test adding content to the start of the subscript formatting
        $this->useTest(4);
        $this->clickKeyword(1);
        $this->sikuli->keyDown('Key.LEFT');
        $this->type('test ');
        $this->assertHTMLMatch('Some subscript test <sub>%1% %2%</sub> content to test');

        // Test adding content in the middle of subscript formatting
        $this->moveToKeyword(1, 'right');
        $this->type(' test');
        $this->assertHTMLMatch('Some subscript test <sub>%1% test %2%</sub> content to test');

        // Test adding content to the end of subscript formatting
        $this->clickKeyword(2);
        $this->sikuli->keyDown('Key.RIGHT');
        $this->sikuli->keyDown('Key.RIGHT');
        $this->type(' test');
        $this->assertHTMLMatch('Some subscript test <sub>%1% test %2% test</sub> content to test');

        // Test highlighting some content in the sub tags and replacing it
        $this->selectKeyword(2);
        $this->type('abc');
        $this->assertHTMLMatch('Some subscript test <sub>%1% test abc test</sub> content to test');

        $this->selectKeyword(1);
        $this->sikuli->keyDown('Key.BACKSPACE');
        $this->type('abc');
        $this->assertHTMLMatch('Some subscript test <sub>abc test abc test</sub> content to test');

    }//end testEditingSubscriptContent()


    /**
     * Test deleting subscript content
     *
     * @return void
     */
    public function testDeletingSubscriptContent()
    {
        $this->useTest(1);
        $this->sikuli->execJS('viper.setSetting("defaultBlockTag", "")');

        // Test replacing subscript content with new content with highlighting
        $this->useTest(4);
        $this->selectKeyword(1, 2);
        $this->type('test');
        $this->assertHTMLMatch('Some subscript <sub>test</sub> content to test');

        $this->useTest(4);
        $this->selectKeyword(1, 2);
        $this->sikuli->keyDown('Key.DELETE');
        $this->type('test');
        $this->assertHTMLMatch('Some subscript test content to test');

        $this->useTest(4);
        $this->selectKeyword(1, 2);
        $this->sikuli->keyDown('Key.BACKSPACE');
        $this->type('test');
        $this->assertHTMLMatch('Some subscript test content to test');

        // Test replacing subscript content with new content when selecting one keyword and using the lineage
        $this->useTest(4);
        $this->selectKeyword(1);
        $this->selectInlineToolbarLineageItem(0);
        $this->type('test');
        $this->assertHTMLMatch('Some subscript <sub>test</sub> content to test');

        $this->useTest(4);
        $this->selectKeyword(1);
        $this->selectInlineToolbarLineageItem(0);
        $this->sikuli->keyDown('Key.DELETE');
        $this->type('test');
        $this->assertHTMLMatch('Some subscript test content to test');

        $this->useTest(4);
        $this->selectKeyword(1);
        $this->selectInlineToolbarLineageItem(0);
        $this->sikuli->keyDown('Key.BACKSPACE');
        $this->type('test');
        $this->assertHTMLMatch('Some subscript test content to test');

        // Test replacing all content
        $this->useTest(5);
        $this->selectKeyword(1);
        $this->selectInlineToolbarLineageItem(0);
        $this->type('test');
        $this->assertHTMLMatch('test');

    }//end testDeletingSubscriptContent()


    /**
     * Test splitting a subscript section in content
     *
     * @return void
     */
    public function testSplittingSubscriptContent()
    {
        $this->useTest(1);
        $this->sikuli->execJS('viper.setSetting("defaultBlockTag", "")');

        // Test pressing enter in the middle of subscript content
        $this->useTest(4);
        $this->moveToKeyword(1, 'right');
        $this->sikuli->keyDown('Key.ENTER');
        $this->type('test');
        $this->assertHTMLMatch('Some subscript <sub>%1%<br />test %2%</sub> content to test');

        // Test pressing enter at the start of subscript content
        $this->useTest(4);
        $this->moveToKeyword(1, 'left');
        $this->sikuli->keyDown('Key.ENTER');
        $this->type('test ');
        $this->assertHTMLMatch('Some subscript <br />test <sub>%1% %2%</sub> content to test');
        $this->sikuli->keyDown('Key.LEFT');
        $this->sikuli->keyDown('Key.LEFT');
        $this->sikuli->keyDown('Key.LEFT');
        $this->sikuli->keyDown('Key.LEFT');
        $this->sikuli->keyDown('Key.LEFT');
        $this->sikuli->keyDown('Key.LEFT');
        $this->type('test');
        $this->assertHTMLMatch('Some subscript test<br />test <sub>%1% %2%</sub> content to test');

        // Test pressing enter at the end of subscript content
        $this->useTest(4);
        $this->moveToKeyword(2, 'right');
        $this->sikuli->keyDown('Key.ENTER');
        $this->type('test ');
        $this->assertHTMLMatch('Some subscript <sub>%1% %2%</sub><br />test&nbsp;&nbsp;content to test');

    }//end testSplittingSubscriptContent()


    /**
     * Test undo and redo with subscript content
     *
     * @return void
     */
    public function testUndoAndRedoWithSubscriptContent()
    {
        $this->useTest(1);
        $this->sikuli->execJS('viper.setSetting("defaultBlockTag", "")');

        // Apply subscript content
        $this->useTest(2);
        $this->selectKeyword(1);
        $this->clickTopToolbarButton('subscript');
        $this->assertHTMLMatch('This is <sub>%1%</sub> %2% some content');

        // Test undo and redo with top toolbar icons
        $this->clickTopToolbarButton('historyUndo');
        $this->assertHTMLMatch('This is %1% %2% some content');
        $this->clickTopToolbarButton('historyRedo');
        $this->assertHTMLMatch('This is <sub>%1%</sub> %2% some content');

        // Test undo and redo with keyboard shortcuts
        $this->sikuli->keyDown('Key.CMD + z');
        $this->assertHTMLMatch('This is %1% %2% some content');
        $this->sikuli->keyDown('Key.CMD + Key.SHIFT + z');
        $this->assertHTMLMatch('This is <sub>%1%</sub> %2% some content');

    }//end testUndoAndRedoWithSubscriptContent()


}//end class
