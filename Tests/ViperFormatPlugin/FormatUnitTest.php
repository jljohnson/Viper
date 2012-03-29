<?php

require_once 'AbstractViperUnitTest.php';

class Viper_Tests_ViperFormatPlugin_FormatUnitTest extends AbstractViperUnitTest
{


    /**
     * Test that selecting text does not show formatting icons in VITP.
     *
     * @return void
     */
    public function testTextSelectionNoOptions()
    {
        $text = $this->find('Lorem');
        $this->selectText('Lorem');

        $dir = dirname(__FILE__).'/Images/';
        $this->assertFalse($this->inlineToolbarButtonExists($dir.'toolbarIcon_heading.png'), 'VITP Heading icon should not be available for text selection');
        $this->assertFalse($this->inlineToolbarButtonExists($dir.'toolbarIcon_div.png'), 'VITP format icons should not be available for text selection');

        $this->click($text);
        $this->selectText('WoW');
        $this->assertFalse($this->inlineToolbarButtonExists($dir.'toolbarIcon_heading.png'), 'VITP Heading icon should not be available for text selection');
        $this->assertFalse($this->inlineToolbarButtonExists($dir.'toolbarIcon_div.png'), 'VITP format icons should not be available for text selection');

    }//end testTextSelectionNoOptions()


    /**
     * Test that block formats (blockquote, P, DIV, PRE) works.
     *
     * @return void
     */
    public function testBlockFormats()
    {

        $dir = dirname(__FILE__).'/Images/';

        $text = 'Lorem';

        $this->selectText($text);
        $this->selectInlineToolbarLineageItem(0);
        $this->clickInlineToolbarButton($dir.'toolbarIcon_toggle_formats_highlighted.png');
        $this->clickInlineToolbarButton($dir.'toolbarIcon_pre.png');
        sleep(1);
        $this->assertTrue($this->inlineToolbarButtonExists($dir.'toolbarIcon_pre_active.png'), 'Toolbar icon not found: toolbarIcon_pre_active.png');
        $this->assertHTMLMatch('<h1>Heading One</h1><pre>Lorem xtn dolor</pre><p>sit amet <strong>WoW</strong></p>');

        $this->click($this->find($text));
        $this->selectText($text);
        $this->selectInlineToolbarLineageItem(0);
        $this->clickInlineToolbarButton($dir.'toolbarIcon_toggle_formats_highlighted.png');
        $this->clickInlineToolbarButton($dir.'toolbarIcon_blockquote.png');
        sleep(1);
        $this->assertTrue($this->inlineToolbarButtonExists($dir.'toolbarIcon_blockquote_active.png'), 'Toolbar icon not found: toolbarIcon_blockquote_active.png');
        $this->assertHTMLMatch('<h1>Heading One</h1><blockquote>Lorem xtn dolor</blockquote><p>sit amet <strong>WoW</strong></p>');

        $this->click($this->find($text));
        $this->selectText($text);
        $this->selectInlineToolbarLineageItem(0);
        $this->clickInlineToolbarButton($dir.'toolbarIcon_toggle_formats_highlighted.png');
        $this->clickInlineToolbarButton($dir.'toolbarIcon_div.png');
        sleep(1);
        $this->assertTrue($this->inlineToolbarButtonExists($dir.'toolbarIcon_div_active.png'), 'Toolbar icon not found: toolbarIcon_div_active.png');
        $this->assertHTMLMatch('<h1>Heading One</h1><div>Lorem xtn dolor</div><p>sit amet <strong>WoW</strong></p>');

        $this->click($this->find($text));
        $this->selectText($text);
        $this->selectInlineToolbarLineageItem(0);
        $this->clickInlineToolbarButton($dir.'toolbarIcon_toggle_formats_highlighted.png');
        $this->clickInlineToolbarButton($dir.'toolbarIcon_p.png');
        sleep(1);
        $this->assertTrue($this->inlineToolbarButtonExists($dir.'toolbarIcon_p_active.png'), 'Toolbar icon not found: toolbarIcon_p_active.png');
        $this->assertHTMLMatch('<h1>Heading One</h1><p>Lorem xtn dolor</p><p>sit amet <strong>WoW</strong></p>');


    }//end testBlockFormats()


