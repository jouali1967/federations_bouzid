<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Declaration;
use App\Pdf\EtatDeclaresPdf;

class EtatDeclaresPdfController extends Controller
{
  public function imprimer(Request $request)
  {
    $date = $request->input('date_declaration');
    [$mois, $annee] = explode('/', $date);
    $employes = Declaration::with(['personne', 'personne.inscriptions'])
      ->whereMonth('date_dec', $mois)
      ->whereYear('date_dec', $annee)
      ->get()
      ->map(function ($declaration) {
        $personne = $declaration->personne;
        $cnss = $personne->inscriptions->first();
        return [
          'nom' => $personne->nom,
          'prenom' => $personne->prenom,
          'cin' => $personne->cin,
          'num_cnss' => $cnss ? $cnss->num_cnss : null,
          'nombre_enfants' => $personne->enfants ? $personne->enfants->count() : 0,
          'situation_famille' => $personne->sit_fam,
          'salaire_base' => $personne->salaire_base,
          'montant_dec' => $declaration->mont_dec,
        ];
      });

    $pdf = new EtatDeclaresPdf($mois, $annee);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetY(30);
    $i = 1;
    $count_pers = count($employes);
    foreach ($employes as $employe) {
      $pdf->SetX(5);
      $pdf->Cell(10, 8, $i, 1, 0, 'C', false);
      $pdf->Cell(60, 8, mb_strtoupper($employe['nom'] . ' ' . $employe['prenom']), 1, 0, 'L', false);
      $pdf->Cell(20, 8, $employe['cin'], 1, 0, 'L', false);
      $pdf->Cell(20, 8, $employe['num_cnss'] ?? '-', 1, 0, 'C', false);
      $pdf->Cell(10, 8, $employe['nombre_enfants'], 1, 0, 'C', false);
      $pdf->Cell(10, 8, $employe['situation_famille'], 1, 0, 'C', false);
      $pdf->Cell(25, 8, number_format($employe['salaire_base'], 0, ',', ' ') . ' Dhs', 1, 0, 'R', false);
      $pdf->Cell(25, 8, number_format($employe['montant_dec'], 0, ',', ' ') . ' Dhs', 1, 1, 'R', false);
      $i++;
      // $count_pers = $count_pers - 1;
      // if ($pdf->GetY() + 45 > ($pdf->getPageHeight() - $pdf->getFooterMargin()) and $count_pers < 5) {
      //   $pdf->AddPage();
      // }

    }
    $pdf->Output('etat_declare.pdf', 'I');
  }
}
