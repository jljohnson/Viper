<?php

require_once 'AbstractViperUnitTest.php';

class Viper_Tests_ViperCopyPastePlugin_CutPasteUnitTest extends AbstractViperUnitTest
{


    /**
     * Test that cuting/pasting a simple text works.
     *
     * @return void
     */
    public function testSimpleTextCutPaste()
    {
        // Copy and paste without deleteing text
        $this->useTest(1);

        $this->selectKeyword(1);
        sleep(2);
        $this->sikuli->keyDown('Key.CMD + x');
        $this->type('A');
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(2);
        $this->sikuli->keyDown('Key.CMD + v');
        $this->type('B');
        sleep(2);
        $this->sikuli->keyDown('Key.CMD + v');
        $this->type('C');

        $this->assertHTMLMatch('<p>A%1%%1%B%1%C</p>');

        // Delete all content, add new content and then cut and paste
        $this->useTest(1);
        $this->moveToKeyword(1);
        $this->sikuli->keyDown('Key.CMD + a');
        $this->sikuli->keyDown('Key.BACKSPACE');
        $this->type('%1% This is one line of content %2%');
        $this->selectKeyword(1, 2);
        sleep(1);
        $this->sikuli->keyDown('Key.CMD + x');
        $this->type('new content ');
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);
        $this->assertHTMLMatch('<p>new content %1% This is one line of content %2%</p>');
        // Type some content to make sure the cursor is at the end
        $this->type(' Added content');
        $this->assertHTMLMatch('<p>new content %1% This is one line of content %2% Added content</p>');

        // Paste again
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);
        $this->assertHTMLMatch('<p>new content %1% This is one line of content %2% Added content%1% This is one line of content %2%</p>');
        // Type some content to make sure the cursor is at the end
        $this->type(' More added content');
        $this->assertHTMLMatch('<p>new content %1% This is one line of content %2% Added content%1% This is one line of content %2% More added content</p>');

        // Paste again
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);
        $this->assertHTMLMatch('<p>new content %1% This is one line of content %2% Added content%1% This is one line of content %2% More added content%1% This is one line of content %2%</p>');
        // Type some content to make sure the cursor is at the end
        $this->type(' Last added content');
        $this->assertHTMLMatch('<p>new content %1% This is one line of content %2% Added content%1% This is one line of content %2% More added content%1% This is one line of content %2% Last added content</p>');

    }//end testSimpleTextCutPaste()


    /**
     * Test partial cut and paste.
     *
     * @return void
     */
    public function testPartialCutPaste()
    {
        // Test cutting some content and pasting over the top of existing content
        $this->useTest(2);

        $this->selectKeyword(2);
        sleep(2);
        $this->sikuli->keyDown('Key.CMD + x');
        
        $this->selectKeyword(1);
        $this->sikuli->keyDown('Key.CMD + v');

        $this->assertHTMLMatch('<p>This is some content to %2% test partial copy and paste. It  needs to be a really long paragraph.</p>');

        // Test cutting some content and pasting it somewhere else in the existing content
        $this->useTest(2);

        $this->selectKeyword(2);
        sleep(2);
        $this->sikuli->keyDown('Key.CMD + x');
        
        $this->moveToKeyword(1, 'left');
        $this->sikuli->keyDown('Key.CMD + v');

        $this->assertHTMLMatch('<p>This is some content to %2%%1% test partial copy and paste. It  needs to be a really long paragraph.</p>');

    }//end testPartialCutPaste()

}//end class

?>