    /**
     * Test that selecting text does not show formatting icons in VITP.
     *
     * @return void
     */
    public function testMultiParentNoOpts()
    {
        $this->selectText('amet', 'WoW');

        $dir = dirname(__FILE__).'/Images/';
        $this->assertFalse($this->inlineToolbarButtonExists($dir.'toolbarIcon_heading.png'), 'VITP Heading icon should not be available for text selection');
        $this->assertFalse($this->inlineToolbarButtonExists($dir.'toolbarIcon_div.png'), 'VITP format icons should not be available for text selection');
        $this->assertFalse($this->inlineToolbarButtonExists($dir.'toolbarIcon_class.png'), 'Class icon in VITP should not be active.');
        $this->assertFalse($this->inlineToolbarButtonExists($dir.'toolbarIcon_anchor.png'), 'Anchor icon in VITP should not be active.');

    }//end testMultiParentNoOpts()


    /**
     * Test that you can create a new P section inside a DIV and outside the DIV section.
     *
     * @return void
     */
    public function testCreatingNewPBeforeAndAfterDivSection()
    {
        $dir = dirname(__FILE__).'/Images/';

        $this->selectText('Lorem', 'WoW');
        $this->clickTopToolbarButton($dir.'toolbarIcon_toggle_formats.png');
        $this->clickTopToolbarButton($dir.'toolbarIcon_div.png');
        $this->assertHTMLMatch('<h1>Heading One</h1><div><p>Lorem xtn dolor</p><p>sit amet <strong>WoW</strong></p></div>');

        $this->selectText('WoW');
        $this->keyDown('Key.RIGHT');
        $this->keyDown('Key.ENTER');
        $this->type('test new line XuT');
        $this->assertHTMLMatch('<h1>Heading One</h1><div><p>Lorem xtn dolor</p><p>sit amet <strong>WoW</strong></p><p>test new line XuT</p></div>');

        $this->selectText('XuT');
        $this->keyDown('Key.RIGHT');
        $this->keyDown('Key.ENTER');
        $this->keyDown('Key.ENTER');
        $this->type('test new paragraph');
        $this->assertHTMLMatch('<h1>Heading One</h1><div><p>Lorem xtn dolor</p><p>sit amet <strong>WoW</strong></p><p>test new line XuT</p></div><p>test new paragraph</p>');

    }//end testCreatingNewPBeforeAndAfterDivSection()


