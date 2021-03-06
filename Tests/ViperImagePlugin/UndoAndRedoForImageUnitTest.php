<?php

require_once 'AbstractViperImagePluginUnitTest.php';

class Viper_Tests_ViperImagePlugin_UndoAndRedoForImageUnitTest extends AbstractViperImagePluginUnitTest
{

     /**
     * Test inserting an image and then clicking undo and redo.
     *
     * @return void
     */
    public function testUndoInsertingImage()
    {
        $this->useTest(1);

        $this->selectKeyword(1);

        $this->clickTopToolbarButton('image');
        $this->type($this->getTestURL('/ViperImagePlugin/Images/editing.png'));
        $this->sikuli->keyDown('Key.TAB');
        $this->type('Alt tag');
        $this->sikuli->keyDown('Key.TAB');
        $this->type('Title tag');
        $this->sikuli->keyDown('Key.ENTER');
        $this->assertHTMLMatch('<p><img src="%url%/ViperImagePlugin/Images/editing.png" alt="Alt tag" title="Title tag"/> Content to test inserting images</p><p>Another paragraph in the content %2%</p>');

        $this->clickTopToolbarButton('historyUndo');
        $this->assertHTMLMatch('<p>%1% Content to test inserting images</p><p>Another paragraph in the content %2%</p>');

        $this->clickTopToolbarButton('historyRedo');
        $this->assertHTMLMatch('<p><img src="%url%/ViperImagePlugin/Images/editing.png" alt="Alt tag" title="Title tag"/> Content to test inserting images</p><p>Another paragraph in the content %2%</p>');

    }//end testUndoInsertingImage()


    /**
     * Test inserting an image, deleting it and then clicking undo.
     *
     * @return void
     */
    public function testUndoDeletingAnInsertedImage()
    {
        $this->useTest(1);

        $this->selectKeyword(1);

        $this->clickTopToolbarButton('image');
        $this->type($this->getTestURL('/ViperImagePlugin/Images/editing.png'));
        $this->sikuli->keyDown('Key.TAB');
        $this->type('Alt tag');
        $this->sikuli->keyDown('Key.TAB');
        $this->type('Title tag');
        $this->sikuli->keyDown('Key.ENTER');
        $this->clickTopToolbarButton('image', 'active-selected');
        $this->assertHTMLMatch('<p><img src="%url%/ViperImagePlugin/Images/editing.png" alt="Alt tag" title="Title tag"/> Content to test inserting images</p><p>Another paragraph in the content %2%</p>');

        $this->clickElement('img', 0);
        $this->sikuli->keyDown('Key.DELETE');
        $this->assertHTMLMatch('<p>Content to test inserting images</p><p>Another paragraph in the content %2%</p>');

        $this->clickTopToolbarButton('historyUndo');
        $this->assertHTMLMatch('<p><img src="%url%/ViperImagePlugin/Images/editing.png" alt="Alt tag" title="Title tag"/> Content to test inserting images</p><p>Another paragraph in the content %2%</p>');

    }//end testUndoDeletingAnInsertedImage()


    /**
     * Test inserting an image, deleting it and then clicking undo.
     *
     * @return void
     */
    public function testUndoAfterInsertingTwoImagesAndDeleteOne()
    {
        $this->useTest(1);

        $this->selectKeyword(1);

        // Insert first image
        $this->clickTopToolbarButton('image');
        $this->type($this->getTestURL('/ViperImagePlugin/Images/editing.png'));
        $this->sikuli->keyDown('Key.TAB');
        $this->type('Alt tag');
        $this->sikuli->keyDown('Key.TAB');
        $this->type('Title tag');
        $this->sikuli->keyDown('Key.ENTER');
        $this->clickTopToolbarButton('image', 'active-selected');
        $this->assertHTMLMatch('<p><img src="%url%/ViperImagePlugin/Images/editing.png" alt="Alt tag" title="Title tag"/> Content to test inserting images</p><p>Another paragraph in the content %2%</p>');

        // Insert second image
        $this->moveToKeyword(2, 'right');
        $this->clickTopToolbarButton('image');
        $this->type($this->getTestURL('/ViperImagePlugin/Images/editing.png'));
        $this->sikuli->keyDown('Key.TAB');
        $this->type('Alt tag');
        $this->sikuli->keyDown('Key.ENTER');
        $this->assertHTMLMatch('<p><img src="%url%/ViperImagePlugin/Images/editing.png" alt="Alt tag" title="Title tag"/> Content to test inserting images</p><p>Another paragraph in the content %2%<img src="%url%/ViperImagePlugin/Images/editing.png" alt="Alt tag"/></p>');

        // Delete first image
        $this->clickElement('img', 0);
        $this->sikuli->keyDown('Key.DELETE');
        $this->assertHTMLMatch('<p>Content to test inserting images</p><p>Another paragraph in the content %2%<img src="%url%/ViperImagePlugin/Images/editing.png" alt="Alt tag" /></p>');

        // Click undo
        $this->clickTopToolbarButton('historyUndo');
        $this->assertHTMLMatch('<p><img src="%url%/ViperImagePlugin/Images/editing.png" alt="Alt tag" title="Title tag"/> Content to test inserting images</p><p>Another paragraph in the content %2%<img src="%url%/ViperImagePlugin/Images/editing.png" alt="Alt tag"/></p>');

    }//end testUndoAfterInsertingTwoImagesAndDeleteOne()


