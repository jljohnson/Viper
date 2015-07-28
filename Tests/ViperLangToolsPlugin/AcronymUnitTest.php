<?php

require_once 'AbstractViperUnitTest.php';

class Viper_Tests_ViperLangToolsPlugin_AcronymUnitTest extends AbstractViperUnitTest
{


    /**
     * Test that the acronym icon is not active when you don't select some text and when you select a block of text.
     *
     * @return void
     */
    public function testAcronymIconIsDisabled()
    {
        $this->selectKeyword(1);
        $this->clickTopToolbarButton('langTools');

        $this->assertTrue($this->topToolbarButtonExists('Acronym', NULL, TRUE), 'Acronym icon in Top Toolbar should be active.');

        $this->selectKeyword(1);
        sleep(1);
        $this->selectInlineToolbarLineageItem(0);
        $this->assertTrue($this->topToolbarButtonExists('langTools'), 'Language icon in Top Toolbar should not be active.');

        $this->clickTopToolbarButton('langTools');
        $this->assertTrue($this->topToolbarButtonExists('Acronym', 'disabled', TRUE), 'Acronym icon in Top Toolbar should not be active.');

    }//end testAcronymIconIsDisabled()


    /**
     * Test that the Apply Changes button remains inactvie.
     *
     * @return void
     */
    public function testUpdateChangesButton()
    {
        $textLoc = $this->findKeyword(1);

        $this->selectKeyword(1);
        $this->clickTopToolbarButton('langTools');
        $this->clickTopToolbarButton('Acronym', NULL, TRUE);

        // Check to make sure the update changes button is disabled.
        $this->assertTrue($this->topToolbarButtonExists('Apply Changes', 'disabled', TRUE), 'The update changes button should be disabled.');

    }//end testUpdateChangesButton()


    /**
     * Test that you can apply the acronym attribute to a word.
     *
     * @return void
     */
    public function testAddingAcronymToAWord()
    {

        $this->selectKeyword(1);
        $this->clickTopToolbarButton('langTools');
        $this->clickTopToolbarButton('Acronym', NULL, TRUE);
        $this->type('abc');
        $this->sikuli->keyDown('Key.ENTER');

        $this->assertHTMLMatch('<p>LOREM <acronym title="abc">%1%</acronym> %2%</p><p>sit amet <strong>%3%</strong></p><p>Squiz <acronym title="abc">%4%</acronym> is orsm</p><p>The <em>%5%</em> brown fox</p>');

        $this->sikuli->click($this->findKeyword(1));
        $this->selectKeyword(1);
        $this->assertTrue($this->topToolbarButtonExists('langTools', 'active'), 'Language icon in Top Toolbar should be active.');

        $this->clickTopToolbarButton('langTools', 'active');
        $this->assertTrue($this->topToolbarButtonExists('Acronym', 'active', TRUE), 'Acronym icon in Top Toolbar should be active.');

        $this->selectKeyword(2);
        $this->clickTopToolbarButton('langTools');
        $this->clickTopToolbarButton('Acronym', NULL, TRUE);
        $this->type('def');
        $this->clickTopToolbarButton('Apply Changes', NULL, TRUE);

        $this->assertHTMLMatch('<p>LOREM <acronym title="abc">%1%</acronym> <acronym title="def">%2%</acronym></p><p>sit amet <strong>%3%</strong></p><p>Squiz <acronym title="abc">%4%</acronym> is orsm</p><p>The <em>%5%</em> brown fox</p>');

        $this->sikuli->click($this->findKeyword(2));
        $this->selectKeyword(2);
        $this->assertTrue($this->topToolbarButtonExists('langTools', 'active'), 'Language icon in Top Toolbar should be active.');

        $this->clickTopToolbarButton('langTools', 'active');
        $this->assertTrue($this->topToolbarButtonExists('Acronym', 'active', TRUE), 'Acronym icon in Top Toolbar should be active.');

    }//end testAddingAcronymToAWord()


