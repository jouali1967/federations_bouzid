<?php

namespace App\Livewire\Sanctions;

use Livewire\Component;
use Elibyy\TCPDF\Facades\TCPDF;
use App\Models\Sanction;
use App\Pdf\CustomPDF;

class ListeSanctionsPdf extends Component
{
    public function generatePdf()
    {
        $sanctions = Sanction::with('personne')->get();
        
        $pdf = new CustomPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        
        // Configuration du document
        $pdf->SetCreator('Système de Gestion');
        $pdf->SetAuthor('Admin');
        //$pdf->SetTitle('Liste des Sanctions');
        // Suppression des en-têtes et pieds de page par défaut
        //$pdf->setPrintHeader(false);
        //$pdf->setPrintFooter(false);
        $pdf->AddPage();
        $pdf->SetFont('dejavusans', '', 12);
        // En-tête du tableau
        $header = ['Personne',  'Date', 'Motif'];
        $w = [60, 20, 70];
        
        // Couleurs, ligne et police
        $pdf->SetFillColor(220, 220, 220);
        $pdf->SetTextColor(0);
        $pdf->SetLineWidth(0.1);
        $pdf->SetFont('dejavusans', 'B', 10);

        // En-tête
        for($i = 0; $i < count($header); $i++) {
            $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        }
        $pdf->Ln();

        // Données
        $pdf->SetFont('dejavusans', '', 9);
        $pdf->SetFillColor(255, 255, 255);
        
        foreach($sanctions as $sanction) {
            $pdf->Cell($w[0], 6, $sanction->personne->nom . ' ' . $sanction->personne->prenom, 1);
            $pdf->Cell($w[1], 6, $sanction->date_sanction, 1);
            $pdf->Cell($w[2], 6, $sanction->motif_sanction, 1);
            $pdf->Ln();
        }

        $pdfContent = $pdf->Output('liste_sanctions.pdf', 'S');
        
        return response()->streamDownload(function() use ($pdfContent) {
            echo $pdfContent;
        }, 'liste_sanctions.pdf', [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="liste_sanctions.pdf"'
        ]);
    }

    public function render()
    {
        return view('livewire.sanctions.liste-sanctions-pdf');
    }
} 