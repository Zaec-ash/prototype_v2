<?php
require_once('libraries/fpdf/fpdf.php');
require_once('libraries/fpdi/src/autoload.php');

use setasign\Fpdi\Fpdi;

// Get form data
$campus_type = $_POST['campus_type'] ?? 'on';
$other_text = trim($_POST['other_text'] ?? '');
$currentYear = date('Y');
$nextYear = date('Y') + 1;
$schoolYear = $currentYear . '-' . $nextYear;

// Get the total page count of the source file
$pdf = new Fpdi();
$pageCount = $pdf->setSourceFile('d.pdf');

// Long Bond Paper / Folio Size (8.5 x 13 inches) in mm
$width = 215.9;
$height = 330.2;

// Determine which pages to use based on campus type
if ($campus_type === 'off') {
    // Off-Campus: Use pages 3 and 4
    $pages_to_use = [3, 4];
    $page_offset = 3;
} else {
    // On-Campus: Use pages 1 and 2
    $pages_to_use = [1, 2];
    $page_offset = 1;
}

// Loop through the pages to use
foreach ($pages_to_use as $index => $pageNo) {
    // Import the current page from template
    $tplIdx = $pdf->importPage($pageNo);
    
    // Create a new blank page in Long Bond Paper size
    $pdf->addPage('P', array($width, $height));
    
    // Stretch/Fit the template to fill the 8.5 x 13 area
    $pdf->useTemplate($tplIdx, 0, 0, $width, $height);
    
    // Write data on first page of the set (Page 1 for On-Campus, Page 3 for Off-Campus)
    if ($index == 0) {
        $pdf->SetFont('Helvetica', '', 14);
        $pdf->SetTextColor(0, 0, 0);
        
        // School Year
        $pdf->SetXY(96, 31);
        $pdf->Write(0, $schoolYear);
        
        // --- Name of Organization ---
        $name = $_POST['rso_name'] ?? 'Sample Organization Name Here';
        
        // Adjust font based on length
        if (strlen($name) > 30) {
            $pdf->SetFont('Helvetica', '', 10);
            $lineHeight = 4;
        } else {
            $pdf->SetFont('Helvetica', '', 12);
            $lineHeight = 5;
        }
        
        // Set position for organization name (adjust Y based on page)
        $orgY = ($campus_type === 'off') ? 100 : 100;
        $pdf->SetXY(50, $orgY);
        $pdf->MultiCell(140, $lineHeight, $name, 0, 'L');
        
        // --- Activity Title ---
        $pdf->SetFont('Helvetica', '', 12);
        $titleY = ($campus_type === 'off') ? 111 : 111;
        $pdf->SetXY(50, $titleY);
        $pdf->Write(0, $_POST['act_title'] ?? 'Sample Activity');
        
        // --- Nature of Activity Checkbox ---
        $act_type = $_POST['act_type'] ?? '';
        $pdf->SetFont('ZapfDingbats', '', 12);
        $checkX = 52;
        
        // Adjust Y positions for Off-Campus (page 3 has slightly different layout)
        if ($campus_type === 'off') {
            // Off-Campus page 3 checkbox positions
            if ($act_type == "Meeting/Fellowship") {
                $pdf->SetXY($checkX, 114);
                $pdf->Write(0, chr(52));
            } elseif ($act_type == "Maintenance/Cleaning") {
                $pdf->SetXY($checkX, 117.5);
                $pdf->Write(0, chr(52));
            } elseif ($act_type == "Seminar/Training/Forum") {
                $pdf->SetXY($checkX, 121);
                $pdf->Write(0, chr(52));
            } elseif ($act_type == "Socialization") {
                $pdf->SetXY($checkX + 52.5, 114);
                $pdf->Write(0, chr(52));
            } elseif ($act_type == "Contest/Competition") {
                $pdf->SetXY($checkX + 52.5, 117.5);
                $pdf->Write(0, chr(52));
            } elseif ($act_type == "Extension/Outreach") {
                $pdf->SetXY($checkX + 52.5, 121);
                $pdf->Write(0, chr(52));
            } elseif ($act_type == "Campaign/Recruitment") {
                $pdf->SetXY($checkX + 97, 114);
                $pdf->Write(0, chr(52));
            } elseif ($act_type == "Income Generating Activity") {
                $pdf->SetXY($checkX + 97, 117.5);
                $pdf->Write(0, chr(52));
            } elseif ($act_type == "Collection of Fees/Fines") {
                $pdf->SetXY($checkX + 97, 121);
                $pdf->Write(0, chr(52));
            } elseif ($act_type == "Others") {
                $pdf->SetFont('Helvetica', '', 9);
                $pdf->SetXY(60, 124);
                $pdf->Write(0, $other_text);
            }
        } else {
            // On-Campus page 1 checkbox positions
            if ($act_type == "Meeting/Fellowship") {
                $pdf->SetXY($checkX, 114);
                $pdf->Write(0, chr(52));
            } elseif ($act_type == "Maintenance/Cleaning") {
                $pdf->SetXY($checkX, 117.5);
                $pdf->Write(0, chr(52));
            } elseif ($act_type == "Seminar/Training/Forum") {
                $pdf->SetXY($checkX, 121);
                $pdf->Write(0, chr(52));
            } elseif ($act_type == "Socialization") {
                $pdf->SetXY($checkX + 52.5, 114);
                $pdf->Write(0, chr(52));
            } elseif ($act_type == "Contest/Competition") {
                $pdf->SetXY($checkX + 52.5, 117.5);
                $pdf->Write(0, chr(52));
            } elseif ($act_type == "Extension/Outreach") {
                $pdf->SetXY($checkX + 52.5, 121);
                $pdf->Write(0, chr(52));
            } elseif ($act_type == "Campaign/Recruitment") {
                $pdf->SetXY($checkX + 97, 114);
                $pdf->Write(0, chr(52));
            } elseif ($act_type == "Income Generating Activity") {
                $pdf->SetXY($checkX + 97, 117.5);
                $pdf->Write(0, chr(52));
            } elseif ($act_type == "Collection of Fees/Fines") {
                $pdf->SetXY($checkX + 97, 121);
                $pdf->Write(0, chr(52));
            } elseif ($act_type == "Others") {
                $pdf->SetFont('Helvetica', '', 9);
                $pdf->SetXY(60, 124);
                $pdf->Write(0, $other_text);
            }
        }
        
        // Switch back to normal font
        $pdf->SetFont('Helvetica', '', 12);
        
        // --- Objectives ---
        $obj1 = $_POST['objectives1'] ?? '';
        $obj2 = $_POST['objectives2'] ?? '';
        $obj3 = $_POST['objectives3'] ?? '';
        
        $objY = ($campus_type === 'off') ? 130.5 : 130.5;
        $pdf->SetXY(50, $objY);
        $pdf->Write(0, $obj1);
        $pdf->SetXY(50, $objY + 6);
        $pdf->Write(0, $obj2);
        $pdf->SetXY(50, $objY + 12);
        $pdf->Write(0, $obj3);
        
        // --- Dates ---
        $raw_start = $_POST['act_start'] ?? '';
        $raw_end = $_POST['act_end'] ?? '';
        $date_start = (!empty($raw_start)) ? date("M j, Y", strtotime($raw_start)) : '';
        $date_end = (!empty($raw_end)) ? date("M j, Y", strtotime($raw_end)) : '';
        
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetXY(46, 152.5);
        $pdf->Write(0, $date_start);
        $pdf->SetXY(65, 152.5);
        $pdf->Write(0, "-");
        $pdf->SetXY(67, 152.5);
        $pdf->Write(0, $date_end);
        
        // --- Times ---
        $raw_time_start = $_POST['time_start'] ?? '';
        $raw_time_end = $_POST['time_end'] ?? '';
        $time_start = (!empty($raw_time_start)) ? date("g:i A", strtotime($raw_time_start)) : '';
        $time_end = (!empty($raw_time_end)) ? date("g:i A", strtotime($raw_time_end)) : '';
        
        $pdf->SetXY(95, 156);
        $pdf->Write(0, $time_start . " to " . $time_end);
        
        // --- Venue ---
        $venue = $_POST['act_venue'] ?? '';
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetXY(152, 152.5);
        $pdf->Write(0, $venue);
        
        // --- RSO President / Requested by ---
        $requested_by = $_POST['client_name'] ?? '';
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetXY(50, 210);
        $pdf->Write(0, $requested_by);
        
        // Date Signed
        $date_signed = date('F d, Y');
        $pdf->SetXY(50, 218);
        $pdf->Write(0, $date_signed);
        
        // --- RSO Adviser ---
        $adviser_name = $_POST['adviser_name'] ?? '';
        $pdf->SetXY(50, 235);
        $pdf->Write(0, $adviser_name);
        
        // Date Endorsed
        $pdf->SetXY(50, 243);
        $pdf->Write(0, $date_signed);
        
        // For Off-Campus, add additional fields on page 3
        if ($campus_type === 'off') {
            // Off-Campus specific: VP for Academic Affairs approval section
            $pdf->SetXY(50, 280);
            $pdf->Write(0, '________________________');
            $pdf->SetXY(50, 288);
            $pdf->Write(0, $date_signed);
        }
    }
    
    // Write data on second page of the set (Page 2 for On-Campus, Page 4 for Off-Campus)
    if ($index == 1) {
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->SetTextColor(0, 0, 0);
        
        // Risk Assessment Form data
        $assessed_by = $_POST['client_name'] ?? '';
        $position = $_POST['position'] ?? 'RSO President';
        $date_assessed = date('F d, Y');
        
        // Fill in Risk Assessment header
        $rso_name = $_POST['rso_name'] ?? '';
        $act_title = $_POST['act_title'] ?? '';
        
        $pdf->SetFont('Helvetica', '', 11);
        
        // Name of RSO
        $pdf->SetXY(50, 45);
        $pdf->Write(0, $rso_name);
        
        // Title of Activity
        $pdf->SetXY(50, 55);
        $pdf->Write(0, $act_title);
        
        // Campus type checkbox
        $pdf->SetFont('ZapfDingbats', '', 12);
        if ($campus_type === 'on') {
            $pdf->SetXY(50, 65);
            $pdf->Write(0, chr(52)); // Checkmark for In Campus
        } else {
            $pdf->SetXY(70, 65);
            $pdf->Write(0, chr(52)); // Checkmark for Out of Campus
        }
        
        // Date of Assessment
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetXY(50, 75);
        $pdf->Write(0, $date_assessed);
        
        // Assessed by
        $pdf->SetXY(50, 280);
        $pdf->Write(0, $assessed_by);
        
        // Position
        $pdf->SetXY(50, 290);
        $pdf->Write(0, $position);
        
        // Attested by (RSO Adviser)
        $adviser_name = $_POST['adviser_name'] ?? '________________________';
        $pdf->SetXY(130, 280);
        $pdf->Write(0, $adviser_name);
        
        $pdf->SetXY(130, 290);
        $pdf->Write(0, 'RSO Adviser');
        
        // For SOAU ONLY section
        $soau_staff = $_POST['soau_staff'] ?? '________________________';
        $pdf->SetXY(50, 320);
        $pdf->Write(0, $soau_staff);
    }
}

// Output the PDF
if (isset($_POST['preview']) && $_POST['preview'] === 'true') {
    // For AJAX preview - return as blob
    $pdfContent = $pdf->Output('S');
    header('Content-Type: application/pdf');
    header('Content-Length: ' . strlen($pdfContent));
    echo $pdfContent;
} else {
    // For download - force download
    $filename = 'Activity_Permit_' . date('Ymd_His') . '.pdf';
    $pdf->Output('D', $filename);
}
?>