    /**
     * Test that multiple P and DIV tags together in the content.
     *
     * @return void
     */
    public function testUsingMultiplePAndDivTagsInContent()
    {
        $dir = dirname(__FILE__).'/Images/';

        $this->selectText('Lorem', 'WoW');
        $this->clickTopToolbarButton($dir.'toolbarIcon_toggle_formats.png');
        $this->clickTopToolbarButton($dir.'toolbarIcon_div.png');
        $this->assertHTMLMatch('<h1>Heading One</h1><div><p>Lorem xtn dolor</p><p>sit amet <strong>WoW</strong></p></div>');

        $this->selectText('WoW');
        $this->keyDown('Key.RIGHT');
        $this->keyDown('Key.ENTER');
        $this->keyDown('Key.ENTER');
        $this->type('TEST new div section');
        $this->keyDown('Key.ENTER');
        $this->type('with two paragraphs XuT');
        $this->selectText('TEST', 'XuT');
        $this->clickTopToolbarButton($dir.'toolbarIcon_toggle_formats.png');
        $this->clickTopToolbarButton($dir.'toolbarIcon_div.png');

        $this->assertHTMLMatch('<h1>Heading One</h1><div><p>Lorem xtn dolor</p><p>sit amet <strong>WoW</strong></p></div><div><p>TEST new div section</p><p>with two paragraphs XuT</p></div>');

        $this->selectText('Lorem', 'XuT');
        $this->clickTopToolbarButton($dir.'toolbarIcon_toggle_formats.png');
        $this->clickTopToolbarButton($dir.'toolbarIcon_div.png');
        $this->assertHTMLMatch('<h1>Heading One</h1><div><div><p>Lorem xtn dolor</p><p>sit amet <strong>WoW</strong></p></div><div><p>TEST new div section</p><p>with two paragraphs XuT</p></div></div>');

        $this->selectText('WoW');
        $this->keyDown('Key.RIGHT');
        $this->keyDown('Key.ENTER');
        $this->keyDown('Key.ENTER');
        $this->type('new paragraph in parent div');
        $this->keyDown('Key.ENTER');
        $this->keyDown('Key.ENTER');
        $this->type('new paragraph in parent div');
        $this->assertHTMLMatch('<h1>Heading One</h1><div><div><p>Lorem xtn dolor</p><p>sit amet <strong>WoW</strong></p></div><p>new paragraph in parent div</p><p>new paragraph in parent div</p><div><p>TEST new div section</p><p>with two paragraphs XuT</p></div></div>');

        $this->selectText('WoW');
        $this->keyDown('Key.RIGHT');
        $this->keyDown('Key.ENTER');
        $this->type('new paragraph in child div');
        $this->assertHTMLMatch('<h1>Heading One</h1><div><div><p>Lorem xtn dolor</p><p>sit amet <strong>WoW</strong></p><p>new paragraph in child div</p></div><p>new paragraph in parent div</p><p>new paragraph in parent div</p><div><p>TEST new div section</p><p>with two paragraphs XuT</p></div></div>');

        $this->selectText('XuT');
        $this->keyDown('Key.RIGHT');
        $this->keyDown('Key.ENTER');
        $this->keyDown('Key.ENTER');
        $this->keyDown('Key.ENTER');
        $this->type('new paragraph outside parent div');
        $this->assertHTMLMatch('<h1>Heading One</h1><div><div><p>Lorem xtn dolor</p><p>sit amet <strong>WoW</strong></p><p>new paragraph in child div</p></div><p>new paragraph in parent div</p><p>new paragraph in parent div</p><div><p>TEST new div section</p><p>with two paragraphs XuT</p></div></div><p>new paragraph outside parent div</p>');

        $this->selectText('XuT');
        $this->keyDown('Key.RIGHT');
        $this->keyDown('Key.ENTER');
        $this->keyDown('Key.ENTER');
        $this->type('new paragraph inside parent div');
        $this->assertHTMLMatch('<h1>Heading One</h1><div><div><p>Lorem xtn dolor</p><p>sit amet <strong>WoW</strong></p><p>new paragraph in child div</p></div><p>new paragraph in parent div</p><p>new paragraph in parent div</p><div><p>TEST new div section</p><p>with two paragraphs XuT</p></div><p>new paragraph inside parent div</p></div><p>new paragraph outside parent div</p>');

        $this->selectText('XuT');
        $this->keyDown('Key.RIGHT');
        $this->keyDown('Key.ENTER');
        $this->type('new paragraph inside child div');
        $this->assertHTMLMatch('<h1>Heading One</h1><div><div><p>Lorem xtn dolor</p><p>sit amet <strong>WoW</strong></p><p>new paragraph in child div</p></div><p>new paragraph in parent div</p><p>new paragraph in parent div</p><div><p>TEST new div section</p><p>with two paragraphs XuT</p><p>new paragraph inside child div</p></div><p>new paragraph inside parent div</p></div><p>new paragraph outside parent div</p>');


    }//end testUsingMultiplePAndDivTagsInContent()


}//end class

?>
