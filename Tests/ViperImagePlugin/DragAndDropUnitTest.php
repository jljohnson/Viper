<?php

require_once 'AbstractViperImagePluginUnitTest.php';

class Viper_Tests_ViperImagePlugin_DragAndDropUnitTest extends AbstractViperImagePluginUnitTest
{

    /**
     * Test dragging an image into the content of the page.
     *
     * @return void
     */
    public function testDragAndDropImage()
    {
        $this->skipTestFor('windows', array('ie9', 'ie8', 'ie11', 'edge'));
        $this->useTest(1);

        $this->clickKeyword(1);
        sleep(1);
        $this->dragDropFromDesktop($this->findKeyword(1));
        sleep(1);

        $this->assertHTMLMatch('<p>X<img alt="DragDropImage" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsCAIAAAD2HxkiAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAArtJREFUeNrs1sENAiEQQFE10wcFOCXKiRYpQDrxYgkTNMt7BZAw7M/s/fl634DfeRgBiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAjUi6qD5mimyWmyL5sQ/I4CIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRgggBEYIIARGCCAERggiB7eIa18i+vOWx5mg2ISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQIYgQECGIEBAhiBAQIYgQECGIEBAhiBDYJoygxBzt5OtnX74BmxBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIfyiMoET2ZQjYhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhsEVc4xpzNG+JTQiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECCIERAgiBEQIIgRECCIERAgiBEQIIgQ2iaqDsi/TBJsQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIfH0EGAALzRF1eGaQ8QAAAABJRU5ErkJggg==" />AX Content to test drag and drop images</p><p>Another paragraph in the content XBX</p>');

        // Edit the image using the inline toolbar
        $this->clickElement('img', 0);
        $this->clickInlineToolbarButton('image', 'active');
        $this->clearFieldValue('Alt');
        sleep(1);
        $this->clickField('Alt', true);
        sleep(1);
        $this->type('Alt tag');
        $this->clickInlineToolbarButton('Apply Changes', NULL, TRUE);
        $this->assertHTMLMatch('<p>X<img alt="Alt tag" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsCAIAAAD2HxkiAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAArtJREFUeNrs1sENAiEQQFE10wcFOCXKiRYpQDrxYgkTNMt7BZAw7M/s/fl634DfeRgBiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAjUi6qD5mimyWmyL5sQ/I4CIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRgggBEYIIARGCCAERggiB7eIa18i+vOWx5mg2ISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQIYgQECGIEBAhiBAQIYgQECGIEBAhiBDYJoygxBzt5OtnX74BmxBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIfyiMoET2ZQjYhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhsEVc4xpzNG+JTQiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECCIERAgiBEQIIgRECCIERAgiBEQIIgQ2iaqDsi/TBJsQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIfH0EGAALzRF1eGaQ8QAAAABJRU5ErkJggg==" />AX Content to test drag and drop images</p><p>Another paragraph in the content XBX</p>');

        // Edit the image using the top toolbar
        $this->clickElement('img', 0);
        $this->clickTopToolbarButton('image', 'active');
        $this->clearFieldValue('Alt');
        sleep(1);
        $this->clickField('Alt', true);
        sleep(1);
        $this->type('test');
        $this->clickTopToolbarButton('Apply Changes', NULL, TRUE);
        $this->assertHTMLMatch('<p>X<img alt="test" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsCAIAAAD2HxkiAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAArtJREFUeNrs1sENAiEQQFE10wcFOCXKiRYpQDrxYgkTNMt7BZAw7M/s/fl634DfeRgBiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAjUi6qD5mimyWmyL5sQ/I4CIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRgggBEYIIARGCCAERggiB7eIa18i+vOWx5mg2ISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQIYgQECGIEBAhiBAQIYgQECGIEBAhiBDYJoygxBzt5OtnX74BmxBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIfyiMoET2ZQjYhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhsEVc4xpzNG+JTQiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECCIERAgiBEQIIgRECCIERAgiBEQIIgQ2iaqDsi/TBJsQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIfH0EGAALzRF1eGaQ8QAAAABJRU5ErkJggg==" />AX Content to test drag and drop images</p><p>Another paragraph in the content XBX</p>');

    }//end testDragAndDropImage()


