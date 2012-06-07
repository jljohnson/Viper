<?php

require_once 'AbstractViperUnitTest.php';

class Viper_Tests_ViperInlineToolbarPlugin_InlineToolbarListUnitTest extends AbstractViperUnitTest
{

    /**
     * Test that when you select the item tag in the lineage, all of the text in the item is selected.
     *
     * @return void
     */
    public function testSelectingItemTagInLineage()
    {
        $this->selectKeyword(1);

        $lineage = $this->getHtml('.ViperITP-lineage');
        $this->assertEquals('<li class="ViperITP-lineageItem">List</li><li class="ViperITP-lineageItem">Item</li><li class="ViperITP-lineageItem Viper-selected">Selection</li>', $lineage);

        $this->selectInlineToolbarLineageItem(1);

        $this->assertEquals('4 %1% templates', $this->getSelectedText(), 'List item is not selected.');

        $lineage = $this->getHtml('.ViperITP-lineage');
        $this->assertEquals('<li class="ViperITP-lineageItem">List</li><li class="ViperITP-lineageItem Viper-selected">Item</li><li class="ViperITP-lineageItem">Selection</li>', $lineage);

        $this->selectInlineToolbarLineageItem(2);
        $this->assertEquals('%1%', $this->getSelectedText(), 'Original selection is not selected');

        $this->selectKeyword(2);

        $lineage = $this->getHtml('.ViperITP-lineage');
        $this->assertEquals('<li class="ViperITP-lineageItem">List</li><li class="ViperITP-lineageItem">Item</li><li class="ViperITP-lineageItem Viper-selected">Selection</li>', $lineage);

        $this->selectInlineToolbarLineageItem(1);

        $this->assertEquals('%2% is cool', $this->getSelectedText(), 'List item is not selected.');

        $lineage = $this->getHtml('.ViperITP-lineage');
        $this->assertEquals('<li class="ViperITP-lineageItem">List</li><li class="ViperITP-lineageItem Viper-selected">Item</li><li class="ViperITP-lineageItem">Selection</li>', $lineage);

        $this->selectInlineToolbarLineageItem(2);
        $this->assertEquals('%2%', $this->getSelectedText(), 'Original selection is not selected');

    }//end testSelectingItemTagInLineage()


    /**
     * Test that when you select the list tag in the lineage, all items in the list are selected.
     *
     * @return void
     */
    public function testSelectingListTagInLineage()
    {
        $this->selectKeyword(1);

        $lineage = $this->getHtml('.ViperITP-lineage');
        $this->assertEquals('<li class="ViperITP-lineageItem">List</li><li class="ViperITP-lineageItem">Item</li><li class="ViperITP-lineageItem Viper-selected">Selection</li>', $lineage);

        $this->selectInlineToolbarLineageItem(0);

        $this->assertEquals('aaa bbbbb ccccc4 %1% templatesAudit XuT content', $this->getSelectedText(), 'List item is not selected.');

        $lineage = $this->getHtml('.ViperITP-lineage');
        $this->assertEquals('<li class="ViperITP-lineageItem Viper-selected">List</li><li class="ViperITP-lineageItem">Item</li><li class="ViperITP-lineageItem">Selection</li>', $lineage);

        $this->selectInlineToolbarLineageItem(2);
        $this->assertEquals('%1%', $this->getSelectedText(), 'Original selection is not selected');

        $this->selectKeyword(2);

        $lineage = $this->getHtml('.ViperITP-lineage');
        $this->assertEquals('<li class="ViperITP-lineageItem">List</li><li class="ViperITP-lineageItem">Item</li><li class="ViperITP-lineageItem Viper-selected">Selection</li>', $lineage);

        $this->selectInlineToolbarLineageItem(0);

        $this->assertEquals('Unordered list%2% is coolAnother list item', $this->getSelectedText(), 'List item is not selected.');

        $lineage = $this->getHtml('.ViperITP-lineage');
        $this->assertEquals('<li class="ViperITP-lineageItem Viper-selected">List</li><li class="ViperITP-lineageItem">Item</li><li class="ViperITP-lineageItem">Selection</li>', $lineage);

        $this->selectInlineToolbarLineageItem(2);
        $this->assertEquals('%2%', $this->getSelectedText(), 'Original selection is not selected');

    }//end testSelectingListTagInLineage()


