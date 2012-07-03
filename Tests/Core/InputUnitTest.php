<?php

require_once 'AbstractViperUnitTest.php';

class Viper_Tests_Core_InputUnitTest extends AbstractViperUnitTest
{


    /**
     * Test that typing standard characters work.
     *
     * @return void
     */
    public function testTextType()
    {
        $text = $this->selectKeyword(1);
        $this->keyDown('Key.DELETE');

        $chars  = '`1234567890-=qwertyuiop[]asdfghjkl;zxcvbnm,.';
        $chars .= 'QWERTYUIOPASDFGHJKLZXCVBNM';
        $chars .= '~!@#$%^&*()_+{}|:"<>?   . ';

        $this->type($chars);

        $expected  = '`1234567890-=qwertyuiop[]asdfghjkl;zxcvbnm,.';
        $expected .= 'QWERTYUIOPASDFGHJKLZXCVBNM';
        $expected .= '~!@#$%^&amp;*()_+{}|:"&lt;&gt;? &nbsp; .&nbsp;';

        $this->assertHTMLMatch('<p>'.$expected.'</p><p>EIB MOZ</p>');

    }//end testTextType()


    /**
     * Test that typing replaces the selected text.
     *
     * @return void
     */
    public function testTextTypeReplaceSelection()
    {
        $this->selectKeyword(1);

        $this->type('Testing input');

        $this->assertHTMLMatch('<p>Testing input</p><p>EIB MOZ</p>');

    }//end testTextTypeReplaceSelection()


    /**
     * Test enter a new paragraph.
     *
     * @return void
     */
    public function testCreatingANewParagraph()
    {
        $this->selectKeyword(1);
        $this->keyDown('Key.RIGHT');
        $this->keyDown('Key.ENTER');
        $this->type('Testing input');

        $this->assertHTMLMatch('<p>%1%</p><p>Testing input</p>');

    }//end testCreatingANewParagraph()


    /**
     * Test that using UP, DOWN, RIGHT, and LEFT arrows move caret correctly.
     *
     * @return void
     */
    public function testKeyboradNavigation()
    {
        $text = $this->selectKeyword(1);
        $this->type('Testing input');

        $this->keyDown('Key.LEFT');
        $this->keyDown('Key.LEFT');
        $this->keyDown('Key.LEFT');
        $this->keyDown('Key.LEFT');
        $this->keyDown('Key.LEFT');
        $this->type('L');
        $this->keyDown('Key.RIGHT');
        $this->keyDown('Key.RIGHT');
        $this->type('R');
        $this->keyDown('Key.LEFT');
        $this->keyDown('Key.LEFT');
        $this->keyDown('Key.LEFT');
        $this->keyDown('Key.LEFT');
        $this->keyDown('Key.DOWN');
        $this->type('D');
        $this->keyDown('Key.LEFT');
        $this->keyDown('Key.LEFT');
        $this->keyDown('Key.LEFT');
        $this->keyDown('Key.LEFT');
        $this->keyDown('Key.LEFT');
        $this->keyDown('Key.LEFT');
        $this->keyDown('Key.UP');
        $this->type('U');

        $this->assertHTMLMatch('<p>TeUsting LinRput</p><p>EIB MOZD</p>');

    }//end testKeyboradNavigation()


    /**
     * Test that removing characters using BACKSPACE works.
     *
     * @return void
     */
    public function testBackspace()
    {
        $text = $this->selectKeyword(1);
        $this->keyDown('Key.RIGHT');
        $this->keyDown('Key.CMD + b');
        $this->type('test');
        $this->keyDown('Key.CMD + b');
        $this->type(' input...');
        $this->keyDown('Key.ENTER');
        $this->type('Testing...');

        for ($i = 0; $i < 30; $i++) {
            $this->keyDown('Key.BACKSPACE');
        }

        $this->assertHTMLMatch('<p>EIB MOZ</p>');

    }//end testBackspace()


    /**
     * Test that removing characters using DELETE works.
     *
     * @return void
     */
    public function testDelete()
    {
        $text = $this->selectKeyword(1);
        $this->keyDown('Key.RIGHT');
        $this->keyDown('Key.CMD + b');
        $this->type('test');
        $this->keyDown('Key.CMD + b');
        $this->type(' input...');
        $this->keyDown('Key.ENTER');
        $this->type('Testing...');

        for ($i = 0; $i < 26; $i++) {
            $this->keyDown('Key.LEFT');
        }

        for ($i = 0; $i < 26; $i++) {
            $this->keyDown('Key.DELETE');
        }

        $this->assertHTMLMatch('<p>EIB MOZ</p>');

    }//end testDelete()


    /**
     * Test that holding down Shift + Right does select text.
     *
     * @return void
     */
    public function testRightKeyboardSelection()
    {
        $text = $this->selectKeyword(1);
        $this->keyDown('Key.SHIFT + Key.RIGHT');
        $this->keyDown('Key.SHIFT + Key.RIGHT');
        $this->keyDown('Key.DELETE');
        $this->type('p');
        $this->assertHTMLMatch('<p>pIB MOZ</p>');

    }//end testRightKeyboardSelection()


