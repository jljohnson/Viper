<?php

require_once 'AbstractViperUnitTest.php';

class Viper_Tests_ViperCopyPastePlugin_CopyPasteFormatsUnitTest extends AbstractViperUnitTest
{

    /**
     * Test that you can copy and paste inside different formats.
     *
     * @return void
     */
    public function testCopyAndPasteInsideFormats()
    {
        // Test copy and paste inside a pre section
        $this->useTest(1);
        $this->selectKeyword(1, 2);
        $this->sikuli->keyDown('Key.CMD + c');
        $this->moveToKeyword(2, 'right');
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);
        $this->assertHTMLMatch('<p>First paragraph</p><pre>Lorum this is more content %1% to test %2%%1% to test %2%</pre>');

        // Test copy and paste inside a div section
        $this->useTest(2);
        $this->selectKeyword(1, 2);
        $this->sikuli->keyDown('Key.CMD + c');
        $this->moveToKeyword(2, 'right');
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);
        $this->assertHTMLMatch('<div>Lorum this is more content %1% to test %2%%1% to test %2%</div>');

        // Test copy and paste inside a quoote section
        $this->useTest(3);
        $this->selectKeyword(1, 2);
        $this->sikuli->keyDown('Key.CMD + c');
        $this->moveToKeyword(2, 'right');
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);
        $this->assertHTMLMatch('<p>First paragraph</p><blockquote><p>Lorum this is more content %1% to test %2%%1% to test %2%</p></blockquote>');

        // Test copy and paste inside a p section
        $this->useTest(4);
        $this->selectKeyword(1, 2);
        $this->sikuli->keyDown('Key.CMD + c');
        $this->moveToKeyword(2, 'right');
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);
        $this->assertHTMLMatch('<p>Lorum this is more content %1% to test %2%%1% to test %2%</p>');

    }//end testCopyAndPasteInsideFormats()


    /**
     * Test copy and paste a section of different formats.
     *
     * @return void
     */
    public function testCopyAndPasteASectionOfAFormat()
    {
        // Test copyd and paste a pre section
        $this->useTest(1);
        $this->selectKeyword(1, 2);
        $this->sikuli->keyDown('Key.CMD + c');
        $this->moveToKeyword(2, 'right');
        $this->sikuli->keyDown('Key.ENTER');
        sleep(2);
        $this->sikuli->keyDown('Key.ENTER');
        sleep(2);
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(2);
        $this->assertHTMLMatch('<p>First paragraph</p><pre>Lorum this is more content %1% to test %2%</pre><p>%1% to test %2%</p>');

        // Test copy and paste a div section
        $this->useTest(2);
        $this->selectKeyword(1, 2);
        $this->sikuli->keyDown('Key.CMD + c');
        $this->moveToKeyword(2, 'right');
        $this->sikuli->keyDown('Key.ENTER');
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(2);
        $this->assertHTMLMatch('<div>Lorum this is more content %1% to test %2%</div><p>%1% to test %2%</p>');

        // Test copy and paste a quote section
        $this->useTest(3);
        $this->selectKeyword(1, 2);
        $this->sikuli->keyDown('Key.CMD + c');
        $this->moveToKeyword(2, 'right');
        $this->sikuli->keyDown('Key.ENTER');
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(2);
        $this->assertHTMLMatch('<p>First paragraph</p><blockquote><p>Lorum this is more content %1% to test %2%</p><p>%1% to test %2%</p></blockquote>');

        // Test copy and paste a paragraph section
        $this->useTest(4);
        $this->selectKeyword(1, 2);
        $this->sikuli->keyDown('Key.CMD + c');
        $this->moveToKeyword(2, 'right');
        $this->sikuli->keyDown('Key.ENTER');
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(2);
        $this->assertHTMLMatch('<p>Lorum this is more content %1% to test %2%</p><p>%1% to test %2%</p>');

    }//end testCopyAndPasteASectionOfAFormat()


    /**
     * Test copy and paste a format section.
     *
     * @return void
     */
    public function testCopyAndPasteFormats()
    {
        // Test copyd and paste a pre
        $this->useTest(1);
        $this->selectKeyword(1);
        $this->selectInlineToolbarLineageItem(0);
        $this->sikuli->keyDown('Key.CMD + c');
        $this->moveToKeyword(2, 'right');
        $this->sikuli->keyDown('Key.ENTER');
        $this->sikuli->keyDown('Key.ENTER');
        sleep(1);
        $this->sikuli->keyDown('Key.CMD + v');
        $this->assertHTMLMatch('<p>First paragraph</p><pre>Lorum this is more content %1% to test %2%</pre><pre>Lorum this is more content %1% to test %2%</pre>');

        // Test copy and paste a div section
        $this->useTest(2);
        $this->selectKeyword(1);
        $this->selectInlineToolbarLineageItem(0);
        $this->sikuli->keyDown('Key.CMD + c');
        $this->moveToKeyword(2, 'right');
        $this->sikuli->keyDown('Key.ENTER');
        $this->sikuli->keyDown('Key.CMD + v');
        $this->assertHTMLMatch('<div>Lorum this is more content %1% to test %2%</div><div>Lorum this is more content %1% to test %2%</div>');

        // Test copy and paste a quote section
        $this->useTest(3);
        $this->selectKeyword(1);
        $this->selectInlineToolbarLineageItem(0);
        $this->sikuli->keyDown('Key.CMD + c');
        $this->moveToKeyword(2, 'right');
        $this->sikuli->keyDown('Key.ENTER');
        $this->sikuli->keyDown('Key.CMD + v');
        $this->assertHTMLMatch('<p>First paragraph</p><blockquote><p>Lorum this is more content %1% to test %2%</p></blockquote><blockquote><p>Lorum this is more content %1% to test %2%</p></blockquote>');

        // Test copy and paste a paragraph section
        $this->useTest(4);
        $this->selectKeyword(1);
        $this->selectInlineToolbarLineageItem(0);
        $this->sikuli->keyDown('Key.CMD + c');
        $this->moveToKeyword(2, 'right');
        $this->sikuli->keyDown('Key.ENTER');
        $this->sikuli->keyDown('Key.CMD + v');
        $this->assertHTMLMatch('<p>Lorum this is more content %1% to test %2%</p><p>Lorum this is more content %1% to test %2%</p>');

    }//end testCopyAndPasteASectionOfAFormats()


    /**
     * Test copy and paste a Pre section inside a pre section.
     *
     * @return void
     */
    public function testCopyAndPastePreFormat()
    {
        $this->useTest(1);

        $this->selectKeyword(1);
        $this->selectInlineToolbarLineageItem(0);
        $this->sikuli->keyDown('Key.CMD + c');
        $this->moveToKeyword(2, 'right');
        $this->sikuli->keyDown('Key.ENTER');
        sleep(1);
        $this->type('First paste ');
        $this->sikuli->keyDown('Key.CMD + v');
        $this->assertHTMLMatch('<p>First paragraph</p><pre>Lorum this is more content %1% to test %2% First paste &lt;pre&gt;Lorum this is more content %1% to test %2%&lt;/pre&gt;</pre>');

        // Paste again to make sure the character is still there.
        $this->sikuli->keyDown('Key.ENTER');
        sleep(1);
        $this->type('Second paste ');
        $this->sikuli->keyDown('Key.CMD + v');
        $this->assertHTMLMatch('<p>First paragraph</p><pre>Lorum this is more content XAX to test XBX First paste &lt;pre&gt;Lorum this is more content XAX to test XBX&lt;/pre&gt; Second paste &lt;pre&gt;Lorum this is more content XAX to test XBX&lt;/pre&gt;   </pre>');

        // Paste again in a new pre section
        $this->sikuli->keyDown('Key.ENTER');
        $this->sikuli->keyDown('Key.ENTER');
        sleep(1);
        $this->sikuli->keyDown('Key.CMD + v');
        $this->assertHTMLMatch('<p>First paragraph</p><pre>Lorum this is more content XAX to test XBX First paste &lt;pre&gt;Lorum this is more content XAX to test XBX&lt;/pre&gt; Second paste &lt;pre&gt;Lorum this is more content XAX to test XBX&lt;/pre&gt;</pre><pre>Lorum this is more content XAX to test XBX</pre>');

    }//end testCopyAndPastePreFormat()


    /**
     * Test pasting HTML into a Pre section.
     *
     * @return void
     */
    public function testPastingHtmlIntoPre()
    {
        $this->useTest(1);

        $this->selectKeyword(1);
        $this->pasteFromURL($this->getTestURL('/ViperCopyPastePlugin/CopyPasteFiles/HtmlCode.txt'));
        $this->assertHTMLMatch('<p>First paragraph</p><pre>Lorum this is more content &lt;strong&gt;strong tags&lt;/strong&gt; &lt;ul&gt;&lt;li&gt;List item&lt;/li&gt;&lt;li&gt;List item&lt;/li&gt;&lt;/ul&gt;  to test XBX</pre>');
        
    }//end testPastingHtmlIntoPre()


    /**
     * Test copying and pasting different block elements.
     *
     * @return void
     */
    public function testCopyPasteBlockElements()
    {
        // Test paragraph
        $this->useTest(5);
        $this->selectKeyword(1);
        $this->selectInlineToolbarLineageItem(0);
        $this->sikuli->keyDown('Key.CMD + c');
        sleep(1);
        $this->moveToKeyword(4, 'right');
        $this->sikuli->keyDown('Key.ENTER');
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);
        $this->assertHTMLMatch('<p>This is a paragraph section %1%</p><div>This is a div section %2%</div><pre>This is a pre section %3%</pre><blockquote><p>This is a quote section %4%</p></blockquote><p>This is a paragraph section %1%</p>');

        // Test div
        $this->useTest(5);
        $this->selectKeyword(2);
        $this->selectInlineToolbarLineageItem(0);
        $this->sikuli->keyDown('Key.CMD + c');
        sleep(1);
        $this->moveToKeyword(4, 'right');
        $this->sikuli->keyDown('Key.ENTER');
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);
        $this->assertHTMLMatch('<p>This is a paragraph section %1%</p><div>This is a div section %2%</div><pre>This is a pre section %3%</pre><blockquote><p>This is a quote section %4%</p></blockquote><div>This is a div section %2%</div>');

        // Test pre
        $this->useTest(5);
        $this->selectKeyword(3);
        $this->selectInlineToolbarLineageItem(0);
        $this->sikuli->keyDown('Key.CMD + c');
        sleep(1);
        $this->moveToKeyword(4, 'right');
        $this->sikuli->keyDown('Key.ENTER');
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);
        $this->assertHTMLMatch('<p>This is a paragraph section %1%</p><div>This is a div section %2%</div><pre>This is a pre section %3%</pre><blockquote><p>This is a quote section %4%</p></blockquote><pre>This is a pre section %3%</pre>');

        // Test quote
        $this->useTest(5);
        $this->selectKeyword(4);
        $this->selectInlineToolbarLineageItem(0);
        $this->sikuli->keyDown('Key.CMD + c');
        sleep(1);
        $this->moveToKeyword(1, 'right');
        $this->sikuli->keyDown('Key.ENTER');
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);
        $this->assertHTMLMatch('<p>This is a paragraph section %1%</p><blockquote><p>This is a quote section %4%</p></blockquote><div>This is a div section %2%</div><pre>This is a pre section %3%</pre><blockquote><p>This is a quote section %4%</p></blockquote>');

    }//end testCopyPasteBlockElements()


    /**
     * Test copy and pasting a heading.
     *
     * @return void
     */
    public function testCopyPasteHeading()
    {
        $this->useTest(6);

        $this->selectKeyword(1);
        $this->selectInlineToolbarLineageItem(0);
        $this->sikuli->keyDown('Key.CMD + c');
        sleep(1);
        $this->moveToKeyword(2, 'right');
        $this->sikuli->keyDown('Key.ENTER');
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);
        $this->assertHTMLMatch('<h1>Heading One %1%</h1><p>This is a paragraph %2%</p><h1>Heading One %1%</h1><h2>Heading Two %3%</h2><p>This is another paragraph %4%</p>');

        $this->selectKeyword(3);
        $this->selectInlineToolbarLineageItem(0);
        $this->sikuli->keyDown('Key.CMD + c');
        sleep(1);
        $this->moveToKeyword(4, 'right');
        $this->sikuli->keyDown('Key.ENTER');
        $this->sikuli->keyDown('Key.CMD + v');
        sleep(1);
        $this->assertHTMLMatch('<h1>Heading One %1%</h1><p>This is a paragraph %2%</p><h1>Heading One %1%</h1><h2>Heading Two %3%</h2><p>This is another paragraph %4%</p><h2>Heading Two %3%</h2>');

    }//end testCopyPasteHeading()


}//end class

?>