    /**
     * Test editing the URL of the image in source code and clicking undo.
     *
     * @return void
     */
    public function testUndoEditUrlOfImage()
    {
        $this->useTest(2);

        $this->moveToKeyword(1);
        $this->clickTopToolbarButton('sourceView');
        sleep(2);
        $this->sikuli->keyDown('Key.CMD + a');
        $this->sikuli->keyDown('Key.DELETE');
        $this->type('<h1>Image without alt or title</h1><p>%1% XuT</p><p><img src="%url%/ViperImagePlugin/Images/hero-shot.jpg" alt=""/></p><p>LABS is ORSM</p>');
        $this->clickButton('Apply Changes', NULL, TRUE);
        $this->assertHTMLMatch('<h1>Image without alt or title</h1><p>%1% XuT</p><p><img src="%url%/ViperImagePlugin/Images/hero-shot.jpg" alt=""/></p><p>LABS is ORSM</p>');

        // Click undo
        $this->clickTopToolbarButton('historyUndo');
        $this->assertHTMLMatch('<h1>Image without alt or title</h1><p>%1% XuT</p><p><img src="%url%/ViperImagePlugin/Images/editing.png" alt=""/></p><p>LABS is ORSM</p>');

        // Click redo
        $this->clickTopToolbarButton('historyRedo');
        $this->assertHTMLMatch('<h1>Image without alt or title</h1><p>%1% XuT</p><p><img src="%url%/ViperImagePlugin/Images/hero-shot.jpg" alt=""/></p><p>LABS is ORSM</p>');

    }//end testUndoEditUrlOfImage()


    /**
     * Test editing an alt tag for an image and then clicking undo.
     *
     * @return void
     */
    public function testUndoEditingImage()
    {
        $this->useTest(2);

        // Edit the alt and title tag for the image
        $this->clickElement('img', 0);
        sleep(1);
        $this->clickInlineToolbarButton('image', 'active');
        $this->clickField('Image is decorative');
        sleep(1);
        $this->clickField('Alt');
        $this->type('Alt tag');
        $this->clickField('Title');
        $this->type('Title tag');
        $this->clickInlineToolbarButton('Update Image', NULL, TRUE);
        $this->assertHTMLMatch('<h1>Image without alt or title</h1><p>%1% XuT</p><p><img alt="Alt tag" src="%url%/ViperImagePlugin/Images/editing.png" title="Title tag" /></p><p>LABS is ORSM</p>');

        // Click undo
        $this->clickTopToolbarButton('historyUndo');
        $this->assertHTMLMatch('<h1>Image without alt or title</h1><p>%1% XuT</p><p><img src="%url%/ViperImagePlugin/Images/editing.png" alt=""/></p><p>LABS is ORSM</p>');

        // Click redo
        $this->clickTopToolbarButton('historyRedo');
        $this->assertHTMLMatch('<h1>Image without alt or title</h1><p>%1% XuT</p><p><img alt="Alt tag" src="%url%/ViperImagePlugin/Images/editing.png" title="Title tag" /></p><p>LABS is ORSM</p>');
        $this->checkResizeHandles('img');

    }//end testUndoEditingImage()