    /**
     * Test dragging an image into the content of the page and editing other content as well.
     *
     * @return void
     */
    public function testDragAndDropImageAndEditContent()
    {
        $this->skipTestFor('windows', array('ie9', 'ie8', 'ie11', 'edge'));
        $this->useTest(1);

        $this->clickKeyword(1);
        sleep(1);
        $this->dragDropFromDesktop($this->findKeyword(1));
        sleep(1);

        $this->assertHTMLMatch('<p>X<img alt="DragDropImage" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsCAIAAAD2HxkiAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAArtJREFUeNrs1sENAiEQQFE10wcFOCXKiRYpQDrxYgkTNMt7BZAw7M/s/fl634DfeRgBiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAjUi6qD5mimyWmyL5sQ/I4CIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRgggBEYIIARGCCAERggiB7eIa18i+vOWx5mg2ISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQIYgQECGIEBAhiBAQIYgQECGIEBAhiBDYJoygxBzt5OtnX74BmxBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIfyiMoET2ZQjYhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhsEVc4xpzNG+JTQiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECCIERAgiBEQIIgRECCIERAgiBEQIIgQ2iaqDsi/TBJsQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIfH0EGAALzRF1eGaQ8QAAAABJRU5ErkJggg==" />AX Content to test drag and drop images</p><p>Another paragraph in the content XBX</p>');

        // Edit the image using the inline toolbar
        $this->clickElement('img', 0);
        $this->clickInlineToolbarButton('image', 'active');
        $this->clearFieldValue('Alt');
        sleep(1);
        $this->clickField('Alt', true);
        sleep(1);
        $this->type('Alt tag');
        $this->clickInlineToolbarButton('Apply Changes', NULL, TRUE);
        $this->assertHTMLMatch('<p>X<img alt="Alt tag" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsCAIAAAD2HxkiAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAArtJREFUeNrs1sENAiEQQFE10wcFOCXKiRYpQDrxYgkTNMt7BZAw7M/s/fl634DfeRgBiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAjUi6qD5mimyWmyL5sQ/I4CIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRgggBEYIIARGCCAERggiB7eIa18i+vOWx5mg2ISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQIYgQECGIEBAhiBAQIYgQECGIEBAhiBDYJoygxBzt5OtnX74BmxBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIfyiMoET2ZQjYhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhsEVc4xpzNG+JTQiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECCIERAgiBEQIIgRECCIERAgiBEQIIgQ2iaqDsi/TBJsQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIfH0EGAALzRF1eGaQ8QAAAABJRU5ErkJggg==" />AX Content to test drag and drop images</p><p>Another paragraph in the content XBX</p>');

        // Edit other content by using inline toolbar
        $this->selectKeyword(2);
        $this->clickInlineToolbarButton('bold');
        $this->assertHTMLMatch('<p>X<img alt="Alt tag" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsCAIAAAD2HxkiAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAArtJREFUeNrs1sENAiEQQFE10wcFOCXKiRYpQDrxYgkTNMt7BZAw7M/s/fl634DfeRgBiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAjUi6qD5mimyWmyL5sQ/I4CIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRgggBEYIIARGCCAERggiB7eIa18i+vOWx5mg2ISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQIYgQECGIEBAhiBAQIYgQECGIEBAhiBDYJoygxBzt5OtnX74BmxBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIfyiMoET2ZQjYhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhsEVc4xpzNG+JTQiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECCIERAgiBEQIIgRECCIERAgiBEQIIgQ2iaqDsi/TBJsQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIfH0EGAALzRF1eGaQ8QAAAABJRU5ErkJggg==" />AX Content to test drag and drop images</p><p>Another paragraph in the content <strong>XBX</strong></p>');

    }//end testDragAndDropImageAndEditContent()