    /**
     * Test that you can remove a acronym from a word.
     *
     * @return void
     */
    public function testRemovingAcronymAttributeFromAWord()
    {

        $this->selectKeyword(4);
        $this->assertTrue($this->topToolbarButtonExists('langTools', 'active'), 'Language icon in Top Toolbar should be active.');

        $this->clickTopToolbarButton('langTools', 'active');
        $this->clickTopToolbarButton('Acronym', 'active', TRUE);

        $this->clearFieldValue('Acronym');
        $this->sikuli->keyDown('Key.ENTER');

        $this->assertHTMLMatch('<p>LOREM %1% %2%</p><p>sit amet <strong>%3%</strong></p><p>Squiz %4% is orsm</p><p>The <em>%5%</em> brown fox</p>');

        $this->sikuli->click($this->findKeyword(4));
        $this->selectKeyword(4);
        $this->assertTrue($this->topToolbarButtonExists('langTools'), 'Language icon is still active in the Top Toolbar.');

        // Reapply the abbreviation so we can delete it by using the update changes icon
        $this->clickTopToolbarButton('langTools');
        $this->clickTopToolbarButton('Acronym', NULL, TRUE);
        $this->type('abc');
        $this->sikuli->keyDown('Key.ENTER');

        $this->assertHTMLMatch('<p>LOREM %1% %2%</p><p>sit amet <strong>%3%</strong></p><p>Squiz <acronym title="abc">%4%</acronym> is orsm</p><p>The <em>%5%</em> brown fox</p>');

        $this->sikuli->click($this->findKeyword(4));
        $this->selectKeyword(4);
        $this->clickTopToolbarButton('langTools', 'active');
        $this->clickTopToolbarButton('Acronym', 'active', TRUE);
        $this->clearFieldValue('Acronym');
        $this->clickTopToolbarButton('Apply Changes', NULL, TRUE);

        $this->assertHTMLMatch('<p>LOREM %1% %2%</p><p>sit amet <strong>%3%</strong></p><p>Squiz %4% is orsm</p><p>The <em>%5%</em> brown fox</p>');

    }//end testRemovingAcronymAttributeFromAWord()


    /**
     * Test that you can edit an acronym that has been applied to a word.
     *
     * @return void
     */
    public function testEditingAcronym()
    {

        $this->selectKeyword(4);
        $this->assertTrue($this->topToolbarButtonExists('langTools', 'active'), 'Class icon in Top Toolbar should be active.');

        $this->clickTopToolbarButton('langTools', 'active');
        $this->clickTopToolbarButton('Acronym', 'active', TRUE);
        $this->type('def');
        $this->sikuli->keyDown('Key.ENTER');

        $this->assertHTMLMatch('<p>LOREM %1% %2%</p><p>sit amet <strong>%3%</strong></p><p>Squiz <acronym title="abcdef">%4%</acronym> is orsm</p><p>The <em>%5%</em> brown fox</p>');

        $this->sikuli->click($this->findKeyword(4));
        $this->selectKeyword(4);
        $this->clickTopToolbarButton('langTools', 'active');
        $this->clickTopToolbarButton('Acronym', 'active', TRUE);
        $this->type('ghi');
        $this->clickTopToolbarButton('Apply Changes', NULL, TRUE);

        $this->assertHTMLMatch('<p>LOREM %1% %2%</p><p>sit amet <strong>%3%</strong></p><p>Squiz <acronym title="abcdefghi">%4%</acronym> is orsm</p><p>The <em>%5%</em> brown fox</p>');

    }//end testEditingAcronym()


    /**
     * Test that you can apply an acronym to a word that is bold.
     *
     * @return void
     */
    public function testAddingAcronymToABoldWord()
    {

        $this->selectKeyword(3);
        $this->clickTopToolbarButton('langTools');
        $this->clickTopToolbarButton('Acronym', NULL, TRUE);
        $this->type('abc');
        $this->sikuli->keyDown('Key.ENTER');

        $this->assertHTMLMatch('<p>LOREM %1% %2%</p><p>sit amet <acronym title="abc"><strong>%3%</strong></acronym></p><p>Squiz <acronym title="abc">%4%</acronym> is orsm</p><p>The <em>%5%</em> brown fox</p>');

    }//end testAddingAcronymToABoldWord()


    /**
     * Test that you can apply an acronym to a word that is italic.
     *
     * @return void
     */
    public function testAddingAcronymToAItalicWord()
    {

        $this->selectKeyword(5);
        $this->clickTopToolbarButton('langTools');
        $this->clickTopToolbarButton('Acronym', NULL, TRUE);
        $this->type('abc');
        $this->sikuli->keyDown('Key.ENTER');

        $this->assertHTMLMatch('<p>LOREM %1% %2%</p><p>sit amet <strong>%3%</strong></p><p>Squiz <acronym title="abc">%4%</acronym> is orsm</p><p>The <acronym title="abc"><em>%5%</em></acronym> brown fox</p>');

    }//end testAddingAcronymToAItalicWord()


