<?php

require_once 'AbstractViperTableEditorPluginUnitTest.php';

class Viper_Tests_ViperTableEditorPlugin_GeneralTableUnitTest extends AbstractViperTableEditorPluginUnitTest
{

    /**
     * Test that you can open and close the table tools using the top toolbar.
     *
     * @return void
     */
    public function testUsingTableIconInTopToolbar()
    {
        $this->useTest(1);

        $this->insertTable(1);
        $this->clickCell(0);
        sleep(1);
        $this->assertTrue($this->topToolbarButtonExists('table', 'active'), 'Create table should be active');
        $this->clickTopToolbarButton('table', 'active');

        // Check to make sure the table editing tools appear.
        $this->assertTrue($this->topToolbarButtonExists('tableCell'), 'Table tools did not appear on screen');
        $this->clickTopToolbarButton('table', 'active');

        // Check to make sure the table editing tools don't appear.
        $this->assertFalse($this->topToolbarButtonExists('tableCell'), 'The table icons should no longer appear on the screen');

    }//end testUsingTableIconInTopToolbar()


    /**
     * Test that the table icon is not available for a list.
     *
     * @return void
     */
    public function testTableIconNotAvailableForList()
    {
        $this->useTest(7);

        // Check an unordered list
        $this->clickKeyword(1);
        $this->assertTrue($this->topToolbarButtonExists('table', 'disabled'), 'Table icon should not appear in the top toolbar.');

        $this->sikuli->keyDown('Key.SHIFT + Key.RIGHT');

        $this->assertTrue($this->topToolbarButtonExists('table', 'disabled'), 'Table icon should be disabled in the top toolbar.');

        $this->sikuli->keyDown('Key.TAB');
        $this->assertTrue($this->topToolbarButtonExists('table', 'disabled'), 'Table icon should not active in the top toolbar.');

        $this->clickKeyword(2);
        $this->selectKeyword(1);
        $this->assertTrue($this->topToolbarButtonExists('table', 'disabled'), 'Table icon should not appear in the top toolbar.');

        $this->selectInlineToolbarLineageItem(1);
        $this->assertTrue($this->topToolbarButtonExists('table', 'disabled'), 'Table icon should not appear in the top toolbar.');

        $this->selectInlineToolbarLineageItem(0);
        $this->assertTrue($this->topToolbarButtonExists('table', 'disabled'), 'Table icon should not appear in the top toolbar.');

        $this->sikuli->keyDown('Key.RIGHT');
        $this->sikuli->keyDown('Key.ENTER');
        $this->assertTrue($this->topToolbarButtonExists('table', 'disabled'), 'Table icon should not appear in the top toolbar.');

        $this->sikuli->keyDown('Key.ENTER');
        $this->type('New parra');
        $this->assertTrue($this->topToolbarButtonExists('table'), 'Table icon should appear in the top toolbar.');

        // Check an ordered list
        $this->clickKeyword(2);
        $this->assertTrue($this->topToolbarButtonExists('table', 'disabled'), 'Table icon should not appear in the top toolbar.');

        $this->sikuli->keyDown('Key.SHIFT + Key.RIGHT');
        $this->assertTrue($this->topToolbarButtonExists('table', 'disabled'), 'Table icon should be disabled in the top toolbar.');

        $this->sikuli->keyDown('Key.TAB');
        $this->assertTrue($this->topToolbarButtonExists('table', 'disabled'), 'Table icon should not active in the top toolbar.');

        $this->selectKeyword(2);
        $this->assertTrue($this->topToolbarButtonExists('table', 'disabled'), 'Table icon should not appear in the top toolbar.');

        $this->selectInlineToolbarLineageItem(1);
        $this->assertTrue($this->topToolbarButtonExists('table', 'disabled'), 'Table icon should not appear in the top toolbar.');

        $this->selectInlineToolbarLineageItem(0);
        $this->assertTrue($this->topToolbarButtonExists('table', 'disabled'), 'Table icon should not appear in the top toolbar.');

        $this->sikuli->keyDown('Key.RIGHT');
        $this->sikuli->keyDown('Key.ENTER');
        $this->assertTrue($this->topToolbarButtonExists('table', 'disabled'), 'Table icon should not appear in the top toolbar.');

        $this->sikuli->keyDown('Key.ENTER');
        $this->type('New parra');
        $this->assertTrue($this->topToolbarButtonExists('table'), 'Table icon should appear in the top toolbar.');

    }//end testTableIconNotAvailableForList()