    /**
     * Test dragging multiple images into the content of the page.
     *
     * @return void
     */
    public function testDragAndDropMultipleImages()
    {
        $this->skipTestFor('windows', array('ie9', 'ie8', 'ie11', 'edge'));
        $this->useTest(1);

        // Drag and drop first image
        $this->clickKeyword(1);
        sleep(1);
        $this->dragDropFromDesktop($this->findKeyword(1));
        sleep(1);
        $this->assertHTMLMatch('<p>X<img alt="DragDropImage" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsCAIAAAD2HxkiAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAArtJREFUeNrs1sENAiEQQFE10wcFOCXKiRYpQDrxYgkTNMt7BZAw7M/s/fl634DfeRgBiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAjUi6qD5mimyWmyL5sQ/I4CIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRgggBEYIIARGCCAERggiB7eIa18i+vOWx5mg2ISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQIYgQECGIEBAhiBAQIYgQECGIEBAhiBDYJoygxBzt5OtnX74BmxBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIfyiMoET2ZQjYhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhsEVc4xpzNG+JTQiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECCIERAgiBEQIIgRECCIERAgiBEQIIgQ2iaqDsi/TBJsQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIfH0EGAALzRF1eGaQ8QAAAABJRU5ErkJggg==" />AX Content to test drag and drop images</p><p>Another paragraph in the content XBX</p>');

        // Edit the image so we know the second image is different
        $this->clickElement('img', 0);
        $this->clickInlineToolbarButton('image', 'active');
        $this->clearFieldValue('Alt');
        sleep(1);
        $this->clickField('Alt', true);
        sleep(1);
        $this->type('Alt tag');
        $this->clickInlineToolbarButton('Apply Changes', NULL, TRUE);
        $this->assertHTMLMatch('<p>X<img alt="Alt tag" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsCAIAAAD2HxkiAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAArtJREFUeNrs1sENAiEQQFE10wcFOCXKiRYpQDrxYgkTNMt7BZAw7M/s/fl634DfeRgBiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAjUi6qD5mimyWmyL5sQ/I4CIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRgggBEYIIARGCCAERggiB7eIa18i+vOWx5mg2ISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQIYgQECGIEBAhiBAQIYgQECGIEBAhiBDYJoygxBzt5OtnX74BmxBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIfyiMoET2ZQjYhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhsEVc4xpzNG+JTQiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECCIERAgiBEQIIgRECCIERAgiBEQIIgQ2iaqDsi/TBJsQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIfH0EGAALzRF1eGaQ8QAAAABJRU5ErkJggg==" />AX Content to test drag and drop images</p><p>Another paragraph in the content XBX</p>');

        // Drag and drop second image
        $this->clickKeyword(2);
        sleep(1);
        $this->dragDropFromDesktop($this->findKeyword(2));
        sleep(1);
        $this->assertHTMLMatch('<p>X<img alt="Alt tag" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsCAIAAAD2HxkiAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAArtJREFUeNrs1sENAiEQQFE10wcFOCXKiRYpQDrxYgkTNMt7BZAw7M/s/fl634DfeRgBiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAjUi6qD5mimyWmyL5sQ/I4CIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRgggBEYIIARGCCAERggiB7eIa18i+vOWx5mg2ISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQIYgQECGIEBAhiBAQIYgQECGIEBAhiBDYJoygxBzt5OtnX74BmxBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIfyiMoET2ZQjYhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhsEVc4xpzNG+JTQiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECCIERAgiBEQIIgRECCIERAgiBEQIIgQ2iaqDsi/TBJsQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIfH0EGAALzRF1eGaQ8QAAAABJRU5ErkJggg==" />AX Content to test drag and drop images</p><p>Another paragraph in the content X<img alt="DragDropImage" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsCAIAAAD2HxkiAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAArtJREFUeNrs1sENAiEQQFE10wcFOCXKiRYpQDrxYgkTNMt7BZAw7M/s/fl634DfeRgBiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAjUi6qD5mimyWmyL5sQ/I4CIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRgggBEYIIARGCCAERggiB7eIa18i+vOWx5mg2ISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQIYgQECGIEBAhiBAQIYgQECGIEBAhiBDYJoygxBzt5OtnX74BmxBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIfyiMoET2ZQjYhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhsEVc4xpzNG+JTQiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECCIERAgiBEQIIgRECCIERAgiBEQIIgQ2iaqDsi/TBJsQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIfH0EGAALzRF1eGaQ8QAAAABJRU5ErkJggg==" />BX</p>');

    }//end testDragAndDropMultipleImages()


