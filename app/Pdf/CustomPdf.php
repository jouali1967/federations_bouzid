<?php

namespace App\Pdf;

use TCPDF;

class CustomPdf extends TCPDF
{
  protected $num_compte;
  protected $date_virement;

  public function __construct($num_compte = null, $date_virement = null)
  {
    parent::__construct();
    $this->num_compte = $num_compte;
    $this->date_virement = $date_virement;
  }

  // Header personnalisé
  public function Header()
  {
    $this->SetFont('helvetica', 'B', 14);
    $this->Cell(0, 10, 'Etat des Employés', 0, 1, 'C');
    $this->SetFont('helvetica', '', 10);
    if ($this->num_compte || $this->date_virement) {
      $this->Cell(0, 8, 'N° Compte : ' . ($this->num_compte ?: '-') . '   Date virement : ' . ($this->date_virement ?: '-'), 0, 1, 'C');
    }
    $this->Ln(2);
    // Ajout du texte demandé
    $this->SetFont('helvetica', 'B', 11);
    $this->Cell(0, 7, 'Objet : Demande de Virement', 0, 1, 'L');
    $this->SetFont('helvetica', '', 10);
    $this->Cell(0, 6, "Nous avons L'honneur de vous demander de bien vouloir virer de notre compte " . ($this->num_compte ?: '-') . " de la fédération des", 0, 1, 'L');
    $this->Cell(0, 6, "Associations Mly Abdellah Veuillez créditer les comptes", 0, 1, 'L');
    $this->Cell(0, 6, "ci-aprés,à partir de " . ($this->date_virement ?: '-') . " et jusqu'a nouvel ordre.", 0, 1, 'L');
    $this->Ln(2);
  }

  // Footer personnalisé
  public function Footer()
  {
    $this->SetY(-15);
    $this->SetFont('helvetica', 'I', 8);
    $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
  }
}