    /**
     * Test resizing an image and then clicking undo.
     *
     * @return void
     */
    public function testUndoResizeOfImage()
    {
        $this->useTest(2);

        $this->clickElement('img', 0);
        $this->resizeImage(50);
        $this->assertHTMLMatch('<h1>Image without alt or title</h1><p>%1% XuT</p><p><img alt="" height="45" src="%url%/ViperImagePlugin/Images/editing.png" width="50" /></p><p>LABS is ORSM</p>');

        $this->clickTopToolbarButton('historyUndo');
        $this->assertHTMLMatch('<h1>Image without alt or title</h1><p>%1% XuT</p><p><img src="%url%/ViperImagePlugin/Images/editing.png" alt=""/></p><p>LABS is ORSM</p>');

        $this->clickTopToolbarButton('historyRedo');
        $this->assertHTMLMatch('<h1>Image without alt or title</h1><p>%1% XuT</p><p><img alt="" height="45" src="%url%/ViperImagePlugin/Images/editing.png" width="50" /></p><p>LABS is ORSM</p>');
        $this->checkResizeHandles('img');

    }//end testUndoResizeOfImage()


    /**
     * Test resizing an image, deleting it and then clicking undo.
     *
     * @return void
     */
    public function testResizeImageDeleteItAndClickUndo()
    {
        $this->useTest(2);

        // Resize the image
        $this->clickElement('img', 0);
        sleep(2);
        $this->resizeImage(50);
        sleep(2);
        $this->assertHTMLMatch('<h1>Image without alt or title</h1><p>%1% XuT</p><p><img alt="" height="45" src="%url%/ViperImagePlugin/Images/editing.png" width="50" /></p><p>LABS is ORSM</p>');

        // Delete the image
        $this->clickKeyword(1);
        $this->clickElement('img', 0);
        sleep(2);
        $this->sikuli->keyDown('Key.DELETE');

        // Undo and check that the resized image was inserted into the content
        $this->clickTopToolbarButton('historyUndo');
        $this->assertHTMLMatch('<h1>Image without alt or title</h1><p>%1% XuT</p><p><img alt="" height="45" src="%url%/ViperImagePlugin/Images/editing.png" width="50" /></p><p>LABS is ORSM</p>');

    }//end testResizeImageDeleteItAndClickUndo()


    /**
     * Test resizing an image, deleting it and then clicking undo.
     *
     * @return void
     */
    public function testMaximumSizeResizeOfImage()
    {
        $this->useTest(2);

        // Test that you cannot resize the image past the maximum values.
        $this->clickElement('img', 0);
        sleep(2);
        $this->resizeImage(300);
        sleep(2);
        $this->assertHTMLMatch('<h1>Image without alt or title</h1><p>%1% XuT</p><p><img alt="" src="%url%/ViperImagePlugin/Images/editing.png" /></p><p>LABS is ORSM</p>');

        // Test that you can resize the image smaller than the original.
        $this->clickElement('img', 0);
        sleep(2);
        $this->resizeImage(50);
        sleep(2);
        $this->assertHTMLMatch('<h1>Image without alt or title</h1><p>%1% XuT</p><p><img alt="" height="45" src="%url%/ViperImagePlugin/Images/editing.png" width="50" /></p><p>LABS is ORSM</p>');

        // Test that you can resize the image back past the maximum.
        $this->clickElement('img', 0);
        sleep(2);
        $this->resizeImage(300);
        sleep(2);
        $this->assertHTMLMatch('<h1>Image without alt or title</h1><p>%1% XuT</p><p><img alt="" src="%url%/ViperImagePlugin/Images/editing.png" /></p><p>LABS is ORSM</p>');

    }//end testMaximumSizeResizeOfImage()


     /**
     * Test moving an image and then clicking undo.
     *
     * @return void
     */
    public function testUndoMoveImage()
    {
        $this->useTest(2);

        $this->clickElement('img', 0);
        $this->clickInlineToolbarButton('move');
        $this->sikuli->mouseMove($this->findKeyword(1));
        $this->sikuli->mouseMoveOffset(15, 0);
        $this->sikuli->click($this->sikuli->getMouseLocation());
        $this->assertHTMLMatch('<h1>Image without alt or title</h1><p>%1%<img alt="" src="%url%/ViperImagePlugin/Images/editing.png" /> XuT</p><p>LABS is ORSM</p>');

        // Undo the move
        $this->clickTopToolbarButton('historyUndo');
        $this->assertHTMLMatch('<h1>Image without alt or title</h1><p>%1% XuT</p><p><img src="%url%/ViperImagePlugin/Images/editing.png" alt=""/></p><p>LABS is ORSM</p>');

        // Redo the move
        $this->clickTopToolbarButton('historyRedo');
        $this->assertHTMLMatch('<h1>Image without alt or title</h1><p>%1%<img alt="" src="%url%/ViperImagePlugin/Images/editing.png" /> XuT</p><p>LABS is ORSM</p>');

    }//end testUndoMoveImage()

}//end class

?>