    /**
     * Test dragging an image onto a page with no content.
     *
     * @return void
     */
    public function testDragAndDropImageOnEmptyPage()
    {
        $this->skipTestFor('windows', array('ie9', 'ie8', 'ie11', 'edge'));
        $this->useTest(1);

        $this->clickKeyword(1);
        $dragLocation = $this->findKeyword(1);
        $this->sikuli->keyDown('Key.CMD + a');
        $this->sikuli->keyDown('Key.DELETE');
        sleep(1);
        $this->dragDropFromDesktop($dragLocation);

        $this->assertHTMLMatch('<p><img alt="DragDropImage" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsCAIAAAD2HxkiAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAArtJREFUeNrs1sENAiEQQFE10wcFOCXKiRYpQDrxYgkTNMt7BZAw7M/s/fl634DfeRgBiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAjUi6qD5mimyWmyL5sQ/I4CIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRgggBEYIIARGCCAERggiB7eIa18i+vOWx5mg2ISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQIYgQECGIEBAhiBAQIYgQECGIEBAhiBDYJoygxBzt5OtnX74BmxBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIfyiMoET2ZQjYhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhsEVc4xpzNG+JTQiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECCIERAgiBEQIIgRECCIERAgiBEQIIgQ2iaqDsi/TBJsQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIfH0EGAALzRF1eGaQ8QAAAABJRU5ErkJggg==" /></p>');

        // Edit the image using the inline toolbar
        $this->clickElement('img', 0);
        sleep(1);
        $this->clickInlineToolbarButton('image', 'active');
        $this->clearFieldValue('Alt');
        sleep(1);
        $this->clickField('Alt', true);
        sleep(1);
        $this->type('Alt tag');
        $this->clickInlineToolbarButton('Apply Changes', NULL, TRUE);
        $this->assertHTMLMatch('<p><img alt="Alt tag" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsCAIAAAD2HxkiAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAArtJREFUeNrs1sENAiEQQFE10wcFOCXKiRYpQDrxYgkTNMt7BZAw7M/s/fl634DfeRgBiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAjUi6qD5mimyWmyL5sQ/I4CIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRgggBEYIIARGCCAERggiB7eIa18i+vOWx5mg2ISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQIYgQECGIEBAhiBAQIYgQECGIEBAhiBDYJoygxBzt5OtnX74BmxBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIfyiMoET2ZQjYhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhsEVc4xpzNG+JTQiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECCIERAgiBEQIIgRECCIERAgiBEQIIgQ2iaqDsi/TBJsQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIfH0EGAALzRF1eGaQ8QAAAABJRU5ErkJggg==" /></p>');

    }//end testDragAndDropImageOnEmptyPage()