    /**
     * Test that clicking in a cell shows the table editing icon.
     *
     * @return void
     */
    public function testTableEditingIconAndTools()
    {
        $this->useTest(1);

        $this->insertTable(1);

        // Get the first cell of the table.
        $tableRect = $this->getBoundingRectangle('table');
        sleep(1);

        $cellRect = $this->getBoundingRectangle('td');
        $region   = $this->sikuli->getRegionOnPage($cellRect);

        // Click inside the cell.
        $this->sikuli->click($region);

        $toolIconRect = $this->getBoundingRectangle('#test-ViperTEP', 0);
        $icon         = $this->sikuli->getRegionOnPage($toolIconRect);

        // Check that position is correct.
        $iconX = $this->sikuli->getPageX($this->sikuli->getCenter($icon));
        $iconY = $this->sikuli->getPageY($this->sikuli->getTopLeft($icon));

        $cellCenter = ($cellRect['x1'] + (($cellRect['x2'] - $cellRect['x1']) / 2));
        $cellBottom = $cellRect['y2'];

        $this->assertTrue((($cellCenter + 3) >= $iconX && ($cellCenter - 3) <= $iconX), 'X Position of table editing icon is incorrect.');
        $this->assertTrue(($cellBottom <= $iconY && $cellBottom + 20 > $iconY), 'Y Position of table editing icon is incorrect.');

        // Move mouse on top of the icon.
        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check to make sure the table editing tools appear.
        $this->assertTrue($this->inlineToolbarButtonExists('tableCell'));

        // Check the highlight for table icon.
        $this->sikuli->mouseMove($this->getToolsButton('table'));
        usleep(200);

        $highlightRect = $this->getCellHighlight();
        if (($this->_posDiff($highlightRect['x1'], $tableRect['x1']) > 3)
            || ($this->_posDiff($highlightRect['x2'], $tableRect['x2']) > 3)
            || ($this->_posDiff($highlightRect['y1'], $tableRect['y1']) > 3)
            || ($this->_posDiff($highlightRect['y2'], $tableRect['y2']) > 3)
        ) {
            $this->fail('Highlight of table is not in the correct position');
        }

        // Move mouse on top of the icon.
        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for row.
        $this->sikuli->mouseMove($this->getToolsButton('row'));
        usleep(200);

        // Note that +1 or -1 is for cellpadding.
        $highlightRect = $this->getCellHighlight();
        if (($this->_posDiff($highlightRect['x1'], $tableRect['x1']) > 3)
            || ($this->_posDiff($highlightRect['x2'], $tableRect['x2']) > 3)
            || ($this->_posDiff($highlightRect['y1'], $cellRect['y1']) > 3)
            || ($this->_posDiff($highlightRect['y2'], $cellRect['y2']) > 3)
        ) {
            $this->fail('Highlight of row is not in the correct position');
        }

        // Move mouse on top of the icon.
        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for cell.
        $this->sikuli->mouseMove($this->getToolsButton('col'));
        usleep(200);

        $highlightRect = $this->getCellHighlight();
        if (($this->_posDiff($highlightRect['x1'], $cellRect['x1']) > 3)
            || ($this->_posDiff($highlightRect['x2'], $cellRect['x2']) > 3)
            || ($this->_posDiff($highlightRect['y1'], $tableRect['y1']) > 3)
            || ($this->_posDiff($highlightRect['y2'], $tableRect['y2']) > 3)
        ) {
            $this->fail('Highlight of col is not in the correct position');
        }

        // Move mouse on top of the icon.
        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for cell.
        $this->sikuli->mouseMove($this->getToolsButton('cell'));
        usleep(200);

        $highlightRect = $this->getCellHighlight();
        if (($this->_posDiff($highlightRect['x1'], $cellRect['x1']) > 3)
            || ($this->_posDiff($highlightRect['x2'], $cellRect['x2']) > 3)
            || ($this->_posDiff($highlightRect['y1'], $cellRect['y1']) > 3)
            || ($this->_posDiff($highlightRect['y2'], $cellRect['y2']) > 3)
        ) {
            $this->fail('Highlight of cell is not in the correct position');
        }

    }//end testTableEditingIconAndTools()


