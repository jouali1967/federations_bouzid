<?php

namespace App\Http\Controllers;

use App\Pdf\SalairePdf;
use App\Models\Personne;
use Illuminate\Http\Request;
use Elibyy\TCPDF\Facades\TCPDF;

class PdfPersonneController extends Controller
{
public function generate(Request $request)
{
    $nom1 = $request->query('nom1');
    $nom2 = $request->query('nom2');
    $dateVirement = $request->query('date_virement');
    $numCompte = $request->query('num_compte');
    $pdf = new SalairePdf($dateVirement,$numCompte,'P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetMargins(10, 6, 10);
    $pdf->SetAutoPageBreak(true, 15);
    $pdf->AddPage('P');
    $pdf->SetY(64);
    $compt = 1;
    $total = 0;

    // Données (exemple)
    $data = array_merge(
        array_fill(0, 10, ['nom' => 'test1', 'num_compte' => '868688', 'mont' => 678]),
        array_fill(0, 10, ['nom' => 'test1', 'num_compte' => '868688', 'mont' => 678]),
        array_fill(0, 10, ['nom' => 'test1', 'num_compte' => '868688', 'mont' => 678]),
        array_fill(0, 10, ['nom' => 'test1', 'num_compte' => '868688', 'mont' => 678]),
        array_fill(0, 10, ['nom' => 'test1', 'num_compte' => '868688', 'mont' => 678]),
        array_fill(0, 10, ['nom' => 'test1', 'num_compte' => '868688', 'mont' => 678]),
        array_fill(0, 10, ['nom' => 'test1', 'num_compte' => '868688', 'mont' => 678]),
        array_fill(0, 1, ['nom' => 'test1', 'num_compte' => '868688', 'mont' => 678]),
    );

    // Hauteur d’une ligne
    $lineHeight = 6;
    $count_pers=count($data);
    $personnes= personne::all();
    foreach ($personnes as $personne) {
        // Données
        $pdf->SetFont('times', '', 8);
        $pdf->SetX(4);
        $pdf->Cell(15, $lineHeight, $compt, 1,  0,'C');
        $pdf->Cell(85, $lineHeight, $personne->full_name, 1,  0);
        $pdf->Cell(50, $lineHeight, $personne->num_compte, 1, 0,'L');
        $pdf->Cell(50, $lineHeight, $personne->salaire_base, 1, 0,'R');
        $pdf->Ln();
        $total += $personne->salaire_base;
        $compt++;
        $count_pers=$count_pers-1;
        if ($pdf->GetY() + 30 > ($pdf->getPageHeight() - $pdf->getFooterMargin()) and $count_pers<5) {
            $pdf->AddPage();
        }

    }

    // Total à la fin
    $pdf->SetFont('times', 'B', 10);
    $pdf->SetX(4);
    $pdf->Cell(150, 6, 'Total', 1, 0,'R');
    $pdf->Cell(50, 6, number_format($total, 2, '.', ' '), 1,  0,'R');
    $pdf->SetY($pdf->getY()+30);
    $pdf->Cell(20, 0, 'Signé', 0, 0,'');
    $pdf->SetX(160);
    $pdf->Cell(20, 0, 'Signé', 0, 1,'');
    $pdf->setY($pdf->getY()+4);
    $pdf->Cell(60,0,mb_strtoupper($nom1, 'UTF-8'),0,0);
    $pdf->SetX(160);
    $pdf->Cell(60,0,mb_strtoupper($nom2, 'UTF-8'),0,0);






    return $pdf->Output('liste_personnes.pdf', 'I');
}

}
