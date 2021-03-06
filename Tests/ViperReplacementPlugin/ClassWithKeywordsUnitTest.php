<?php

require_once 'AbstractViperCustomClassStylesUnitTest.php';

class Viper_Tests_ViperReplacementPlugin_ClassWithKeywordsUnitTest extends AbstractViperCustomClassStylesUnitTest
{

    /**
     * Test apply and remove a keyword as a class to normal content.
     *
     * @return void
     */
    public function testApplyAndRemoveClassKeywordToContent()
    {
        foreach ($this->getTestMethods(TRUE, TRUE, FALSE) as $method) {
            $this->useTest(1);
            $this->selectKeyword(1);
            $this->doAction($method, 'cssClass');
            $this->type('((prop:className))');
            $this->sikuli->KeyDown('Key.ENTER');
            $this->assertEquals('((prop:className))', $this->getFieldValue('Class'), 'Class field should not be empty');
            $this->assertHTMLMatch('<p>Test content <span class="((prop:className))">%1%</span> more test content</p>');
            $this->assertRawHTMLMatch('<p>Test content<span class="replaced-className" data-viper-attribute-keywords="true" data-viper-class="((prop:className))">%1%</span> more test content</p>');

            $this->selectKeyword(1);
            $this->doAction($method, 'cssClass', 'active');
            $this->clearFieldValue('Class');
            $this->sikuli->keyDown('Key.ENTER');
            $this->assertEquals('', $this->getFieldValue('Class'), 'Class field should be empty');
            $this->assertHTMLMatch('<p>Test content %1% more test content</p>');
            $this->assertRawHTMLMatch('<p>Test content %1% more test content</p>');
        }

    }//end testApplyAndRemoveClassKeywordToContent()


    /**
     * Test applying and removing a keyword as a class class and a normal class to normal content.
     *
     * @return void
     */
    public function testApplyAndRemoveClassKeywordWithNormalClassToContent()
    {
        foreach ($this->getTestMethods(TRUE, TRUE, FALSE) as $method) {
            $this->useTest(1);
            $this->selectKeyword(1);
            $this->doAction($method, 'cssClass');
            $this->type('test ((prop:className))');
            $this->sikuli->KeyDown('Key.ENTER');
            $this->assertEquals('test ((prop:className))', $this->getFieldValue('Class'), 'Class field should not be empty');
            $this->assertHTMLMatch('<p>Test content <span class="test ((prop:className))">%1%</span> more test content</p>');
            $this->assertRawHTMLMatch('<p>Test content<span class="test replaced-className" data-viper-attribute-keywords="true" data-viper-class="test ((prop:className))">%1%</span> more test content</p>');

            $this->selectKeyword(1);
            $this->doAction($method, 'cssClass', 'active');
            $this->clearFieldValue('Class');
            $this->sikuli->keyDown('Key.ENTER');
            $this->assertEquals('', $this->getFieldValue('Class'), 'Class field should be empty');
            $this->assertHTMLMatch('<p>Test content %1% more test content</p>');
            $this->assertRawHTMLMatch('<p>Test content %1% more test content</p>');
        }


    }//end testApplyAndRemoveClassKeywordWithNormalClassToContent()