    /**
     * Test that holding down Shift + Left does select text.
     *
     * @return void
     */
    public function testLeftKeyboardSelection()
    {
        $text = $this->selectKeyword(1);
        $this->keyDown('Key.SHIFT + Key.RIGHT');
        $this->keyDown('Key.SHIFT + Key.LEFT');
        $this->keyDown('Key.SHIFT + Key.LEFT');
        $this->keyDown('Key.SHIFT + Key.LEFT');
        $this->keyDown('Key.DELETE');
        $this->type('p');
        $this->assertHTMLMatch('<p>pAX</p><p>EIB MOZ</p>');

    }//end testRightKeyboardSelection()


    /**
     * Test that selecting the whole content is possible with short cut.
     *
     * @return void
     */
    public function testSelectAllAndRemove()
    {
        $this->selectKeyword(1);
        $this->keyDown('Key.CMD + a');
        $this->keyDown('Key.DELETE');

        $this->type('abc');
        $this->assertHTMLMatch('<p>abc</p>');

    }//end testSelectAllAndRemove()


    /**
     * Test that selecting the whole content is possible with short cut.
     *
     * @return void
     */
    public function testSelectAllAndReplace()
    {
        $this->selectKeyword(1);
        $this->keyDown('Key.CMD + a');

        sleep(1);
        $this->type('abc');
        $this->assertHTMLMatch('<p>abc</p>');

    }//end testSelectAllAndReplace()


    /**
     * Test that you can delete all content and then undo the changes.
     *
     * @return void
     */
    public function testDeleteAllClickUndoAndClickRedo()
    {

        $this->selectKeyword(1);
        $this->keyDown('Key.CMD + a');
        $this->keyDown('Key.DELETE');
        sleep(1);
        $this->assertHTMLMatch('<p></p>');
        $this->assertTrue($this->topToolbarButtonExists('historyUndo'), 'Undo icon should be active');
        $this->assertTrue($this->topToolbarButtonExists('historyRedo', 'disabled'), 'Redo icon should be disabled');

        $this->clickTopToolbarButton('historyUndo');
        $this->assertHTMLMatch('<p>%1%</p><p>EIB MOZ</p>');
        $this->assertTrue($this->topToolbarButtonExists('historyUndo', 'disabled'), 'Undo icon should be disabled');
        $this->assertTrue($this->topToolbarButtonExists('historyRedo'), 'Redo icon should be active');

        $this->clickTopToolbarButton('historyRedo');
        $this->assertHTMLMatch('<p></p>');
        $this->assertTrue($this->topToolbarButtonExists('historyUndo'), 'Undo icon should be active');
        $this->assertTrue($this->topToolbarButtonExists('historyRedo', 'disabled'), 'Redo icon should be disabled');

        $this->clickTopToolbarButton('historyUndo');
        $this->assertHTMLMatch('<p>%1%</p><p>EIB MOZ</p>');
        $this->assertTrue($this->topToolbarButtonExists('historyUndo', 'disabled'), 'Undo icon should be disabled');
        $this->assertTrue($this->topToolbarButtonExists('historyRedo'), 'Redo icon should be active');

    }//end testDeleteAllClickUndoAndClickRedo()


    /**
     * Test that you can delete all content and then undo the changes using the keyboard shortcuts.
     *
     * @return void
     */
    public function testDeleteAllClickUndoAndClickRedoUsingShortcuts()
    {

        $this->selectKeyword(1);
        $this->keyDown('Key.CMD + a');
        $this->keyDown('Key.DELETE');
        sleep(1);
        $this->assertHTMLMatch('<p></p>');
        $this->assertTrue($this->topToolbarButtonExists('historyUndo'), 'Undo icon should be active');
        $this->assertTrue($this->topToolbarButtonExists('historyRedo', 'disabled'), 'Redo icon should be disabled');

        $this->keyDown('Key.CMD + z');
        $this->assertHTMLMatch('<p>%1%</p><p>EIB MOZ</p>');
        $this->assertTrue($this->topToolbarButtonExists('historyUndo', 'disabled'), 'Undo icon should be disabled');
        $this->assertTrue($this->topToolbarButtonExists('historyRedo'), 'Redo icon should be active');

        $this->keyDown('Key.CMD + Key.SHIFT + z');
        $this->assertHTMLMatch('<p></p>');
        $this->assertTrue($this->topToolbarButtonExists('historyUndo'), 'Undo icon should be active');
        $this->assertTrue($this->topToolbarButtonExists('historyRedo', 'disabled'), 'Redo icon should be disabled');

        $this->keyDown('Key.CMD + z');
        $this->assertHTMLMatch('<p>%1%</p><p>EIB MOZ</p>');
        $this->assertTrue($this->topToolbarButtonExists('historyUndo', 'disabled'), 'Undo icon should be disabled');
        $this->assertTrue($this->topToolbarButtonExists('historyRedo'), 'Redo icon should be active');


    }//end testDeleteAllClickUndoAndClickRedo()

}//end class

?>
