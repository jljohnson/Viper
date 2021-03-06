<?php

require_once 'AbstractViperUnitTest.php';

class Viper_Tests_ViperCopyPastePlugin_CopyAndCutListUnitTest extends AbstractViperUnitTest
{


    /**
     * Test copy and paste for part of a list.
     *
     * @return void
     */
    public function testCopyAndPastePartOfList()
    {
        // Test unordered list
        $this->useTest(1);

        $this->selectKeyword(2, 3);
        sleep(1);
        $this->sikuli->keyDown('Key.CMD + c');
        sleep(1);
        $this->moveToKeyword(1, 'right');
        sleep(1);
        $this->sikuli->keyDown('Key.ENTER');
        sleep(1);
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);

        $this->assertHTMLMatch('<p>Unordered list %1%</p><ul><li>%2% 1</li><li>item 2</li><li>item 3 %3%</li></ul><ul><li>item %2% 1</li><li>item 2</li><li>item 3 %3%</li></ul>');

        // Test unordered list into the same list
        $this->useTest(1);

        $this->selectKeyword(2, 3);
        $this->sikuli->keyDown('Key.CMD + c');

        $this->moveToKeyword(3, 'right');
        $this->sikuli->keyDown('Key.CMD + v');

        $this->assertHTMLMatch('<p>Unordered list %1%</p><ul><li>item %2% 1</li><li>item 2</li><li>item 3 %3%</li><li>%2% 1</li><li>item 2</li><li>item 3 %3%</li></ul>');

        // Test ordered list
        $this->useTest(2);
        $this->selectKeyword(2, 3);
        $this->sikuli->keyDown('Key.CMD + c');

        $this->moveToKeyword(1, 'right');
        $this->sikuli->keyDown('Key.ENTER');
        $this->sikuli->keyDown('Key.CMD + v');

        $this->assertHTMLMatch('<p>Ordered list %1%</p><ol><li>%2% 1</li><li>item 2</li><li>item 3 %3%</li></ol><ol><li>item %2% 1</li><li>item 2</li><li>item 3 %3%</li></ol>');

        // Test ordered list into the same list
        $this->useTest(2);

        $this->selectKeyword(2, 3);
        $this->sikuli->keyDown('Key.CMD + c');

        $this->moveToKeyword(3, 'right');
        $this->sikuli->keyDown('Key.CMD + v');