    /**
     * Test switching between an item and a list.
     *
     * @return void
     */
    public function testSwitchingBetweenItemAndList()
    {
        $this->selectKeyword(1);

        $lineage = $this->getHtml('.ViperITP-lineage');
        $this->assertEquals('<li class="ViperITP-lineageItem">List</li><li class="ViperITP-lineageItem">Item</li><li class="ViperITP-lineageItem Viper-selected">Selection</li>', $lineage);

        $this->selectInlineToolbarLineageItem(1);
        $this->assertEquals('4 %1% templates', $this->getSelectedText(), 'List item is not selected.');
        $lineage = $this->getHtml('.ViperITP-lineage');
        $this->assertEquals('<li class="ViperITP-lineageItem">List</li><li class="ViperITP-lineageItem Viper-selected">Item</li><li class="ViperITP-lineageItem">Selection</li>', $lineage);

        $this->selectInlineToolbarLineageItem(0);
        $this->assertEquals('aaa bbbbb ccccc4 %1% templatesAudit XuT content', $this->getSelectedText(), 'List item is not selected.');

        $lineage = $this->getHtml('.ViperITP-lineage');
        $this->assertEquals('<li class="ViperITP-lineageItem Viper-selected">List</li><li class="ViperITP-lineageItem">Item</li><li class="ViperITP-lineageItem">Selection</li>', $lineage);

        $this->selectInlineToolbarLineageItem(1);
        $this->assertEquals('4 %1% templates', $this->getSelectedText(), 'List item is not selected.');
        $lineage = $this->getHtml('.ViperITP-lineage');
        $this->assertEquals('<li class="ViperITP-lineageItem">List</li><li class="ViperITP-lineageItem Viper-selected">Item</li><li class="ViperITP-lineageItem">Selection</li>', $lineage);

        $this->selectInlineToolbarLineageItem(2);
        $this->assertEquals('%1%', $this->getSelectedText(), 'Original selection is not selected');

    }//end testSwitchingBetweenItemAndList()


    /**
     * Test lineage changes when list is removed.
     *
     * @return void
     */
    public function testLineageWhenListRemoved()
    {
        $this->selectKeyword(1);

        $lineage = $this->getHtml('.ViperITP-lineage');
        $this->assertEquals('<li class="ViperITP-lineageItem">List</li><li class="ViperITP-lineageItem">Item</li><li class="ViperITP-lineageItem Viper-selected">Selection</li>', $lineage);

        $this->selectInlineToolbarLineageItem(0);
        $this->keyDown('Key.SHIFT + Key.TAB');

        $lineage = $this->getHtml('.ViperITP-lineage');
        $this->assertEquals('<li class="ViperITP-lineageItem Viper-selected">Selection</li>', $lineage);

        $this->selectKeyword(2);

        $lineage = $this->getHtml('.ViperITP-lineage');
        $this->assertEquals('<li class="ViperITP-lineageItem">List</li><li class="ViperITP-lineageItem">Item</li><li class="ViperITP-lineageItem Viper-selected">Selection</li>', $lineage);

        $this->selectInlineToolbarLineageItem(0);
        $this->keyDown('Key.SHIFT + Key.TAB');

        $lineage = $this->getHtml('.ViperITP-lineage');
        $this->assertEquals('<li class="ViperITP-lineageItem Viper-selected">Selection</li>', $lineage);

    }//end testLineageWhenListRemoved()