    /**
     * Test applying and removing a keyword as a class class and a custom class to normal content.
     *
     * @return void
     */
    public function testApplyAndRemoveClassKeywordWithCustomClassToContent()
    {
        $this->setCustomClassStyles();

        foreach ($this->getTestMethods(TRUE, TRUE, FALSE) as $method) {
            $this->useTest(1);
            $this->selectKeyword(1);
            $this->doAction($method, 'cssClass');
            $this->type('((prop:className))');
            $this->selectStyles(array('ordered-list'));
            $this->sikuli->KeyDown('Key.ENTER');
            $this->assertHTMLMatch('<p>Test content <span class="((prop:className)) ordered-list">%1%</span> more test content</p>');
            $this->assertRawHTMLMatch('<p>Test content<span class="replaced-className ordered-list" data-viper-attribute-keywords="true" data-viper-class="((prop:className)) ordered-list">%1%</span> more test content</p>');

            $this->selectKeyword(1);
            $this->doAction($method, 'cssClass', 'active');
            $selectedStyles = $this->getSelectedStyles();
            $this->removeStyles($selectedStyles);
            $this->clearFieldValue('Class');
            $this->sikuli->keyDown('Key.ENTER');
            $this->assertEquals('', $this->getFieldValue('Class'), 'Class field should be empty');
            $this->assertHTMLMatch('<p>Test content %1% more test content</p>');
            $this->assertRawHTMLMatch('<p>Test content %1% more test content</p>');
        }

    }//end testApplyAndRemoveClassKeywordWithCustomClassToContent()


    /**
     * Test applying and removing a class keyword to a keyword.
     *
     * @return void
     */
    public function testApplyAndRemoveClassKeywordToKeyword()
    {
        foreach ($this->getTestMethods(TRUE, TRUE, FALSE) as $method) {
            $this->useTest(2, 1);
            $this->clickKeyword(5);
            $this->doAction($method, 'cssClass');
            $this->type('((prop:className))');
            $this->sikuli->KeyDown('Key.ENTER');
            $this->assertEquals('((prop:className))', $this->getFieldValue('Class'), 'Class field should not be empty');
            $this->assertHTMLMatch('<p>Test %1% content <span class="((prop:className))">((prop:viperKeyword))</span> more test content</p>');
            $this->assertRawHTMLMatch('<p>Test %1% content <span class="replaced-className" data-viper-attribute-keywords="true" data-viper-class="((prop:className))" data-viper-keyword="((prop:viperKeyword))" title="((prop:viperKeyword))">%5%</span> more test content</p>');

            $this->clickKeyword(5);
            $this->doAction($method, 'cssClass', 'active');
            $this->clearFieldValue('Class');
            $this->sikuli->KeyDown('Key.ENTER');
            $this->assertEquals('', $this->getFieldValue('Class'), 'Class field should be empty');
            $this->assertHTMLMatch('<p>Test %1% content ((prop:viperKeyword)) more test content</p>');
            $this->assertRawHTMLMatch('<p>Test %1% content <span data-viper-keyword="((prop:viperKeyword))" title="((prop:viperKeyword))">%5%</span> more test content</p>');
        }

    }//end testApplyAndRemoveClassKeywordToKeyword()


    /**
     * Test applying and removing a class keyword and normal class to a keyword.
     *
     * @return void
     */
    public function testApplyAndRemoveClassKeywordAndNormalClassToKeyword()
    {
        foreach ($this->getTestMethods(TRUE, TRUE, FALSE) as $method) {
            $this->useTest(2, 1);
            $this->clickKeyword(5);
            $this->doAction($method, 'cssClass');
            $this->type('test ((prop:className))');
            $this->sikuli->KeyDown('Key.ENTER');
            $this->assertEquals('test ((prop:className))', $this->getFieldValue('Class'), 'Class field should not be empty');
            $this->assertHTMLMatch('<p>Test %1% content <span class="test ((prop:className))">((prop:viperKeyword))</span> more test content</p>');
            $this->assertRawHTMLMatch('<p>Test %1% content <span class="test replaced-className" data-viper-attribute-keywords="true" data-viper-class="test ((prop:className))" data-viper-keyword="((prop:viperKeyword))" title="((prop:viperKeyword))">%5%</span> more test content</p>');

            $this->clickKeyword(5);
            $this->doAction($method, 'cssClass', 'active');
            $this->clearFieldValue('Class');
            $this->sikuli->KeyDown('Key.ENTER');
            $this->assertEquals('', $this->getFieldValue('Class'), 'Class field should be empty');
            $this->assertHTMLMatch('<p>Test %1% content ((prop:viperKeyword)) more test content</p>');
            $this->assertRawHTMLMatch('<p>Test %1% content <span data-viper-keyword="((prop:viperKeyword))" title="((prop:viperKeyword))">%5%</span> more test content</p>');
        }

    }//end testApplyAndRemoveClassKeywordAndNormalClassToKeyword()