    /**
     * Test dragging an image onto an existing image inserts a new image.
     *
     * @return void
     */
    public function testDragAndDropOntoExistingImage()
    {
        $this->skipTestFor('windows', array('ie9', 'ie8', 'ie11', 'edge'));
        $this->useTest(2);

        $this->clickKeyword(1);
        sleep(1);
        $this->dragDropFromDesktop($this->sikuli->getElementRegion('img', 0));

        $this->assertHTMLMatch('<p>Content with an image XAX</p><p><img alt="DragDropImage" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsCAIAAAD2HxkiAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAArtJREFUeNrs1sENAiEQQFE10wcFOCXKiRYpQDrxYgkTNMt7BZAw7M/s/fl634DfeRgBiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAjUi6qD5mimyWmyL5sQ/I4CIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRgggBEYIIARGCCAERggiB7eIa18i+vOWx5mg2ISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQIYgQECGIEBAhiBAQIYgQECGIEBAhiBDYJoygxBzt5OtnX74BmxBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIfyiMoET2ZQjYhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhsEVc4xpzNG+JTQiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECCIERAgiBEQIIgRECCIERAgiBEQIIgQ2iaqDsi/TBJsQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIfH0EGAALzRF1eGaQ8QAAAABJRU5ErkJggg==" /><img src="%url%/ViperImagePlugin/Images/editing.png" alt=""/></p><p>LABS is ORSM</p>');

        // Edit the image using the inline toolbar
        $this->clickElement('img', 0);
        sleep(1);
        $this->clickInlineToolbarButton('image', 'active');
        $this->clearFieldValue('Alt');
        sleep(1);
        $this->clickField('Alt', true);
        sleep(1);
        $this->type('Alt tag');
        $this->clickInlineToolbarButton('Apply Changes', NULL, TRUE);
        $this->assertHTMLMatch('<p>Content with an image XAX</p><p><img alt="Alt tag" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsCAIAAAD2HxkiAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAArtJREFUeNrs1sENAiEQQFE10wcFOCXKiRYpQDrxYgkTNMt7BZAw7M/s/fl634DfeRgBiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAjUi6qD5mimyWmyL5sQ/I4CIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRgggBEYIIARGCCAERggiB7eIa18i+vOWx5mg2ISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQIYgQECGIEBAhiBAQIYgQECGIEBAhiBDYJoygxBzt5OtnX74BmxBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIfyiMoET2ZQjYhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhsEVc4xpzNG+JTQiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECCIERAgiBEQIIgRECCIERAgiBEQIIgQ2iaqDsi/TBJsQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIfH0EGAALzRF1eGaQ8QAAAABJRU5ErkJggg==" /><img src="%url%/ViperImagePlugin/Images/editing.png" alt=""/></p><p>LABS is ORSM</p>');

    }//end testDragAndDropOntoExistingImage()