    /**
     * Test lineage when list created and removed.
     *
     * @return void
     */
    public function testLineageWhenListIsCreatedAndRemoved()
    {
        $this->selectKeyword(3);

        $lineage = $this->getHtml('.ViperITP-lineage');
        $this->assertEquals('<li class="ViperITP-lineageItem">P</li><li class="ViperITP-lineageItem Viper-selected">Selection</li>', $lineage);

        $this->selectKeyword(3);
        $this->keyDown('Key.TAB');

        $lineage = $this->getHtml('.ViperITP-lineage');
        $this->assertEquals('<li class="ViperITP-lineageItem">List</li><li class="ViperITP-lineageItem">Item</li><li class="ViperITP-lineageItem Viper-selected">Selection</li>', $lineage);

        $this->selectInlineToolbarLineageItem(1);
        $this->assertEquals('cPOc ccccc dddd. %3%', $this->getSelectedText(), 'List item is not selected.');

        $lineage = $this->getHtml('.ViperITP-lineage');
        $this->assertEquals('<li class="ViperITP-lineageItem">List</li><li class="ViperITP-lineageItem Viper-selected">Item</li><li class="ViperITP-lineageItem">Selection</li>', $lineage);

        $this->selectKeyword(3);
        $this->keyDown('Key.SHIFT + Key.TAB');
        $lineage = $this->getHtml('.ViperITP-lineage');
        $this->assertEquals('<li class="ViperITP-lineageItem">P</li><li class="ViperITP-lineageItem Viper-selected">Selection</li>', $lineage);

    }//end testLineageWhenListIsCreatedAndRemoved()


    /**
     * Test lineage when list created and removed.
     *
     * @return void
     */
    public function testLineageWhenCreatingSubLists()
    {
        $this->selectKeyword(1);
        $this->keyDown('Key.TAB');

        $lineage = $this->getHtml('.ViperITP-lineage');
        $this->assertEquals('<li class="ViperITP-lineageItem">List</li><li class="ViperITP-lineageItem">Item</li><li class="ViperITP-lineageItem">List</li><li class="ViperITP-lineageItem">Item</li><li class="ViperITP-lineageItem Viper-selected">Selection</li>', $lineage);

        $this->selectInlineToolbarLineageItem(3);
        $this->assertEquals('4 %1% templates', $this->getSelectedText(), 'List item is not selected.');
        $this->selectInlineToolbarLineageItem(2);
        $this->assertEquals('4 %1% templates', $this->getSelectedText(), 'List is not selected.');
        $this->selectInlineToolbarLineageItem(1);
        $this->assertEquals('aaa bbbbb ccccc4 %1% templates', $this->getSelectedText(), 'List item is not selected.');
        $this->selectInlineToolbarLineageItem(0);
        $this->assertEquals('aaa bbbbb ccccc4 %1% templatesAudit XuT content', $this->getSelectedText(), 'List item is not selected.');

        $this->selectKeyword(1);
        $this->keyDown('Key.SHIFT + Key.TAB');
        $lineage = $this->getHtml('.ViperITP-lineage');
        $this->assertEquals('<li class="ViperITP-lineageItem">List</li><li class="ViperITP-lineageItem">Item</li><li class="ViperITP-lineageItem Viper-selected">Selection</li>', $lineage);

        $this->selectKeyword(2);
        $this->keyDown('Key.TAB');

        $lineage = $this->getHtml('.ViperITP-lineage');
        $this->assertEquals('<li class="ViperITP-lineageItem">List</li><li class="ViperITP-lineageItem">Item</li><li class="ViperITP-lineageItem">List</li><li class="ViperITP-lineageItem">Item</li><li class="ViperITP-lineageItem Viper-selected">Selection</li>', $lineage);

        $this->selectInlineToolbarLineageItem(3);
        $this->assertEquals('%2% is cool', $this->getSelectedText(), 'List item is not selected.');
        $this->selectInlineToolbarLineageItem(2);
        $this->assertEquals('%2% is cool', $this->getSelectedText(), 'List is not selected.');
        $this->selectInlineToolbarLineageItem(1);
        $this->assertEquals('Unordered list%2% is cool', $this->getSelectedText(), 'List item is not selected.');
        $this->selectInlineToolbarLineageItem(0);
        $this->assertEquals('Unordered list%2% is coolAnother list item', $this->getSelectedText(), 'List item is not selected.');

        $this->selectKeyword(2);
        $this->keyDown('Key.SHIFT + Key.TAB');
        $lineage = $this->getHtml('.ViperITP-lineage');
        $this->assertEquals('<li class="ViperITP-lineageItem">List</li><li class="ViperITP-lineageItem">Item</li><li class="ViperITP-lineageItem Viper-selected">Selection</li>', $lineage);

    }//end testLineageWhenCreatingSubLists()


}//end class

?>
