<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
class PolicyNoFromPDFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.policyNo-from-pdf.index');
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $file=$request->pdf_file;
        $pdfparser=new Parser();
        $pdf=$pdfparser->parseFile($file->path());

        $content=$pdf->getText();
        $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $content);

        $split = explode("Policy Number", $content);
        $a=array();

        // if(in_array("Policy", $split))
        // {
            array_push($a,$split);

        // }
        // 
        $pos= strpos($content,"Policy No");
        $policy_no=substr($content, $pos+12, 19);
        
        print_r($policy_no);   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
