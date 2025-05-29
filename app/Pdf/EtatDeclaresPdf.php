<?php

namespace App\Pdf;

use TCPDF;

class EtatDeclaresPdf extends TCPDF
{
    protected $mois;
    protected $annee;

  public function __construct($mois, $annee)
  {
    parent::__construct('P', 'mm', 'A4', true, 'UTF-8', false);
    $this->mois = $mois;
    $this->annee = $annee;
    // Configuration du PDF
    $this->SetCreator('Laravel App');
    $this->SetAuthor('Système de Gestion');
    $this->SetSubject('État des employés');
    $this->SetKeywords('employés, état, PDF');

    // Marges
    $this->SetMargins(15, 20, 15);
    $this->SetHeaderMargin(5);
    $this->SetFooterMargin(10);
    $this->SetAutoPageBreak(true, 25);
  }

  // Header personnalisé
  public function Header()
  {
    // Logo ou en-tête entreprise
    if ($this->page == 1) {
      $this->SetFont('helvetica', 'B', 14);
      $this->Cell(0, 0, "Liste des employés", 0, 1, 'C');
      $this->Ln(2);
      $this->SetFont('helvetica', 'B', 10);
      $this->Cell(0, 0, $this->mois.'/'.$this->annee, 0, 1, 'C');
      $this->SetXY(5, 22);
      $this->SetFont('helvetica', 'B', 9);
      $this->MultiCell(10, 8, "N°", 1, 'C', 0, 0, null, null, true, 0, false, true, 8, 'M');
      $this->MultiCell(60, 8, "Nom et Prénom", 1, 'C', 0, 0, null, null, true, 0, false, true, 8, 'M');
      $this->MultiCell(20, 8, "CIN", 1, 'C', 0, 0, null, null, true, 0, false, true, 8, 'M');
      $this->MultiCell(20, 8, "CNSS", 1, 'C', 0, 0, null, null, true, 0, false, true, 8, 'M');
      $this->MultiCell(10, 8, "NB\nENF", 1, 'C', 0, 0, null, null, true, 0, false, true, 8, 'M');
      $this->MultiCell(10, 8, "SIT\nFAM", 1, 'C', 0, 0, null, null, true, 0, false, true, 8, 'M');
      $this->MultiCell(25, 8, "SALAIRE DE\nBASE", 1, 'C', 0, 0, null, null, true, 0, false, true, 8, 'M');
      $this->MultiCell(25, 8, "MONTANT\nDECLARES", 1, 'C', 0, 0, null, null, true, 0, false, true, 8, 'M');
    }
  }

  // Footer personnalisé
  public function Footer()
  {
    $this->SetY(-15);
    // Numéro de page
    $this->SetFont('helvetica', 'I', 8);
    $this->SetTextColor(100, 100, 100);
    $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . ' / ' . $this->getAliasNbPages(), 0, 0, 'C');

    // Date et heure de génération
    $this->SetX(15);
    $this->Cell(0, 10, 'Généré le ' . date('d/m/Y à H:i'), 0, 0, 'L');
  }

  // Méthode pour créer l'en-tête du tableau

  // Méthode pour ajouter une ligne de données
}
