<?php
/*
 * Purpose: let the user place a signature on monitor, even better on a tablet with a stylo.
 * After that, the signature will be placed in a pdf file (new or already existing) or in a html page as an image (.png)
 * 
 * TANKS TO:
 * 
 * oliver@fpdf.org (fpdf library)
 * Setasign - Jan Slabon (fpdi library)
 * jSignature (for the clientside)
 * 
 */
$config = parse_ini_file(__DIR__ . "/../../jsheetconfig.ini");
use setasign\Fpdi\Fpdi;
require_once('vendor/autoload.php');

class signature
{

    var $pdf;
    var $hash;
    var $image;
    var $x;
    var $y;
    var $p;


    function __construct()
    {
        /*require('fpdf/fpdf.php');
        require('fpdi/Fpdi.php');*/

        $this->make_hash();
        $this->make_signature();
    }

    // makes aan unique id for image creation
    function make_hash()
    {
        $this->hash = uniqid();
    }

    // creates the image (if dir is writable)
    function make_signature()
    {
        if (isset($_POST['img'])) {
            $arr = explode(',', $_POST['img']);
            $this->image = "image{$this->hash}.png";
            if (is_writable(dirname(__FILE__))) {
                file_put_contents($this->image, base64_decode($arr[1]));
            } else {
                die('<p>The working directory is not writable, abort.</p>');
            }
        }
    }

    // delete the image (if exists)
    function delete_signature()
    {
        if (file_exists($this->image)) @unlink($this->image);
    }

    // uses a template and places the signature
    function use_template_pdf($filename, $template, $detail)
    {
        $filePath = $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['config']['appRoot'] . '/resources/2/pdf/';
        // if template doesn't exists makes it
        if (!file_exists($filePath . $template)) {
            //	$this->make_template_pdf($template);
            die('<p>File not exist, abort.</p>');
        }
        $GLOBALS['x'] = json_decode($detail['x']);
        $GLOBALS['y'] = json_decode($detail['y']);
        $GLOBALS['p'] = json_decode($detail['p']);

        $this->pdf = new FPDI();
        $pagecount = $this->pdf->setSourceFile($filePath . $template);
        for ($pageNo = 1; $pageNo <= $pagecount; $pageNo++) {
            // import a page
            $templateId = $this->pdf->importPage($pageNo);
            // get the size of the imported page
            $size = $this->pdf->getTemplateSize($templateId);
            // create a page (landscape or portrait depending on the imported page size)
            if ($size['width'] > $size['height']) {
                $this->pdf->AddPage('L', array($size['width'], $size['height']));
            } else {
                $this->pdf->AddPage('P', array($size['width'], $size['height']));
            }
            $no = 0;

            if ($detail['same'] == 1) {
                for ($i = 0; $i < sizeof($GLOBALS['p']); $i++) {
                  //  if ($pageNo == $GLOBALS['p'][$i]) {
                        //  $tplidx = $this->pdf->importPage(0);
                        //	$this->pdf->addPage();
                        $this->pdf->useTemplate($templateId, 0, 0);

                        $this->pdf->Image($this->image, $GLOBALS['x'][0] - 36, $GLOBALS['y'][0] - 9, 72, 18);
                        //$this->pdf->Image($this->image,$GLOBALS['x'][$i],$GLOBALS['y'][$i],-200);
                        $no = 1;
                   // }
                }
            } else {
                if (sizeof($GLOBALS['p']) > 1) {
                    for ($i = 0; $i < sizeof($GLOBALS['p']); $i++) {
                        if ($pageNo == $GLOBALS['p'][$i]) {
                            //  $tplidx = $this->pdf->importPage(0);
                            //	$this->pdf->addPage();
                            $this->pdf->useTemplate($templateId, 0, 0);

                            $this->pdf->Image($this->image, $GLOBALS['x'][$i] - 36, $GLOBALS['y'][$i] - 9, 72, 18);
                            //$this->pdf->Image($this->image,$GLOBALS['x'][$i],$GLOBALS['y'][$i],-200);
                            $no = 1;
                        }
                    }
                    if ($no == 0) {
                        // use the imported page
                        $this->pdf->useTemplate($templateId);
                    }
                } else {
                    if ($pageNo == $GLOBALS['p'][0]) {
                        //  $tplidx = $this->pdf->importPage(0);
                        //	$this->pdf->addPage();
                        $this->pdf->useTemplate($templateId, 0, 0);

                        $this->pdf->Image($this->image, $GLOBALS['x'][0] - 32, $GLOBALS['y'][0] - 8, 72, 18);
                        $no = 1;
                    } else {
                        // use the imported page
                        $this->pdf->useTemplate($templateId);
                        /*          $this->pdf->SetFont('Helvetica');
                                    $this->pdf->SetXY(5, 5);
                                    $this->pdf->Write(8, 'A complete document imported with FPDI');        */
                    }
                }
            }
        }

        if (is_writable(dirname($filePath))) {
            $this->pdf->Output($filePath . $filename, 'F');
        } else {
            $this->pdf->Output($filePath . $filename, 'I');
        }
        $this->pdf = NULL;
        $this->delete_signature();

    }

    // uses a template and places the signature
    function getDetails($template)
    {
        $list = array();
        $filePath = $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['config']['appRoot'] . '/resources/2/pdf/';
        // if template doesn't exists makes it
        if (!file_exists($filePath . $template)) {
            //	$this->make_template_pdf($template);
            return $list;
        }
        $this->pdf = new FPDI();
        $pagecount = $this->pdf->setSourceFile($filePath . $template);
        if (1 <= $pagecount) {
            // import a page
            $templateId = $this->pdf->importPage(1);
            // get the size of the imported page
            $size = $this->pdf->getTemplateSize($templateId);
            $list['h'] = $size['height'];
            $list['w'] = $size['width'];
            $list['p'] = $pagecount;
        }
        $this->pdf = null;
        return $list;
    }
}

?>