    /**
     * Tests that table highlights work in complex tables.
     *
     * Complex tables have tbody, thead, tfoot, %2%, cells with rowspan and colspan.
     *
     * @return void
     */
    public function testComplextTableHighlights()
    {
        $this->useTest(2);

        // Test highlights in heading.
        $icon = $this->_getTableToolsIcon(0);

        $table   = $this->getBoundingRectangle('table');
        $tbody   = $this->getBoundingRectangle('tbody');
        $tfoot   = $this->getBoundingRectangle('tfoot');
        $thead   = $this->getBoundingRectangle('thead');
        $caption = $this->getBoundingRectangle('caption');

        $firstCell = $this->getBoundingRectangle('th');

        // Move mouse on top of the icon.
        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for table.
        $this->sikuli->mouseMove($this->getToolsButton('table'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $table['x1']);
        $this->assertHighlightPos($highlight['x2'], $table['x2']);
        $this->assertHighlightPos($highlight['y1'], $table['y1']);
        $this->assertHighlightPos($highlight['y2'], $tfoot['y2']);

        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for row.
        $this->sikuli->mouseMove($this->getToolsButton('row'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $tbody['x1']);
        $this->assertHighlightPos($highlight['x2'], $tbody['x2']);
        $this->assertHighlightPos($highlight['y1'], $thead['y1']);
        $this->assertHighlightPos($highlight['y2'], $thead['y2']);

        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for col.
        $this->sikuli->mouseMove($this->getToolsButton('col'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $firstCell['x1']);
        $this->assertHighlightPos($highlight['x2'], $firstCell['x2']);
        $this->assertHighlightPos($highlight['y1'], $thead['y1']);
        $this->assertHighlightPos($highlight['y2'], $tfoot['y2']);

        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for col.
        $this->sikuli->mouseMove($this->getToolsButton('cell'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $firstCell['x1']);
        $this->assertHighlightPos($highlight['x2'], $firstCell['x2']);
        $this->assertHighlightPos($highlight['y1'], $firstCell['y1']);
        $this->assertHighlightPos($highlight['y2'], $firstCell['y2']);

        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Test highlights in a cell with colspan.
        $icon = $this->_getTableToolsIcon(8);
        $colspanCell = $this->getBoundingRectangle('td', 5);

        // Move mouse on top of the icon.
        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for table.
        $this->sikuli->mouseMove($this->getToolsButton('table'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $table['x1']);
        $this->assertHighlightPos($highlight['x2'], $table['x2']);
        $this->assertHighlightPos($highlight['y1'], $caption['y1']);
        $this->assertHighlightPos($highlight['y2'], $tfoot['y2']);

        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for row.
        $this->sikuli->mouseMove($this->getToolsButton('row'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $tbody['x1']);
        $this->assertHighlightPos($highlight['x2'], $tbody['x2']);
        $this->assertHighlightPos($highlight['y1'], $colspanCell['y1']);
        $this->assertHighlightPos($highlight['y2'], $colspanCell['y2']);

        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for col.
        $this->sikuli->mouseMove($this->getToolsButton('col'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $colspanCell['x1']);
        $this->assertHighlightPos($highlight['x2'], $colspanCell['x2']);
        $this->assertHighlightPos($highlight['y1'], $thead['y1']);
        $this->assertHighlightPos($highlight['y2'], $tfoot['y2']);

        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for col.
        $this->sikuli->mouseMove($this->getToolsButton('cell'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $colspanCell['x1']);
        $this->assertHighlightPos($highlight['x2'], $colspanCell['x2']);
        $this->assertHighlightPos($highlight['y1'], $colspanCell['y1']);
        $this->assertHighlightPos($highlight['y2'], $colspanCell['y2']);

        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Test highlights in a row with rowspan.
        $icon = $this->_getTableToolsIcon(11);
        $colspanCell = $this->getBoundingRectangle('td', 8);

        // Move mouse on top of the icon.
        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for table.
        $this->sikuli->mouseMove($this->getToolsButton('table'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $table['x1']);
        $this->assertHighlightPos($highlight['x2'], $table['x2']);
        $this->assertHighlightPos($highlight['y1'], $caption['y1']);
        $this->assertHighlightPos($highlight['y2'], $tfoot['y2']);

        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for row.
        $this->sikuli->mouseMove($this->getToolsButton('row'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $tbody['x1']);
        $this->assertHighlightPos($highlight['x2'], $tbody['x2']);
        $this->assertHighlightPos($highlight['y1'], $colspanCell['y1']);
        $this->assertHighlightPos($highlight['y2'], $colspanCell['y2']);

        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for col.
        $this->sikuli->mouseMove($this->getToolsButton('col'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $colspanCell['x1']);
        $this->assertHighlightPos($highlight['x2'], $colspanCell['x2']);
        $this->assertHighlightPos($highlight['y1'], $thead['y1']);
        $this->assertHighlightPos($highlight['y2'], $tfoot['y2']);

        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for col.
        $this->sikuli->mouseMove($this->getToolsButton('cell'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $colspanCell['x1']);
        $this->assertHighlightPos($highlight['x2'], $colspanCell['x2']);
        $this->assertHighlightPos($highlight['y1'], $colspanCell['y1']);
        $this->assertHighlightPos($highlight['y2'], $colspanCell['y2']);

        // Test highlights in footer.
        $icon = $this->_getTableToolsIcon(3);
        $colspanCell = $this->getBoundingRectangle('td', 0);

        // Move mouse on top of the icon.
        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for table.
        $this->sikuli->mouseMove($this->getToolsButton('table'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $table['x1']);
        $this->assertHighlightPos($highlight['x2'], $table['x2']);
        $this->assertHighlightPos($highlight['y1'], $caption['y1']);
        $this->assertHighlightPos($highlight['y2'], $tfoot['y2']);

        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for row.
        $this->sikuli->mouseMove($this->getToolsButton('row'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $tbody['x1']);
        $this->assertHighlightPos($highlight['x2'], $tbody['x2']);
        $this->assertHighlightPos($highlight['y1'], $colspanCell['y1']);
        $this->assertHighlightPos($highlight['y2'], $colspanCell['y2']);

        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for col.
        $this->sikuli->mouseMove($this->getToolsButton('col'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $colspanCell['x1']);
        $this->assertHighlightPos($highlight['x2'], $colspanCell['x2']);
        $this->assertHighlightPos($highlight['y1'], $thead['y1']);
        $this->assertHighlightPos($highlight['y2'], $tfoot['y2']);

        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for col.
        $this->sikuli->mouseMove($this->getToolsButton('cell'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $colspanCell['x1']);
        $this->assertHighlightPos($highlight['x2'], $colspanCell['x2']);
        $this->assertHighlightPos($highlight['y1'], $colspanCell['y1']);
        $this->assertHighlightPos($highlight['y2'], $colspanCell['y2']);

    }//end testComplextTableHighlights()


    /**
     * Tests that table highlights work in complex tables with no footer.
     *
     * @return void
     */
    public function testComplextTableHighlightsNoFooter()
    {
        $this->useTest(3);

        // Test highlights in heading.
        $icon = $this->_getTableToolsIcon(7);

        $table   = $this->getBoundingRectangle('table');
        $tbody   = $this->getBoundingRectangle('tbody');
        $thead   = $this->getBoundingRectangle('thead');
        $caption = $this->getBoundingRectangle('caption');

        $cell = $this->getBoundingRectangle('td', 4);
        sleep(1);

        // Move mouse on top of the icon.
        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for table.
        $this->sikuli->mouseMove($this->getToolsButton('table'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $table['x1']);
        $this->assertHighlightPos($highlight['x2'], $table['x2']);
        $this->assertHighlightPos($highlight['y1'], $caption['y1']);
        $this->assertHighlightPos($highlight['y2'], $tbody['y2']);

        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for row.
        $this->sikuli->mouseMove($this->getToolsButton('row'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $tbody['x1']);
        $this->assertHighlightPos($highlight['x2'], $tbody['x2']);
        $this->assertHighlightPos($highlight['y1'], $cell['y1']);
        $this->assertHighlightPos($highlight['y2'], $cell['y2']);

        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for col.
        $this->sikuli->mouseMove($this->getToolsButton('col'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $cell['x1']);
        $this->assertHighlightPos($highlight['x2'], $cell['x2']);
        $this->assertHighlightPos($highlight['y1'], $thead['y1']);
        $this->assertHighlightPos($highlight['y2'], $tbody['y2']);

        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for col.
        $this->sikuli->mouseMove($this->getToolsButton('cell'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $cell['x1']);
        $this->assertHighlightPos($highlight['x2'], $cell['x2']);
        $this->assertHighlightPos($highlight['y1'], $cell['y1']);
        $this->assertHighlightPos($highlight['y2'], $cell['y2']);

    }//end testComplextTableHighlightsNoFooter()


    /**
     * Tests that table highlights work in complex tables with no header.
     *
     * @return void
     */
    public function testComplextTableHighlightsNoHeader()
    {
        $this->useTest(4);

        // Test highlights in heading.
        $icon = $this->_getTableToolsIcon(4);

        $table   = $this->getBoundingRectangle('table');
        $tbody   = $this->getBoundingRectangle('tbody');
        $tfoot   = $this->getBoundingRectangle('tfoot');
        $caption = $this->getBoundingRectangle('caption');

        $cell = $this->getBoundingRectangle('td', 4);
        sleep(1);

        // Move mouse on top of the icon.
        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for table.
        $this->sikuli->mouseMove($this->getToolsButton('table'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $table['x1']);
        $this->assertHighlightPos($highlight['x2'], $table['x2']);
        $this->assertHighlightPos($highlight['y1'], $caption['y1']);
        $this->assertHighlightPos($highlight['y2'], $tfoot['y2']);

        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for row.
        $this->sikuli->mouseMove($this->getToolsButton('row'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $tbody['x1']);
        $this->assertHighlightPos($highlight['x2'], $tbody['x2']);
        $this->assertHighlightPos($highlight['y1'], $cell['y1']);
        $this->assertHighlightPos($highlight['y2'], $cell['y2']);

        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for col.
        $this->sikuli->mouseMove($this->getToolsButton('col'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $cell['x1']);
        $this->assertHighlightPos($highlight['x2'], $cell['x2']);
        $this->assertHighlightPos($highlight['y1'], $tbody['y1']);
        $this->assertHighlightPos($highlight['y2'], $tfoot['y2']);

        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for col.
        $this->sikuli->mouseMove($this->getToolsButton('cell'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $cell['x1']);
        $this->assertHighlightPos($highlight['x2'], $cell['x2']);
        $this->assertHighlightPos($highlight['y1'], $cell['y1']);
        $this->assertHighlightPos($highlight['y2'], $cell['y2']);

    }//end testComplextTableHighlightsNoHeader()


    /**
     * Tests that table highlights work in complex tables with no %2%.
     *
     * @return void
     */
    public function testComplextTableHighlightsNoCaption()
    {
        $this->useTest(5, null);

        // Test highlights in heading.
        $icon = $this->_getTableToolsIcon(7);

        $table   = $this->getBoundingRectangle('table');
        $tbody   = $this->getBoundingRectangle('tbody');
        $tfoot   = $this->getBoundingRectangle('tfoot');
        $thead   = $this->getBoundingRectangle('thead');

        $cell = $this->getBoundingRectangle('td', 4);
        sleep(1);

        // Move mouse on top of the icon.
        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for table.
        $this->sikuli->mouseMove($this->getToolsButton('table'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $table['x1']);
        $this->assertHighlightPos($highlight['x2'], $table['x2']);
        $this->assertHighlightPos($highlight['y1'], $thead['y1']);
        $this->assertHighlightPos($highlight['y2'], $tfoot['y2']);

        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for row.
        $this->sikuli->mouseMove($this->getToolsButton('row'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $tbody['x1']);
        $this->assertHighlightPos($highlight['x2'], $tbody['x2']);
        $this->assertHighlightPos($highlight['y1'], $cell['y1']);
        $this->assertHighlightPos($highlight['y2'], $cell['y2']);

        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for col.
        $this->sikuli->mouseMove($this->getToolsButton('col'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $cell['x1']);
        $this->assertHighlightPos($highlight['x2'], $cell['x2']);
        $this->assertHighlightPos($highlight['y1'], $thead['y1']);
        $this->assertHighlightPos($highlight['y2'], $tfoot['y2']);

        $this->sikuli->mouseMove($icon);
        usleep(200);

        // Check the highlight for col.
        $this->sikuli->mouseMove($this->getToolsButton('cell'));
        usleep(200);
        $highlight = $this->getCellHighlight();
        $this->assertHighlightPos($highlight['x1'], $cell['x1']);
        $this->assertHighlightPos($highlight['x2'], $cell['x2']);
        $this->assertHighlightPos($highlight['y1'], $cell['y1']);
        $this->assertHighlightPos($highlight['y2'], $cell['y2']);

    }//end testComplextTableHighlightsNoCaption()


    /**
     * Test that table navigation (TAB) with keyboard works.
     *
     * @return void
     */
    public function testTableKeyboardNav2()
    {
        $this->useTest(1);

        $this->insertTable(1, 2, 2, 3);

        $this->clickCell(0);
        $this->sikuli->keyDown('Key.TAB');
        $this->sikuli->keyDown('Key.TAB');
        $this->type('2');
        $this->sikuli->keyDown('Key.TAB');
        $this->type('3');
        $this->sikuli->keyDown('Key.SHIFT + Key.TAB');
        $this->sikuli->keyDown('Key.SHIFT + Key.TAB');
        $this->type('1');

        $this->assertHTMLMatchNoHeaders('<p>Test %1%</p><table border="1" style="width: 100%;"><thead><tr><th></th><th>1</th><th>2</th></tr></thead><tbody><tr><td>3</td><td></td><td></td></tr></tbody></table>');

    }//end testTableKeyboardNav()


    /**
     * Test that table navigation (TAB) with keyboard works.
     *
     * @return void
     */
    public function testTableKeyboardNavWithRowAndColSpan()
    {
        $this->useTest(1);

        $this->insertTable(1, 0, 3, 3);
        $this->showTools(0, 'cell');
        $this->clickMergeSplitIcon('mergeDown');

        $this->showTools(3, 'cell');
        $this->clickMergeSplitIcon('mergeRight');

        $this->clickCell(0);

        // Make sure caret does not go out of table.
        $this->sikuli->keyDown('Key.SHIFT + Key.TAB');
        $this->sikuli->keyDown('Key.SHIFT + Key.TAB');
        $this->sikuli->keyDown('Key.SHIFT + Key.TAB');

        $this->sikuli->keyDown('Key.TAB');
        $this->sikuli->keyDown('Key.TAB');
        $this->sikuli->keyDown('Key.TAB');

        $this->type('2');
        $this->sikuli->keyDown('Key.TAB');
        $this->type('3');
        $this->sikuli->keyDown('Key.SHIFT + Key.TAB');
        $this->sikuli->keyDown('Key.SHIFT + Key.TAB');
        $this->type('1');
        $this->sikuli->keyDown('Key.TAB');
        $this->sikuli->keyDown('Key.TAB');
        $this->sikuli->keyDown('Key.TAB');
        $this->sikuli->keyDown('Key.TAB');
        $this->sikuli->keyDown('Key.TAB');
        $this->type('5');
        $this->sikuli->keyDown('Key.SHIFT + Key.TAB');
        $this->type('4');

        $this->assertHTMLMatchNoHeaders('<p>Test %1%</p><table style="width: 100%; " border="1"><tbody><tr><td rowspan="2"></td><td></td><td>1</td></tr><tr><td colspan="2">2</td></tr><tr><td>3</td><td></td><td>4</td></tr><tr><td>5</td><td></td><td></td></tr></tbody></table>');

    }//end testTableKeyboardNavWithRowNColSpan()


    /**
     * Test that table is removed when its last row is deleted.
     *
     * @return void
     */
    public function testRemoveTableOnLastRowDelete()
    {
        $this->useTest(1);

        $this->insertTable(1, 2, 2, 2);

        $this->showTools(0, 'row');
        $this->clickButton('delete');

        $this->showTools(0, 'row');
        $this->clickButton('delete');

        $this->assertHTMLMatch('<p>Test %1%</p>');

    }//end testRemoveTableOnLastRowDelete()


    /**
     * Test that table is removed when its last column is deleted.
     *
     * @return void
     */
    public function testRemoveTableOnLastColDelete()
    {
        $this->useTest(1);

        $this->insertTable(1, 2, 2, 2);

        $this->showTools(0, 'col');
        $this->clickButton('delete');

        $this->showTools(0, 'col');
        $this->clickButton('delete');

        $this->assertHTMLMatch('<p>Test %1%</p>');

    }//end testRemoveTableOnLastColDelete()


    /**
     * Tests that down arrow moves caret out of table.
     *
     * @return void
     */
    public function testTableNavigatingOutOfTable()
    {
        $this->useTest(1);

        $this->insertTable(1);
        $this->clickCell(11);
        $this->sikuli->keyDown('Key.DOWN');
        $this->type('test');

        $this->assertHTMLMatchNoHeaders('<p>Test %1%</p><table border="1" style="width: 100%;"><thead><tr><th></th><th></th><th></th><th></th></tr></thead><tbody><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr></tbody></table><p>test</p>');

    }//end testTableNavigatingOutOfTable()


    /**
     * Tests pasting content after an empty table.
     *
     * @return void
     */
    public function testPastingContentAfterAnEmptyTable()
    {
        $this->useTest(1);

        // Copy content
        $this->selectKeyword(1);
        $this->sikuli->keyDown('Key.CMD + c');

        // Insert table
        $this->insertTable(1);

        // Paste content after table
        $this->clickCell(11);
        $this->sikuli->keyDown('Key.DOWN');
        sleep(1);
        $this->sikuli->keyDown('Key.CMD + v');

        $this->assertHTMLMatchNoHeaders('<p>Test %1%</p><table border="1" style="width: 100%;"><thead><tr><th></th><th></th><th></th><th></th></tr></thead><tbody><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr></tbody></table><p>%1%</p>');

    }//end testPastingContentAfterAnEmptyTable()


    /**
     * Tests pasting content after a table.
     *
     * @return void
     */
    public function testPastingContentAfterATable()
    {
        $this->useTest(1);

        // Copy content
        $this->selectKeyword(1);
        $this->sikuli->keyDown('Key.CMD + c');

        // Insert table
        $this->insertTable(1);

        // Add some content
        $this->clickCell(9);
        $this->type('Cell 9');
        $this->clickCell(10);
        $this->type('Cell 10');
        $this->clickCell(11);
        $this->type('Cell 11');

        // Paste content after table
        $this->sikuli->keyDown('Key.DOWN');
        $this->sikuli->keyDown('Key.CMD + v');

        $this->assertHTMLMatchNoHeaders('<p>Test %1%</p><table border="1" style="width: 100%;"><thead><tr><th></th><th></th><th></th><th></th></tr></thead><tbody><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td>Cell 9</td><td>Cell 10</td><td>Cell 11</td></tr></tbody></table><p>%1%</p>');

    }//end testPastingContentAfterATable()


    /**
     * Tests that you can click inside an empty table that has been created in a div section.
     *
     * @return void
     */
    public function testClickingInsideEmptyTableInDiv()
    {
        $this->useTest(1);

        $this->selectKeyword(1);

        $this->clickTopToolbarButton('sourceView');
        sleep(2);
        $this->sikuli->keyDown('Key.CMD + a');
        $this->sikuli->keyDown('Key.DELETE');
        $this->type('<div><table style="width: 100%;"><tbody><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr></tbody></table></div>');

        $this->clickButton('Apply Changes', NULL, TRUE);

        $this->clickCell(0);
        $this->assertHTMLMatchNoHeaders('<div><table style="width: 100%;"><tbody><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr></tbody></table></div>');

    }//end testClickingInsideEmptyTableInDiv()


    /**
     * Test that removing content on page with table element as last element.
     *
     * @return void
     */
    public function testDeleteContentLastElementTable()
    {
        $this->useTest(6, null);

        $this->selectKeyword(1);
        $this->sikuli->keyDown('Key.CMD + a');
        $this->sikuli->keyDown('Key.DELETE');
        $this->type('test');
        $this->assertHTMLMatch('<p>test</p>');
        $this->sikuli->keyDown('Key.CMD + z');
        $this->sikuli->keyDown('Key.CMD + z');

        $this->selectKeyword(1, 3);
        $this->sikuli->keyDown('Key.DELETE');
        $this->type('test');
        $this->assertHTMLMatch('<p>test</p>');
        $this->sikuli->keyDown('Key.CMD + z');

    }//end testDeleteContentLastElementTable()


    /**
     * Tests using delete and backspace in the last cell of the table
     *
     * @return void
     */
    public function testDeleteAndBackspaceInLastCellOfTable()
    {
        $this->useTest(8, null);

        $this->clickCell(16);

        // When you press delete nothing should happen to the table
        $this->sikuli->keyDown('Key.DELETE');
        sleep(1);
        $this->assertTrue($this->topToolbarButtonExists('cssClass', 'disabled'));
        $this->assertTrue($this->topToolbarButtonExists('anchorID', 'disabled'));
        $this->sikuli->keyDown('Key.DELETE');
        sleep(1);
        $this->assertTrue($this->topToolbarButtonExists('cssClass', 'disabled'));
        $this->assertTrue($this->topToolbarButtonExists('anchorID', 'disabled'));
        $this->sikuli->keyDown('Key.DELETE');
        sleep(1);
        $this->assertTrue($this->topToolbarButtonExists('cssClass', 'disabled'));
        $this->assertTrue($this->topToolbarButtonExists('anchorID', 'disabled'));

        $this->assertHTMLMatchNoHeaders('<table style="width: 300px;" border="1" cellspacing="2" cellpadding="2"><tbody><tr><td style="width: 100px;" colspan="2">Survey</td><td rowspan="2">All Genders</td><td style="width: 100px;" colspan="2">By Gender</td></tr><tr><td></td><td></td><td>Male</td><td>Females</td></tr><tr><td></td><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td><td></td></tr></tbody></table>');

        // When you press backsapce nothing should happen to the table
        $this->sikuli->keyDown('Key.BACKSPACE');
        $this->assertTrue($this->topToolbarButtonExists('cssClass', 'disabled'));
        $this->assertTrue($this->topToolbarButtonExists('anchorID', 'disabled'));
        $this->sikuli->keyDown('Key.BACKSPACE');
        $this->assertTrue($this->topToolbarButtonExists('cssClass', 'disabled'));
        $this->assertTrue($this->topToolbarButtonExists('anchorID', 'disabled'));
        $this->sikuli->keyDown('Key.BACKSPACE');
        $this->assertTrue($this->topToolbarButtonExists('cssClass', 'disabled'));
        $this->assertTrue($this->topToolbarButtonExists('anchorID', 'disabled'));

        $this->assertHTMLMatchNoHeaders('<table style="width: 300px;" border="1" cellspacing="2" cellpadding="2"><tbody><tr><td style="width: 100px;" colspan="2">Survey</td><td rowspan="2">All Genders</td><td style="width: 100px;" colspan="2">By Gender</td></tr><tr><td></td><td></td><td>Male</td><td>Females</td></tr><tr><td></td><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td><td></td></tr></tbody></table>');

    }//end testDeleteAndBackspaceInLastCellOfTable()


    /**
     * Tests using delete and backspace in the last cell of the table
     *
     * @return void
     */
    public function testCreatingParagraphAfterTable()
    {
        $this->useTest(9);
        $this->clickCell(11);
        $this->sikuli->keyDown('Key.RIGHT');
        $this->sikuli->keyDown('Key.ENTER');
        $this->type('More test content.');
        
        $this->assertHTMLMatchNoHeaders('<p>Some test content. %1%</p><table style="width:100%;"><tbody><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr></tbody></table><p>More test content.</p>');

    }//end testCreatingParagraphAfterTable()


    /**
     * Tests that opening source view and applying changes without changes made will not change anything.
     *
     * @return void
     */
    public function testViewingSourceCodeWithClassContentAroundTable()
    {
        // Test content before table
        $this->useTest(11);
        $this->clickTopToolbarButton('sourceView');
        $this->clickButton('Apply Changes', NULL, TRUE);
        $this->assertHTMLMatchNoHeaders('<p class="test-class">Test content %1%</p><table style="width:100%;"><tbody><tr><th></th><th></th><th></th><th></th></tr><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr></tbody></table>');

        // Test content after table
        $this->useTest(12);
        $this->clickTopToolbarButton('sourceView');
        $this->clickButton('Apply Changes', NULL, TRUE);
        $this->assertHTMLMatchNoHeaders('<table style="width:100%;"><tbody><tr><th></th><th></th><th></th><th></th></tr><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr></tbody></table><p class="test-class">Test content %1%</p>');

    }//end testCreatingParagraphAfterTable()


    /**
     * Asserts that the highlight is in correct position.
     *
     * @param integer $actual   The highlight position.
     * @param integer $expected The expected position.
     * @param integer $maxDiff  The max difference between two positions.
     *
     * @return void
     */
    public function assertHighlightPos($actual, $expected, $maxDiff=3)
    {
        $diff = abs((int) ($actual - $expected));
        $this->assertTrue(($diff <= $maxDiff), 'Table highlight is not correct. Diff = '.$diff);

    }//end assertHighlightPos()


    /**
     * Retuns the icon for the table tools.
     *
     * @param integer $cellNum The cell to click.
     *
     * @return object
     */
    private function _getTableToolsIcon($cellNum)
    {
        $this->clickCell($cellNum);
        $icon =  NULL;

        try {
            $toolIconRect = $this->getBoundingRectangle('#test-ViperTEP', 0);
            $icon         = $this->sikuli->getRegionOnPage($toolIconRect);
        } catch (Exception $e) {
            $this->clickCell($cellNum);
            $toolIconRect = $this->getBoundingRectangle('#test-ViperTEP', 0);
            $icon         = $this->sikuli->getRegionOnPage($toolIconRect);
        }

        return $icon;

    }//end _getTableToolsIcon()


    /**
     * Returns the difference in pixel between two positions.
     *
     * @param integer $pos1 A position.
     * @param integer $pos2 A position.
     *
     * @return integer
     */
    private function _posDiff($pos1, $pos2)
    {
        return abs((int) ($pos1 - $pos2));

    }//end _posDiff()

}//end class

?>