    /**
     * Test that selection is maintained when switching between acronym and class.
     *
     * @return void
     */
    public function testSelectionIsMaintainedWhenSwitchingFromAcronymToClass()
    {
        $this->selectKeyword(1);

        $this->clickTopToolbarButton('langTools');
        $this->clickTopToolbarButton('Acronym', NULL, TRUE);
        $this->assertEquals('%1%', $this->getSelectedText(), 'Selected text is not highlighted.');

        $this->clickTopToolbarButton('cssClass');
        $this->assertEquals('%1%', $this->getSelectedText(), 'Selected text is not highlighted.');

        $this->clickTopToolbarButton('langTools');
        $this->assertEquals('%1%', $this->getSelectedText(), 'Selected text is not highlighted.');

    }//end testSelectionIsMaintainedWhenSwitchingFromAcronymToClass()


    /**
     * Test undo and redo for acronym's.
     *
     * @return void
     */
    public function testUndoAndRedoForAcronym()
    {

        $this->selectKeyword(1);
        $this->clickTopToolbarButton('langTools');
        $this->clickTopToolbarButton('Acronym', NULL, TRUE);
        $this->type('abc');
        $this->sikuli->keyDown('Key.ENTER');

        $this->assertHTMLMatch('<p>LOREM <acronym title="abc">%1%</acronym> %2%</p><p>sit amet <strong>%3%</strong></p><p>Squiz <acronym title="abc">%4%</acronym> is orsm</p><p>The <em>%5%</em> brown fox</p>');

       $this->clickTopToolbarButton('historyUndo');
       $this->assertHTMLMatch('<p>LOREM %1% %2%</p><p>sit amet <strong>%3%</strong></p><p>Squiz <acronym title="abc">%4%</acronym> is orsm</p><p>The <em>%5%</em> brown fox</p>');

       $this->clickTopToolbarButton('historyRedo');
       $this->assertHTMLMatch('<p>LOREM <acronym title="abc">%1%</acronym> %2%</p><p>sit amet <strong>%3%</strong></p><p>Squiz <acronym title="abc">%4%</acronym> is orsm</p><p>The <em>%5%</em> brown fox</p>');

    }//end testUndoAndRedoForAcronym()


    /**
     * Test applying acronym, deleting it and then clicking undo.
     *
     * @return void
     */
    public function testUndoAfterDeletingAcronym()
    {
        // Apply acronym
        $this->selectKeyword(1);
        $this->clickTopToolbarButton('langTools');
        $this->clickTopToolbarButton('Acronym', NULL, TRUE);
        $this->clickField('Acronym');
        $this->type('abc');
        $this->sikuli->keyDown('Key.ENTER');
        $this->assertHTMLMatch('<p>LOREM <acronym title="abc">%1%</acronym> %2%</p><p>sit amet <strong>%3%</strong></p><p>Squiz <acronym title="abc">%4%</acronym> is orsm</p><p>The <em>%5%</em> brown fox</p>');

        // Delete abbreviation
        $this->selectKeyword(1);
        $this->clickTopToolbarButton('langTools', 'active');
        $this->clickTopToolbarButton('Acronym', 'active', TRUE);
        $this->clearFieldValue('Acronym');
        $this->sikuli->keyDown('Key.ENTER');
        $this->clickTopToolbarButton('langTools', 'selected');
        $this->assertHTMLMatch('<p>LOREM %1% %2%</p><p>sit amet <strong>%3%</strong></p><p>Squiz <acronym title="abc">%4%</acronym> is orsm</p><p>The <em>%5%</em> brown fox</p>');

        // Press undo
        $this->clickTopToolbarButton('historyUndo');
        $this->assertHTMLMatch('<p>LOREM <acronym title="abc">%1%</acronym> %2%</p><p>sit amet <strong>%3%</strong></p><p>Squiz <acronym title="abc">%4%</acronym> is orsm</p><p>The <em>%5%</em> brown fox</p>');

        $this->clickTopToolbarButton('historyRedo');
        $this->assertHTMLMatch('<p>LOREM %1% %2%</p><p>sit amet <strong>%3%</strong></p><p>Squiz <acronym title="abc">%4%</acronym> is orsm</p><p>The <em>%5%</em> brown fox</p>');

    }//end testUndoAfterDeletingAcronym()


}//end class

?>