    /**
     * Test applying and removing a class keyword and custom class to a keyword.
     *
     * @return void
     */
    public function testApplyAndRemoveClassKeywordAndCustomClassToKeyword()
    {
        $this->setCustomClassStyles();

        foreach ($this->getTestMethods(TRUE, TRUE, FALSE) as $method) {
            $this->useTest(2, 1);
            $this->clickKeyword(5);
            $this->doAction($method, 'cssClass');
            $this->type('((prop:className))');
            $this->selectStyles(array('ordered-list'));
            $this->sikuli->KeyDown('Key.ENTER');
            $this->assertHTMLMatch('<p>Test %1% content <span class="((prop:className)) ordered-list">((prop:viperKeyword))</span> more test content</p>');
            $this->assertRawHTMLMatch('<p>Test %1% content <span class="replaced-className ordered-list" data-viper-attribute-keywords="true" data-viper-class="((prop:className)) ordered-list" data-viper-keyword="((prop:viperKeyword))" title="((prop:viperKeyword))">%5%</span> more test content</p>');

            $this->clickKeyword(5);
            $this->doAction($method, 'cssClass', 'active');
            $selectedStyles = $this->getSelectedStyles();
            $this->removeStyles($selectedStyles);
            $this->clearFieldValue('Class');
            $this->sikuli->KeyDown('Key.ENTER');
            $this->assertEquals('', $this->getFieldValue('Class'), 'Class field should not be empty');
            $this->assertHTMLMatch('<p>Test %1% content ((prop:viperKeyword)) more test content</p>');
            $this->assertRawHTMLMatch('<p>Test %1% content <span data-viper-keyword="((prop:viperKeyword))" title="((prop:viperKeyword))">%5%</span> more test content</p>');
        }

    }//end testApplyAndRemoveClassKeywordAndNormalClassToKeyword()


    /**
     * Test that you can bold formating to content that has classes applied using keywords.
     *
     * @return void
     */
    public function testApplingBoldToContentUsingKeywordClassNames()
    {
        // Test using inline toolbar
        $this->useTest(3);
        $this->selectKeyword(1);
        $this->clickInlineToolbarButton('bold');
        $this->assertTrue($this->topToolbarButtonExists('cssClass', 'active'));
        $this->assertTrue($this->inlineToolbarButtonExists('cssClass', 'active'));
        $this->assertHTMLMatch('<p>Test content <strong><span class="((prop:className))">%1%</span></strong> more content %2%</p>');
        $this->assertRawHTMLMatch('<p>Test content <strong><span class="replaced-className" data-viper-class="((prop:className))">%1%</span></strong> more content %2%</p>');

        $this->clickInlineToolbarButton('bold', 'active');
        $this->assertTrue($this->topToolbarButtonExists('cssClass', 'active'));
        $this->assertTrue($this->inlineToolbarButtonExists('cssClass', 'active'));
        $this->assertHTMLMatch('<p>Test content <span class="((prop:className))">%1%</span> more content %2%</p>');
        $this->assertRawHTMLMatch('<p>Test content <span class="replaced-className" data-viper-class="((prop:className))">%1%</span> more content %2%</p>');

        // Test using top toolbar
        $this->clickTopToolbarButton('bold');
        $this->assertTrue($this->topToolbarButtonExists('cssClass', 'active'));
        $this->assertTrue($this->inlineToolbarButtonExists('cssClass', 'active'));
        $this->assertHTMLMatch('<p>Test content <strong><span class="((prop:className))">%1%</span></strong> more content %2%</p>');
        $this->assertRawHTMLMatch('<p>Test content <strong><span class="replaced-className" data-viper-class="((prop:className))">%1%</span></strong> more content %2%</p>');

        $this->clickTopToolbarButton('bold', 'active');
        $this->assertTrue($this->topToolbarButtonExists('cssClass', 'active'));
        $this->assertTrue($this->inlineToolbarButtonExists('cssClass', 'active'));
        $this->assertHTMLMatch('<p>Test content <span class="((prop:className))">%1%</span> more content %2%</p>');
        $this->assertRawHTMLMatch('<p>Test content <span class="replaced-className" data-viper-class="((prop:className))">%1%</span> more content %2%</p>');

        // Test using keyboard shortcuts
        $this->sikuli->keyDown('Key.CMD + b');
        $this->assertTrue($this->topToolbarButtonExists('cssClass', 'active'));
        $this->assertTrue($this->inlineToolbarButtonExists('cssClass', 'active'));
        $this->assertHTMLMatch('<p>Test content <strong><span class="((prop:className))">%1%</span></strong> more content %2%</p>');
        $this->assertRawHTMLMatch('<p>Test content <strong><span class="replaced-className" data-viper-class="((prop:className))">%1%</span></strong> more content %2%</p>');

        $this->sikuli->keyDown('Key.CMD + b');
        $this->assertTrue($this->topToolbarButtonExists('cssClass', 'active'));
        $this->assertTrue($this->inlineToolbarButtonExists('cssClass', 'active'));
        $this->assertHTMLMatch('<p>Test content <span class="((prop:className))">%1%</span> more content %2%</p>');
        $this->assertRawHTMLMatch('<p>Test content <span class="replaced-className" data-viper-class="((prop:className))">%1%</span> more content %2%</p>');

    }//end testApplingBoldToContentUsingKeywordClassNames()