        $this->assertHTMLMatch('<p>Ordered list %1%</p><ol><li>item %2% 1</li><li>item 2</li><li>item 3 %3%</li><li>%2% 1</li><li>item 2</li><li>item 3 %3%</li></ol>');

    }//end testCopyAndPastePartOfList()


    /**
     * Test cut and paste for part of a list.
     *
     * @return void
     */
    public function testCutAndPastePartOfList()
    {
        //Test unordered list
        $this->useTest(6);

        $this->selectKeyword(1, 2);
        $this->sikuli->keyDown('Key.CMD + x');

        $this->moveToKeyword(3, 'right');
        $this->sikuli->keyDown('Key.ENTER');
        $this->sikuli->keyDown('Key.CMD + v');

        $this->assertHTMLMatch('<p>Unordered list</p><ul><li>2</li><li>item 3</li></ul><p>This is a paragraph %3%</p><ul><li>%1% item 1</li><li>item %2%</li></ul>');

        //Test ordered list
        $this->useTest(7);
        $this->selectKeyword(1, 2);
        $this->sikuli->keyDown('Key.CMD + x');

        $this->moveToKeyword(3, 'right');
        $this->sikuli->keyDown('Key.ENTER');
        $this->sikuli->keyDown('Key.CMD + v');

        $this->assertHTMLMatch('<p>Ordered list</p><ol><li>2</li><li>item 3</li></ol><p>This is a paragraph %3%</p><ol><li>%1% item 1</li><li>item %2%</li></ol>');

    }//end testCutAndPastePartOfList()


    /**
     * Test copy and paste a list.
     *
     * @return void
     */
    public function testCopyAndPasteWholeList()
    {
        // Test unordered list
        $this->useTest(1);

        $this->selectKeyword(2);
        $this->selectInlineToolbarLineageItem(0);
        $this->sikuli->keyDown('Key.CMD + c');

        $this->moveToKeyword(1, 'right');
        $this->sikuli->keyDown('Key.ENTER');
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);

        $this->assertHTMLMatch('<p>Unordered list %1%</p><ul><li>item %2% 1</li><li>item 2</li><li>item 3 %3%</li></ul><ul><li>item %2% 1</li><li>item 2</li><li>item 3 %3%</li></ul>');

        // Test unordered list into the same list
        $this->useTest(1);

        $this->selectKeyword(2);
        sleep(1);
        $this->selectInlineToolbarLineageItem(0);
        sleep(1);
        $this->sikuli->keyDown('Key.CMD + c');
        sleep(1);
        $this->moveToKeyword(3, 'right');
        sleep(1);
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);

        $this->assertHTMLMatch('<p>Unordered list %1%</p><ul><li>item %2% 1</li><li>item 2</li><li>item 3 %3%</li><li>item %2% 1</li><li>item 2</li><li>item 3 %3%</li></ul>');

        // Test ordered list
        $this->useTest(2);

        $this->selectKeyword(2);
        $this->selectInlineToolbarLineageItem(0);
        $this->sikuli->keyDown('Key.CMD + c');

        $this->moveToKeyword(1, 'right');
        $this->sikuli->keyDown('Key.ENTER');
        $this->sikuli->keyDown('Key.CMD + v');

        $this->assertHTMLMatch('<p>Ordered list %1%</p><ol><li>item %2% 1</li><li>item 2</li><li>item 3 %3%</li></ol><ol><li>item %2% 1</li><li>item 2</li><li>item 3 %3%</li></ol>');

        // Test unordered list into the same list
        $this->useTest(2);

        $this->selectKeyword(2);
        sleep(1);
        $this->selectInlineToolbarLineageItem(0);
        sleep(1);
        $this->sikuli->keyDown('Key.CMD + c');
        sleep(1);

        $this->moveToKeyword(3, 'right');
        sleep(1);
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);

        $this->assertHTMLMatch('<p>Ordered list %1%</p><ol><li>item %2% 1</li><li>item 2</li><li>item 3 %3%</li><li>item %2% 1</li><li>item 2</li><li>item 3 %3%</li></ol>');


    }//end testCopyAndPasteWholeList()


    /**
     * Test cut and paste for whole list.
     *
     * @return void
     */
    public function testCutAndPasteWholeOfList()
    {
        // Test unordered list
        $this->useTest(6);

        $this->selectKeyword(1);
        $this->selectInlineToolbarLineageItem(0);
        $this->sikuli->keyDown('Key.CMD + x');

        $this->moveToKeyword(3, 'right');
        $this->sikuli->keyDown('Key.ENTER');
        $this->sikuli->keyDown('Key.CMD + v');

        $this->assertHTMLMatch('<p>Unordered list</p><p>This is a paragraph %3%</p><ul><li>%1% item 1</li><li>item %2% 2</li><li>item 3</li></ul>');

        // Test ordered list
        $this->useTest(7);
        $this->selectKeyword(1);
        $this->selectInlineToolbarLineageItem(0);
        $this->sikuli->keyDown('Key.CMD + x');

        $this->moveToKeyword(3, 'right');
        $this->sikuli->keyDown('Key.ENTER');
        $this->sikuli->keyDown('Key.CMD + v');

        $this->assertHTMLMatch('<p>Ordered list</p><p>This is a paragraph %3%</p><ol><li>%1% item 1</li><li>item %2% 2</li><li>item 3</li></ol>');

    }//end testCutAndPasteWholeOfList()


    /**
     * Test copy and paste part of a list inside another list
     *
     * @return void
     */
    public function testCopyAndPastePartOfListIntoAnotherList()
    {
        // Test copying unordered list items to another unordered list
        $this->useTest(3);
        $this->selectKeyword(1, 2);
        $this->sikuli->keyDown('Key.CMD + c');
        sleep(1);
        $this->moveToKeyword(3, 'right');
        sleep(1);
        $this->sikuli->keyDown('Key.ENTER');
        sleep(1);
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);
        $this->assertHTMLMatch('<p>First list:</p><ul><li>item 1</li><li>%1% item 2</li><li>item 3 %2%</li><li>item 4</li></ul><p>Second list:</p><ul><li>item 1</li><li>item 2 %3%</li><li>%1% item 2</li><li>item 3 %2%</li><li>item 3</li><li>item 4</li></ul>');

        // Test copying ordered list items to another ordered list
        $this->useTest(4);
        $this->selectKeyword(1, 2);
        $this->sikuli->keyDown('Key.CMD + c');
        sleep(1);
        $this->moveToKeyword(3, 'right');
        sleep(1);
        $this->sikuli->keyDown('Key.ENTER');
        sleep(1);
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);
        $this->assertHTMLMatch('<p>First list:</p><ol><li>item 1</li><li>%1% item 2</li><li>item 3 %2%</li><li>item 4</li></ol><p>Second list:</p><ol><li>item 1</li><li>item 2 %3%</li><li>%1% item 2</li><li>item 3 %2%</li><li>item 3</li><li>item 4</li></ol>');

         // Test copying list items into different list types
        $this->useTest(5);
        $this->selectKeyword(1, 2);
        $this->sikuli->keyDown('Key.CMD + c');
        sleep(1);
        $this->moveToKeyword(4, 'right');
        sleep(1);
        $this->sikuli->keyDown('Key.ENTER');
        sleep(1);
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);
        $this->assertHTMLMatch('<p>First list:</p><ul><li>item 1</li><li>%1% item 2</li><li>item 3 %2%</li><li>item 4</li></ul><p>Second list:</p><ol><li>item 1</li><li>XCX item 2</li><li>item 3 XDX</li><li>XAX item 2</li><li>item 3 XBX</li><li>item 4</li></ol>');

        $this->useTest(5);
        $this->selectKeyword(3, 4);
        $this->sikuli->keyDown('Key.CMD + c');
        sleep(1);
        $this->moveToKeyword(2, 'right');
        sleep(1);
        $this->sikuli->keyDown('Key.ENTER');
        sleep(1);
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);
        $this->assertHTMLMatch('<p>First list:</p><ul><li>item 1</li><li>%1% item 2</li><li>item 3 %2%</li><li>%3% item 2</li><li>item 3 %4%</li><li>item 4</li></ul><p>Second list:</p><ol><li>item 1</li><li>%3% item 2</li><li>item 3 %4%</li><li>item 4</li></ol>');

    }//end testCopyAndPastePartOfListIntoAnotherList()


    /**
     * Test cut and paste part of a list inside another list
     *
     * @return void
     */
    public function testCutAndPastePartOfListIntoAnotherList()
    {
        // Test cutting unordered list items to another unordered list
        $this->useTest(3);
        $this->selectKeyword(1, 2);
        $this->sikuli->keyDown('Key.CMD + x');
        sleep(1);
        $this->moveToKeyword(3, 'right');
        sleep(1);
        $this->sikuli->keyDown('Key.ENTER');
        sleep(1);
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);
        $this->assertHTMLMatch('<p>First list:</p><ul><li>item 1</li><li>item 4</li></ul><p>Second list:</p><ul><li>item 1</li><li>item 2 %3%</li><li>%1% item 2</li><li>item 3 %2%</li><li>item 3</li><li>item 4</li></ul>');

        // Test cutting ordered list items to another ordered list
        $this->useTest(4);
        $this->selectKeyword(1, 2);
        $this->sikuli->keyDown('Key.CMD + x');
        sleep(1);
        $this->moveToKeyword(3, 'right');
        sleep(1);
        $this->sikuli->keyDown('Key.ENTER');
        sleep(1);
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);
        $this->assertHTMLMatch('<p>First list:</p><ol><li>item 1</li><li>item 4</li></ol><p>Second list:</p><ol><li>item 1</li><li>item 2 %3%</li><li>%1% item 2</li><li>item 3 %2%</li><li>item 3</li><li>item 4</li></ol>');

         // Test cutting list items into different list types
        $this->useTest(5);
        $this->selectKeyword(1, 2);
        $this->sikuli->keyDown('Key.CMD + x');
        sleep(1);
        $this->moveToKeyword(4, 'right');
        sleep(1);
        $this->sikuli->keyDown('Key.ENTER');
        sleep(1);
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);
        $this->assertHTMLMatch('<p>First list:</p><ul><li>item 1</li><li>item 4</li></ul><p>Second list:</p><ol><li>item 1</li><li>%3% item 2</li><li>item 3 %4%</li><li>%1% item 2</li><li>item 3 %2%</li><li>item 4</li></ol>');

        $this->useTest(5);
        $this->selectKeyword(3, 4);
        $this->sikuli->keyDown('Key.CMD + x');
        sleep(1);
        $this->moveToKeyword(2, 'right');
        sleep(1);
        $this->sikuli->keyDown('Key.ENTER');
        sleep(1);
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);
        $this->assertHTMLMatch('<p>First list:</p><ul><li>item 1</li><li>%1% item 2</li><li>item 3 %2%</li><li>%3% item 2</li><li>item 3 %4%</li><li>item 4</li></ul><p>Second list:</p><ol><li>item 1</li><li>item 4</li></ol>');

    }//end testCopyAndPastePartOfListIntoAnotherList()


}//end class

?>
