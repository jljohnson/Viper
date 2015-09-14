	<?php

require_once 'AbstractViperUnitTest.php';

class Viper_Tests_ViperReplacementPlugin_CopyAndPasteKeywordUnitTest extends AbstractViperUnitTest
{

    /**
     * Test that keyword can copied and pasted.
     *
     * @return void
     */
    public function testCopyAndPasteKeyword()
    {
        // Beginning of paragraph using keyboard shortcuts
        $this->useTest(1);
        $this->moveToKeyword(1 , 'right');
        $this->sikuli->keyDown('Key.RIGHT');
        $this->sikuli->keyDown('Key.SHIFT + Key.RIGHT');
        sleep(1);
        $this->sikuli->keyDown('Key.CMD + c');
        $this->moveToKeyword(1, 'left');
        $this->sikuli->keyDown('Key.CMD + v');
        $this->assertHTMLMatch('<p>((prop:productName))%1% ((prop:productName))</p><p>%2% %3%</p><p>%4% ((prop:productName))</p><p>%5% %6%</p>');

        $expectedRawHTML = '<p><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span>%1% <span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></p><p>%2% %3%</p><p>%4% <span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></p><p>%5% %6%</p>';
        $actualRawHTML = $this->getRawHtml();
        $this->assertEquals($expectedRawHTML, $actualRawHTML);

        // Middle of paragraph using keyboard shortcuts
        $this->moveToKeyword(2, 'right');
        $this->sikuli->keyDown('Key.CMD + v');
        $this->assertHTMLMatch('<p>((prop:productName))%1% ((prop:productName))</p><p>%2%((prop:productName)) %3%</p><p>%4% ((prop:productName))</p><p>%5% %6%</p>');

        $expectedRawHTML = '<p><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span>%1% <span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></p><p>%2%<span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span> %3%</p><p>%4% <span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></p><p>%5% %6%</p>';
        $actualRawHTML = $this->getRawHtml();
        $this->assertEquals($expectedRawHTML, $actualRawHTML);

        // End of paragraph using keyboard shortcuts
        $this->moveToKeyword(3, 'right');
        $this->sikuli->keyDown('Key.CMD + v');
        $this->assertHTMLMatch('<p>((prop:productName))%1% ((prop:productName))</p><p>%2%((prop:productName)) %3%((prop:productName))</p><p>%4% ((prop:productName))</p><p>%5% %6%</p>');

        $expectedRawHTML = '<p><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span>%1% <span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></p><p>%2%<span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span> %3%<span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></p><p>%4% <span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></p><p>%5% %6%</p>';
        $actualRawHTML = $this->getRawHtml();
        $this->assertEquals($expectedRawHTML, $actualRawHTML);

        // Beginning of paragraph using right click
        $this->moveToKeyword(4 , 'right');
        $this->sikuli->keyDown('Key.RIGHT');
        $this->sikuli->keyDown('Key.SHIFT + Key.RIGHT');
        sleep(1);
        $this->copy(true);
        $this->moveToKeyword(4, 'left');
        $this->paste(true);
        $this->assertHTMLMatch('<p>((prop:productName))%1% ((prop:productName))</p><p>%2%((prop:productName)) %3%((prop:productName))</p><p>((prop:productName))%4% ((prop:productName))</p><p>%5% %6%</p>');

        $expectedRawHTML = '<p><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span>%1% <span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></p><p>%2%<span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span> %3%<span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></p><p><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span>%4% <span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></p><p>%5% %6%</p>';
        $actualRawHTML = $this->getRawHtml();
        $this->assertEquals($expectedRawHTML, $actualRawHTML);

        // Middle of paragraph using right click
        $this->moveToKeyword(5, 'right');
        $this->paste(true);
        $this->assertHTMLMatch('<p>((prop:productName))%1% ((prop:productName))</p><p>%2%((prop:productName)) %3%((prop:productName))</p><p>((prop:productName))%4% ((prop:productName))</p><p>%5%((prop:productName)) %6%</p>');

        $expectedRawHTML = '<p><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span>%1% <span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></p><p>%2%<span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span> %3%<span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></p><p><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span>%4% <span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></p><p>%5%<span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span> %6%</p>';
        $actualRawHTML = $this->getRawHtml();
        $this->assertEquals($expectedRawHTML, $actualRawHTML);

        // End of paragraph using right click
        $this->moveToKeyword(6, 'right');
        $this->paste(true);
        $this->assertHTMLMatch('<p>((prop:productName))%1% ((prop:productName))</p><p>%2%((prop:productName)) %3%((prop:productName))</p><p>((prop:productName))%4% ((prop:productName))</p><p>%5%((prop:productName)) %6%((prop:productName))</p>');

        $expectedRawHTML = '<p><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span>%1% <span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></p><p>%2%<span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span> %3%<span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></p><p><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span>%4% <span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></p><p>%5%<span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span> %6%<span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></p>';
        $actualRawHTML = $this->getRawHtml();
        $this->assertEquals($expectedRawHTML, $actualRawHTML);

    }//end testCopyAndPasteKeyword()


