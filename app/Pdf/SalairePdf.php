<?php

namespace App\Pdf;

use TCPDF;

class SalairePdf extends TCPDF
{
  protected $dateVirement;
  protected $numCompte;

  // Constructor to receive the data
  public function __construct($dateVirement, $numCompte, $orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false)
  {
    parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache);
    $this->dateVirement = $dateVirement; // String 'dd/mm/YYYY'
    $this->numCompte = $numCompte;
  }
  // Header personnalisé
  public function Header()
  {
    if ($this->getPage() === 1) {
      $this->SetFont('helvetica', 'B', 9);
      $this->SetXY(4, 6);
      $this->Cell(0, 0, 'Fédération des associations', 0, 1);
      $this->Cell(0, 0, 'mly abdellah', 0, 1);
      $this->SetX(110);
      $this->Cell(120, 0, 'Mly Abdellah le :' . date('d/m/Y'), 0, 1);
      $this->SetX(145);
      $this->Cell(1, 0, 'A', 0, 1);
      $this->SetX(130);
      $this->Cell(60, 0, 'Monsieur,le Directeur de la BP', 0, 1);
      $this->SetX(130);
      $this->Cell(60, 0, 'Sidi Bouzid d\'El Jadida', 0, 0);
      $this->SetFont('helvetica', 'U', 10);
      $this->SetXY(4, 35);
      $this->Cell(0, 0, 'Objet :', 0, 0);
      $this->SetFont('times', '', 10);
      $this->setX(15);
      $this->Cell(0, 0, 'Demande de Virement', 0, 1);
      $this->SetFont('times', '', 10);

      $this->Cell(0, 0, 'Nous avons L\'honneur de vous demander de bien vouloir virer de notre compte ' . $this->numCompte . ' de la fédération des', 0, 1);
      $this->SetX(4);
      $this->Cell(0, 0, 'Associations Mly Abdellah Veuillez créditer les comptes', 0, 1);
      $this->Cell(0, 0, 'ci-aprés,à partir de ' . $this->dateVirement . ' et jusqu\'a nouvel ordre.', 0, 1);
      $this->Ln();
      $this->SetX(4);
      $this->SetFont('times', 'B', 9);
      $this->SetX(4);
      $this->MultiCell(15, 7, "N°", 1, 'C', 0, 0, null, null, true, 0, false, true, 6, 'M');
      $this->MultiCell(85,  7, "Nom et Prénom", 1, 'C', 0, 0, null, null, true, 0, false, true, 6, 'M');
      $this->MultiCell(50, 7, "Numero de Compte", 1, 'C', 0, 0, null, null, true, 0, false, true, 6, 'M');
      $this->MultiCell(50, 7, "Montant", 1, 'C', 0, 0, null, null, true, 0, false, true, 6, 'M');
      $this->ln();
    }
  }

  // Footer personnalisé
  public function Footer()
  {
    $this->setY(-19);
    $this->setFontSize(9);
    $this->cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
  }
}