    /**
     * Test that you can apply italics to content that has classes applied using keywords.
     *
     * @return void
     */
    public function testApplingItalicToContentUsingKeywordClassNames()
    {
        foreach ($this->getTestMethods() as $method) {
            $this->useTest(3);
            $this->selectKeyword(1);
            $this->doAction($method, 'italic');
            $this->assertTrue($this->topToolbarButtonExists('cssClass', 'active'));
            $this->assertTrue($this->inlineToolbarButtonExists('cssClass', 'active'));
            $this->assertHTMLMatch('<p>Test content <em><span class="((prop:className))">%1%</span></em> more content %2%</p>');
            $this->assertRawHTMLMatch('<p>Test content <em><span class="replaced-className" data-viper-class="((prop:className))">%1%</span></em> more content %2%</p>');

            $this->doAction($method, 'italic', 'active');
            $this->assertTrue($this->topToolbarButtonExists('cssClass', 'active'));
            $this->assertTrue($this->inlineToolbarButtonExists('cssClass', 'active'));
            $this->assertHTMLMatch('<p>Test content <span class="((prop:className))">%1%</span> more content %2%</p>');
            $this->assertRawHTMLMatch('<p>Test content <span class="replaced-className" data-viper-class="((prop:className))">%1%</span> more content %2%</p>');
        }

    }//end testApplingItalicToContentUsingKeywordClassNames()


    /**
     * Test that you can apply strikethrough to content that has classes applied using keywords.
     *
     * @return void
     */
    public function testApplingStrikethroughToContentUsingKeywordClassNames()
    {
        $this->useTest(3);
        $this->selectKeyword(1);
        $this->clickTopToolbarButton('strikethrough');
        $this->assertTrue($this->topToolbarButtonExists('cssClass', 'active'));
        $this->assertTrue($this->inlineToolbarButtonExists('cssClass', 'active'));
        $this->assertHTMLMatch('<p>Test content <del><span class="((prop:className))">%1%</span></del> more content %2%</p>');
        $this->assertRawHTMLMatch('<p>Test content <del><span class="replaced-className" data-viper-class="((prop:className))">%1%</span></del> more content %2%</p>');

        $this->clickTopToolbarButton('strikethrough', 'active');
        $this->assertTrue($this->topToolbarButtonExists('cssClass', 'active'));
        $this->assertTrue($this->inlineToolbarButtonExists('cssClass', 'active'));
        $this->assertHTMLMatch('<p>Test content <span class="((prop:className))">%1%</span> more content %2%</p>');
        $this->assertRawHTMLMatch('<p>Test content <span class="replaced-className" data-viper-class="((prop:className))">%1%</span> more content %2%</p>');

    }//end testApplingStrikethroughToContentUsingKeywordClassNames()