    /**
     * Test that classes using keyword names can be copied and pasted.
     *
     * @return void
     */
    public function testCopyAndPasteClassesWithKeywordNames()
    {
        $this->useTest(2);
        $this->selectKeyword(1);
        $this->sikuli->KeyDown('Key.CMD + c');
        $this->moveToKeyword(2, 'right');
        $this->sikuli->KeyDown('Key.CMD + v');
        $this->assertHTMLMatch('<p>Test content <span class="footnote-ref ((prop:className)) ((prop:className))">%1%</span> more test content %2%<span class="footnote-ref ((prop:className)) ((prop:className))">%1%</span></p>');

        $expectedRawHTML = '<p>Test content <span class="footnote-ref replaced-className replaced-className" data-viper-class="footnote-ref ((prop:className)) ((prop:className))">%1%</span> more test content %2%<span class="footnote-ref replaced-className replaced-className" data-viper-class="footnote-ref ((prop:className)) ((prop:className))">%1%</span></p>';
        $actualRawHTML = $this->getRawHtml();
        $this->assertEquals($expectedRawHTML, $actualRawHTML);

    }//end testCopyAndPasteClassesWithKeywordNames()


    /**
     * Test that keyword with a link can copied and pasted.
     *
     * @return void
     */
    public function testCopyAndPasteKeywordWithLink()
    {
        // Beginning of paragraph using keyboard shortcuts
        $this->useTest(3);
        $this->moveToKeyword(1 , 'right');
        $this->sikuli->keyDown('Key.RIGHT');
        $this->sikuli->keyDown('Key.SHIFT + Key.RIGHT');
        sleep(1);
        $this->sikuli->keyDown('Key.CMD + c');
        $this->moveToKeyword(1, 'left');
        $this->sikuli->keyDown('Key.CMD + v');
        $this->assertHTMLMatch('<p><a href="www.squizlabs.com.au">((prop:productName))</a>%1% <a href="www.squizlabs.com.au">((prop:productName))</a></p><p>%2% %3%</p><p>%4% <a href="www.squizlabs.com.au">((prop:productName))</a></p><p>%5% %6%</p>');

        $expectedRawHTML = '<p>%1% <a href="www.squizlabs.com.au"><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></a></p><p>%2% %3%</p><p>%4% <a href="www.squizlabs.com.au"><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></a></p><p>%5% %6%</p>';
        $actualRawHTML = $this->getRawHtml();
        $this->assertEquals($expectedRawHTML, $actualRawHTML);

        // Middle of paragraph using keyboard shortcuts
        $this->moveToKeyword(2, 'right');
        $this->sikuli->keyDown('Key.CMD + v');
        $this->assertHTMLMatch('<p><a href="www.squizlabs.com.au">((prop:productName))</a>%1% <a href="www.squizlabs.com.au">((prop:productName))</a></p><p>%2% %3%</p><p>%4% <a href="www.squizlabs.com.au">((prop:productName))</a></p><p>%5% %6%</p>');
       
        $expectedRawHTML = '<p><a href="www.squizlabs.com.au"><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></a>%1% <a href="www.squizlabs.com.au"><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></a></p><p>%2%<a href="www.squizlabs.com.au"><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></a> %3%</p><p>%4%<a href="www.squizlabs.com.au"><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></a></p><p>%5% %6%</p>';
        $actualRawHTML = $this->getRawHtml();
        $this->assertEquals($expectedRawHTML, $actualRawHTML);

        // End of paragraph using keyboard shortcuts
        $this->moveToKeyword(3, 'right');
        $this->sikuli->keyDown('Key.CMD + v');
        $this->assertHTMLMatch('<p><a href="www.squizlabs.com.au">((prop:productName))</a>%1% <a href="www.squizlabs.com.au">((prop:productName))</a></p><p>%2%<a href="www.squizlabs.com.au">((prop:productName))</a> %3%<a href="www.squizlabs.com.au">((prop:productName))</a></p><p>%4%<a href="www.squizlabs.com.au">((prop:productName))</a></p><p>%5% %6%</p>');

        $expectedRawHTML = '<p><a href="www.squizlabs.com.au"><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></a>%1% <a href="www.squizlabs.com.au"><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></a></p><p>%2%<a href="www.squizlabs.com.au"><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></a> %3%<a href="www.squizlabs.com.au"><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></a></p><p>%4%<a href="www.squizlabs.com.au"><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></a></p><p>%5% %6%</p>';
        $actualRawHTML = $this->getRawHtml();
        $this->assertEquals($expectedRawHTML, $actualRawHTML);

        // Beginning of paragraph using right click
        $this->moveToKeyword(4 , 'right');
        $this->sikuli->keyDown('Key.RIGHT');
        $this->sikuli->keyDown('Key.SHIFT + Key.RIGHT');
        sleep(1);
        $this->copy(true);
        $this->moveToKeyword(4, 'left');
        $this->paste(true);
        $this->assertHTMLMatch('<p><a href="www.squizlabs.com.au">((prop:productName))</a>%1% <a href="www.squizlabs.com.au">((prop:productName))</a></p><p>%2%<a href="www.squizlabs.com.au">((prop:productName))</a> %3%<a href="www.squizlabs.com.au">((prop:productName))</a></p><p><a href="www.squizlabs.com.au">((prop:productName))</a>%4%<a href="www.squizlabs.com.au">((prop:productName))</a></p><p>%5% %6%</p>');

        $expectedRawHTML = '<p><a href="www.squizlabs.com.au"><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></a>%1% <a href="www.squizlabs.com.au"><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></a></p><p>%2%<a href="www.squizlabs.com.au"><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></a> %3%<a href="www.squizlabs.com.au"><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></a></p><p><a href="www.squizlabs.com.au"><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></a>%4%<a href="www.squizlabs.com.au"><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></a></p><p>%5% %6%</p>';
        $actualRawHTML = $this->getRawHtml();
        $this->assertEquals($expectedRawHTML, $actualRawHTML);

        // Middle of paragraph using right click
        $this->moveToKeyword(5, 'right');
        $this->paste(true);
        $this->assertHTMLMatch('<p><a href="www.squizlabs.com.au">((prop:productName))</a>%1% <a href="www.squizlabs.com.au">((prop:productName))</a></p><p>%2%<a href="www.squizlabs.com.au">((prop:productName))</a> %3%<a href="www.squizlabs.com.au">((prop:productName))</a></p><p><a href="www.squizlabs.com.au">((prop:productName))</a>%4%<a href="www.squizlabs.com.au">((prop:productName))</a></p><p>%5%<a href="www.squizlabs.com.au">((prop:productName))</a> %6%</p>');

        $expectedRawHTML = '<p><a href="www.squizlabs.com.au"><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></a>%1% <a href="www.squizlabs.com.au"><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></a></p><p>%2%<a href="www.squizlabs.com.au"><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></a> %3%<a href="www.squizlabs.com.au"><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></a></p><p><a href="www.squizlabs.com.au"><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></a>%4%<a href="www.squizlabs.com.au"><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></a></p><p>%5%<a href="www.squizlabs.com.au"><span data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</span></a> %6%</p>';
        $actualRawHTML = $this->getRawHtml();
        $this->assertEquals($expectedRawHTML, $actualRawHTML);

        // End of paragraph using right click
        $this->moveToKeyword(6, 'right');
        $this->paste(true);
        $this->assertHTMLMatch('<p><a href="www.squizlabs.com.au">((prop:productName))</a>%1% <a href="www.squizlabs.com.au">((prop:productName))</a></p><p>%2%<a href="www.squizlabs.com.au">((prop:productName))</a> %3%<a href="www.squizlabs.com.au">((prop:productName))</a></p><p><a href="www.squizlabs.com.au">((prop:productName))</a>%4%<a href="www.squizlabs.com.au">((prop:productName))</a></p><p>%5%<a href="www.squizlabs.com.au">((prop:productName))</a> %6%<a href="www.squizlabs.com.au">((prop:productName))</a></p>');

        $expectedRawHTML = '<p><span <a href="www.squizlabs.com.au"><keyword contenteditable="false" data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</a></span>%1% <span <a href="www.squizlabs.com.au"><keyword contenteditable="false" data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</a></span></p><p>%2%<span <a href="www.squizlabs.com.au"><keyword contenteditable="false" data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</a></span> %3%<span <a href="www.squizlabs.com.au"><keyword contenteditable="false" data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</a></span></p><p><span <a href="www.squizlabs.com.au"><keyword contenteditable="false" data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</a></span>%4% <span <a href="www.squizlabs.com.au"><keyword contenteditable="false" data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</a></span></p><p>%5%<span <a href="www.squizlabs.com.au"><keyword contenteditable="false" data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</a></span> %6%<span <a href="www.squizlabs.com.au"><keyword contenteditable="false" data-viper-keyword="((prop:productName))" title="((prop:productName))">VIPER</a></span></p>';
        $actualRawHTML = $this->getRawHtml();
        $this->assertEquals($expectedRawHTML, $actualRawHTML);
    }//end testCopyAndPasteKeywordWithLink()
}