    /**
     * Test dragging an image into the content of the page and pressing undo.
     *
     * @return void
     */
    public function testUndoDragAndDropImage()
    {
        $this->skipTestFor('windows', array('ie9', 'ie8', 'ie11', 'edge'));
        
        // Test undo when dragging in a new image
        $this->useTest(1);
        $this->clickKeyword(1);
        sleep(1);
        $this->dragDropFromDesktop($this->findKeyword(1));
        sleep(1);
        $this->assertHTMLMatch('<p>X<img alt="DragDropImage" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsCAIAAAD2HxkiAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAArtJREFUeNrs1sENAiEQQFE10wcFOCXKiRYpQDrxYgkTNMt7BZAw7M/s/fl634DfeRgBiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAjUi6qD5mimyWmyL5sQ/I4CIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRgggBEYIIARGCCAERggiB7eIa18i+vOWx5mg2ISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQIYgQECGIEBAhiBAQIYgQECGIEBAhiBDYJoygxBzt5OtnX74BmxBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIfyiMoET2ZQjYhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhsEVc4xpzNG+JTQiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECCIERAgiBEQIIgRECCIERAgiBEQIIgQ2iaqDsi/TBJsQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIfH0EGAALzRF1eGaQ8QAAAABJRU5ErkJggg==" />AX Content to test drag and drop images</p><p>Another paragraph in the content XBX</p>');

        // Press undo
        $this->clickTopToolbarButton('historyUndo');
        $this->assertHTMLMatch('<p>%1% Content to test drag and drop images</p><p>Another paragraph in the content %2%</p>');

        // Press redo
        $this->clickTopToolbarButton('historyRedo');
        $this->assertHTMLMatch('<p>X<img alt="DragDropImage" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsCAIAAAD2HxkiAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAArtJREFUeNrs1sENAiEQQFE10wcFOCXKiRYpQDrxYgkTNMt7BZAw7M/s/fl634DfeRgBiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAjUi6qD5mimyWmyL5sQ/I4CIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRgggBEYIIARGCCAERggiB7eIa18i+vOWx5mg2ISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQIYgQECGIEBAhiBAQIYgQECGIEBAhiBDYJoygxBzt5OtnX74BmxBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIfyiMoET2ZQjYhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhsEVc4xpzNG+JTQiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECCIERAgiBEQIIgRECCIERAgiBEQIIgQ2iaqDsi/TBJsQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIfH0EGAALzRF1eGaQ8QAAAABJRU5ErkJggg==" />AX Content to test drag and drop images</p><p>Another paragraph in the content XBX</p>');

        // Test undo when replacing an existing image
        $this->useTest(2);
        $this->clickKeyword(1);
        sleep(1);
        $this->dragDropFromDesktop($this->sikuli->getElementRegion('img', 0));
        $this->assertHTMLMatch('<p>Content with an image XAX</p><p><img alt="DragDropImage" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsCAIAAAD2HxkiAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAArtJREFUeNrs1sENAiEQQFE10wcFOCXKiRYpQDrxYgkTNMt7BZAw7M/s/fl634DfeRgBiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAjUi6qD5mimyWmyL5sQ/I4CIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRgggBEYIIARGCCAERggiB7eIa18i+vOWx5mg2ISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQIYgQECGIEBAhiBAQIYgQECGIEBAhiBDYJoygxBzt5OtnX74BmxBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIfyiMoET2ZQjYhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhsEVc4xpzNG+JTQiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECCIERAgiBEQIIgRECCIERAgiBEQIIgQ2iaqDsi/TBJsQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIfH0EGAALzRF1eGaQ8QAAAABJRU5ErkJggg==" /><img src="%url%/ViperImagePlugin/Images/editing.png" alt=""/></p><p>LABS is ORSM</p>');

        // Press undo
        $this->clickTopToolbarButton('historyUndo');
        $this->assertHTMLMatch('<p>Content with an image %1%</p><p><img src="%url%/ViperImagePlugin/Images/editing.png" alt=""/></p><p>LABS is ORSM</p>');

        // Press redo
        $this->clickTopToolbarButton('historyRedo');
        $this->assertHTMLMatch('<p>Content with an image XAX</p><p><img alt="DragDropImage" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsCAIAAAD2HxkiAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAArtJREFUeNrs1sENAiEQQFE10wcFOCXKiRYpQDrxYgkTNMt7BZAw7M/s/fl634DfeRgBiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAjUi6qD5mimyWmyL5sQ/I4CIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRgggBEYIIARGCCAERggiB7eIa18i+vOWx5mg2ISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhIEIQIYgQECGIEBAhiBAQIYgQECGIEBAhiBDYJoygxBzt5OtnX74BmxBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIIgRECCIERAgiBEQIfyiMoET2ZQjYhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQkCEIEJAhCBCQIQgQhAhIEIQISBCECEgQhAhIEIQISBCECEgQhAhsEVc4xpzNG+JTQiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECCIERAgiBEQIIgRECCIERAgiBEQIIgQ2iaqDsi/TBJsQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIiBBECIgQRAiIEEQIfH0EGAALzRF1eGaQ8QAAAABJRU5ErkJggg==" /><img src="%url%/ViperImagePlugin/Images/editing.png" alt=""/></p><p>LABS is ORSM</p>');

    }//end testUndoDragAndDropImage()

}//end class

?>
