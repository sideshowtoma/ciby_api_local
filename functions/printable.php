<?php


    

    function vince_mc_carry_me_loot_before_i_res_247($business_unit_name,$client_group_name,$message_is,$supplier_or_client_boolean=true,$supplier_or_client_id,$supplier_or_client_name,$payment_id,$invoice_voucher_id,$documented_payment_date,$amount,$balance,$description,$payment_mode)
    {
    //include configuration file
    //require_once "../core/config.php";

    //create configuration object
    //$config = new Config();
    //$school = $config->getSchoolInformation();

        //die(json_encode($message_is));
   
    $margins = 2.0;
    $font_family = "Courier";

    $pdf = new FPDF();
    $pdf->SetMargins($margins,$margins,$margins);
    
    $size_is=160;
    $how_many=0;
    if($supplier_or_client_boolean==true)
    {
      
       
        
        foreach ($message_is as $value) 
        {
            $invoices=$value['invoices'];
            $how_many+=count($invoices);
        }
    }
    else
    {
         foreach ($message_is as $value) 
        {
            $invoices=$value['vouchers'];
            $how_many+=count($invoices);
           
        }
    }
    
    if($size_is>1)
    {
        $size_is+=$how_many*10;
    }
    
    
    $pdf->AddPage('P',[80,$size_is],0);
    //$pdf->a
    $pdf->SetAutoPageBreak(FALSE,1);
    $pdf->SetFont('Arial','',14);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetAuthor("Sternum bursar");
    $pdf->SetCreator("Sternum bursar, Bursar");
    $pdf->SetTitle("Transaction Receipt");
    
    $pdf->SetFont($font_family,'',18);

    $pdf->SetX(($pdf->GetPageWidth()-30)/2);

    $pdf->Cell(30,10,"Receipt",0,1,'C');

    //school info
    $pdf->SetX(($pdf->GetPageWidth()-70)/2);

    $pdf->SetFont($font_family,'B',14);
    $pdf->MultiCell(70,5, strtoupper($business_unit_name)."\n".$client_group_name,0,'C');
    
    $personal_details=fetch_personal_info_function($_SESSION['session_id']);
    /*
    $pdf->SetFont($font_family,'',11);
    $pdf->Cell(45,6,'*****************************************************************',0,1,'C');
    $pdf->Cell(32,6,"\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tStart of receipt",0,0,'C');
    $pdf->Ln();
    $pdf->Cell(45,6,'*****************************************************************',0,1,'C');
      */  
    
    $pdf->Ln();
 
    $my_name_title_is=$supplier_or_client_boolean==true? 'Client' : 'Supplier';
    //add student details
    //$pdf->SetFont($font_family,'B',14);
    //$pdf->Cell(60,8,$my_name_title_is." Details",0,1,'L');

    $pdf->SetFont($font_family,'',12);

    //$pdf->Cell(32,6,$my_name_title_is." ID:",0,0,'L');
    //$pdf->Cell(45,6,$supplier_or_client_id,0,1,'L');

    //$pdf->Cell(32,6,"Name:",0,0,'L');
    //$pdf->Cell(45,6,$supplier_or_client_name,0,1,'L');

    $service_expense_title_is=$supplier_or_client_boolean==true? 'Service:' : 'Expense:';
    $pdf->Cell(32,6,$service_expense_title_is .' Receipt',0,0,'L');
    
    $pdf->Ln();
    $total=0;
    $payment=0;
    
    // $pdf->Cell($w, $h, $txt, $border, $ln, $align, $fill)
        $pdf->Cell(45,6,'________________________________________________________________________________',0,1,'C');
        $pdf->SetFont($font_family,'B',11);
        $pdf->Cell(32,6,"ITEM\t\t\t\tQTY\t\t\t\tPRICE\t\t\t\tAMOUNT",0,0,'L');
        $pdf->SetFont($font_family,'',12);
        $pdf->Cell(45,6,'________________________________________________________________________________',0,1,'C');
        
    if($supplier_or_client_boolean==true)
    {
      
       
        
        foreach ($message_is as $value) 
        {
            $invoices=$value['invoices'];
            foreach ($value['payment'] as $payment_s) 
            {
                $payment+=$payment_s['amount'];
            }
            
            foreach ($invoices as $invoice_is) 
            {
                $total+=$invoice_is['full_amount'];
                $price=(int)$invoice_is['full_amount']/$invoice_is['quantity'];
                //$pdf->MultiCell(22,6, break_my_sentence_an_all($invoice_is['service_name'])."\t\t".$invoice_is['quantity']."\t".number_format($price,2),0,'L');
                $pdf->SetFont($font_family,'',10);
                $pdf->Cell(18,6,$invoice_is['service_name'],0,0,'L');
                $pdf->Ln();
                $pdf->Cell(18,6,$invoice_is['_id']."\t\t\t\t".$invoice_is['quantity']."\t\t\t\t".number_format($price,2)."\t\t\t\t".number_format($invoice_is['full_amount'],2)."",0,0,'L');
                $pdf->Ln();
            }
        }
    }
    else
    {
         foreach ($message_is as $value) 
        {
            $invoices=$value['vouchers'];
            foreach ($value['cash_out'] as $payment_s) 
            {
                $payment+=$payment_s['amount'];
            }
            
            foreach ($invoices as $invoice_is) 
            {
                $total+=$invoice_is['amount'];
                $price=(int)$invoice_is['amount'];
                //$pdf->MultiCell(22,6, break_my_sentence_an_all($invoice_is['service_name'])."\t\t".$invoice_is['quantity']."\t".number_format($price,2),0,'L');
                $pdf->SetFont($font_family,'',10);
                $pdf->Cell(18,6,$invoice_is['expense_account_name'],0,0,'L');
                $pdf->Ln();
                $pdf->Cell(18,6,$invoice_is['_id']."\t\t\t\t1\t\t\t\t".number_format($price,2)."\t\t\t\t".number_format($invoice_is['amount'],2)."",0,0,'L');
                $pdf->Ln();
            }
        }
    }
        $pdf->Cell(45,6,'--------------------------------------------------------------------------------',0,1,'C');
        $pdf->SetFont($font_family,'B',11);
        $pdf->Cell(32,6,"TOTAL\t\t\t\t\t\t\t\t\t\t\t\t\t\t".number_format($total,2)."",0,0,'L');
        $pdf->Ln();
        
        $pdf->Cell(32,6,"PAYMENT\t\t\t\t\t\t\t\t\t\t\t\t".number_format($payment,2)."",0,0,'L');
        $pdf->Ln();
        $pdf->Cell(32,6,"BALANCE\t\t\t\t\t\t\t\t\t\t\t\t".number_format($total-$payment,2)."",0,0,'L');
        $pdf->Ln();
       
        $pdf->Cell(45,6,'--------------------------------------------------------------------------------',0,1,'C');
    
    //add receipt details
    $pdf->SetFont($font_family,'B',14);
    $pdf->Cell(60,8,"Receipt Details",0,1,'L');

    $pdf->SetFont($font_family,'',12);

    //$pdf->Cell(32,6,"ID:",0,0,'L');
    //$pdf->Cell(45,6,$payment_id,0,1,'L');

    $voucher_invoice_id_title_is=$supplier_or_client_boolean==true? 'Invoice:' : 'Invoice:';
    $pdf->Cell(32,6,$voucher_invoice_id_title_is,0,0,'L');
    $pdf->Cell(45,6,$invoice_voucher_id,0,1,'L');

    $pdf->Cell(32,6,"Date:",0,0,'L');
    $pdf->Cell(45,6,$documented_payment_date,0,1,'L');

    $pdf->Cell(32,6,"Amount:",0,0,'L');
    $pdf->Cell(45,6,"KES ".number_format($amount,2),0,1,'L');

    $pdf->Cell(45,6,'________________________________________________________________________________',0,1,'C');
    $pdf->SetFont($font_family,'B',11);
    $pdf->Cell(32,6,"SERVED BY: ".strtoupper($personal_details['full_names']),0,0,'L');
    $pdf->SetFont($font_family,'',12);
    $pdf->Cell(45,6,'________________________________________________________________________________',0,1,'C');
    $pdf->Ln();
    
    /*
    $pdf->SetFont($font_family,'',11);
    $pdf->Cell(45,6,'*****************************************************************',0,1,'C');
    $pdf->Cell(32,6,"\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tEND of receipt",0,0,'C');
    $pdf->Ln();
    $pdf->Cell(32,6,"".md5(json_encode($message_is)),0,0,'L');
    $pdf->Ln();
    $pdf->Cell(45,6,'*****************************************************************',0,1,'C');
    $pdf->Ln();
    */
    //add account details
    //$pdf->SetFont($font_family,'B',14);
    //$pdf->Cell(60,8,"Account Details",0,1,'L');

    //$pdf->SetFont($font_family,'',12);

    //$pdf->Cell(32,6,"Payment mode:",0,0,'L');
    //$pdf->Cell(45,6,$payment_mode,0,1,'L');

    /*
    if($student['balance'] < 0){
        $balance_text = "Due";
        $balance = $student['balance'] * -1;
    }else{
        $balance_text = "Overpayment";
        $balance = $student['balance'];
    }
*/
    //$balance_text="ok";
    //$balance=1000;
    
    //$pdf->Cell(32,6,"Balance:",0,0,'L');
    //$pdf->Cell(45,6,"KES ".number_format($balance,2),0,1,'L');

    //add description
    //$pdf->SetFont($font_family,'B',14);
    //$pdf->Cell(60,8,"Description",0,1,'L');

    //$pdf->SetFont($font_family,'',12);
    //$pdf->MultiCell(75,7,$description,0);

    //add footer
    //$pdf->Ln();

    
     
    $pdf->SetFont($font_family,'B',11);
    $pdf->Cell(70,8,"Sternum bursar",0,1,'C');

    $pdf->SetFont($font_family,'',10);
    $pdf->Cell(70,8,"www.sternumbusiness.com",0,1,'C',false,"https://www.sternumbusiness.com/");

    //create a pdf file name that does not exist
    $count = 1;
    $basename = "receipt";
    //$filename = self::TEMP_FILE_PATH."receipt.pdf";

    $actual_name="reciept_".$supplier_or_client_name."_".$payment_id."_".$invoice_voucher_id."_".$amount.".pdf";
    $filename = "../downloads/".$actual_name;
   
    /*
    while (file_exists($filename)) {

        $temp_name = $basename."".$count;
        $filename = self::TEMP_FILE_PATH.$temp_name.".pdf";
        $count++;
    }
    */
//die('lol--'.$filename);
    $pdf->Output('F',$filename);
  
    header('location: '.$filename.'');
    // = str_replace("../..", "", $filename);

    //$this->utility->respond("SUCCESS", "Success", $filename, true, $this->cxn);
    }
    
    
    //vince_mc_carry_me_loot_before_i_res_247('Sternum Estate','Block A kawangware','rent',$supplier_or_client_boolean=true,'28227361','Jack machakos','6778888','2155414','19/07/2019',1000,20000,'This is a payment','MPESA');
    