    /**
     * Test that you can apply subscript to content that has classes applied using keywords.
     *
     * @return void
     */
    public function testApplingSubscriptToContentUsingKeywordClassNames()
    {
        $this->useTest(3);
        $this->selectKeyword(1);
        $this->clickTopToolbarButton('subscript');
        $this->assertTrue($this->topToolbarButtonExists('cssClass', 'active'));
        $this->assertTrue($this->inlineToolbarButtonExists('cssClass', 'active'));
        $this->assertHTMLMatch('<p>Test content <sub><span class="((prop:className))">%1%</span></sub> more content %2%</p>');
        $this->assertRawHTMLMatch('<p>Test content <sub><span class="replaced-className" data-viper-class="((prop:className))">%1%</span></sub> more content %2%</p>');

        $this->clickTopToolbarButton('subscript', 'active');
        $this->assertTrue($this->topToolbarButtonExists('cssClass', 'active'));
        $this->assertTrue($this->inlineToolbarButtonExists('cssClass', 'active'));
        $this->assertHTMLMatch('<p>Test content <span class="((prop:className))">%1%</span> more content %2%</p>');
        $this->assertRawHTMLMatch('<p>Test content <span class="replaced-className" data-viper-class="((prop:className))">%1%</span> more content %2%</p>');

    }//end testApplingSubscriptToContentUsingKeywordClassNames()


    /**
     * Test that you can apply superscript to content that has classes applied using keywords.
     *
     * @return void
     */
    public function testApplingSuperscriptToContentUsingKeywordClassNames()
    {
        $this->useTest(3);
        $this->selectKeyword(1);
        $this->clickTopToolbarButton('superscript');
        $this->assertTrue($this->topToolbarButtonExists('cssClass', 'active'));
        $this->assertTrue($this->inlineToolbarButtonExists('cssClass', 'active'));
        $this->assertHTMLMatch('<p>Test content <sup><span class="((prop:className))">%1%</span></sup> more content %2%</p>');
        $this->assertRawHTMLMatch('<p>Test content <sup><span class="replaced-className" data-viper-class="((prop:className))">%1%</span></sup> more content %2%</p>');

        $this->clickTopToolbarButton('superscript', 'active');
        $this->assertTrue($this->topToolbarButtonExists('cssClass', 'active'));
        $this->assertTrue($this->inlineToolbarButtonExists('cssClass', 'active'));
        $this->assertHTMLMatch('<p>Test content <span class="((prop:className))">%1%</span> more content %2%</p>');
        $this->assertRawHTMLMatch('<p>Test content <span class="replaced-className" data-viper-class="((prop:className))">%1%</span> more content %2%</p>');

    }//end testApplingSuperscriptToContentUsingKeywordClassNames()


    /**
     * Test remove format with a keyword class
     *
     * @return void
     */
    public function testRemoveFormat()
    {
        $this->useTest(3);
        $this->selectKeyword(1);
        $this->clickTopToolbarButton('removeFormat');
        sleep(1);

        $this->assertHTMLMatch('<p>Test content %1% more content %2%</p>');
        $this->assertTrue($this->inlineToolbarButtonExists('cssClass'), 'The class icon should not be active');
        $this->assertTrue($this->topToolbarButtonExists('cssClass'), 'The class icon should not be active');

    }//end testRemoveFormat()

}
