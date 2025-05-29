<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salaire;
use App\Pdf\ViremenetSalaire;
use Elibyy\TCPDF\Facades\TCPDF;

class SalaireImpressionPdfController extends Controller
{
  public function export(Request $request)
  {
    $mois = $request->input('mois');
    $annee = $request->input('annee');
    $salaires = Salaire::with('personne')
      ->whereMonth('date_virement', $mois)
      ->whereYear('date_virement', $annee)
      ->get();

    $pdf = new ViremenetSalaire('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->AddPage();

    // Titre
    $pdf->Ln(2);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 8, 'Période : ' . str_pad($mois, 2, '0', STR_PAD_LEFT) . '/' . $annee, 0, 1, 'L');
    $pdf->SetLineWidth(1); // Définir l'épaisseur de la ligne
    $pdf->line(10, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->SetLineWidth(0.2); // Réinitialiser l'épaisseur de la ligne pour les autres éléments
    $pdf->Ln(2);

    // En-tête du tableau avec fond de couleur et centrage vertical
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->SetFillColor(220, 220, 220); // gris clair
    $pdf->MultiCell(60, 8, "Nom et Prénom", 1, 'C', 1, 0, null, null, true, 0, false, true, 8, 'M');
    $pdf->MultiCell(20, 8, "Salaire de\nBase", 1, 'C', 1, 0, null, null, true, 0, false, true, 8, 'M');
    $pdf->MultiCell(20, 8, "Montant\nPrimes", 1, 'C', 1, 0, null, null, true, 0, false, true, 8, 'M');
    $pdf->MultiCell(20, 8, "Montant\nSanctions", 1, 'C', 1, 0, null, null, true, 0, false, true, 8, 'M');
    $pdf->MultiCell(20, 8, "Salaire\nMensuel", 1, 'C', 1, 0, null, null, true, 0, false, true, 8, 'M');
    $pdf->MultiCell(40, 8, "N° Compte", 1, 'C', 1, 1, null, null, true, 0, false, true, 8, 'M');

    // Lignes du tableau avec centrage vertical
    $pdf->SetFont('helvetica', '', 8);
    foreach ($salaires as $salaire) {
      $pdf->MultiCell(60, 5, $salaire->personne->nom . ' ' . $salaire->personne->prenom, 1, 'L', 0, 0, null, null, true, 0, false, true, 0, 'M');
      $pdf->MultiCell(20, 5, $salaire->salaire_base, 1, 'R', 0, 0, null, null, true, 0, false, true, 5, 'M');
      $pdf->MultiCell(20, 5, $salaire->montant_prime, 1, 'R', 0, 0, null, null, true, 0, false, true, 5, 'M');
      $pdf->MultiCell(20, 5, $salaire->montant_sanction, 1, 'R', 0, 0, null, null, true, 0, false, true, 5, 'M');
      $pdf->MultiCell(20, 5, $salaire->montant_vire, 1, 'R', 0, 0, null, null, true, 0, false, true, 5, 'M');
      $pdf->MultiCell(40, 5, $salaire->personne->num_compte, 1, 'R', 0, 1, null, null, true, 0, false, true, 5, 'M');
    }

    $pdf->Output('etat_salaires.pdf');
  }
}
