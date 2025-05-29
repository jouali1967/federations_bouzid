<?php

namespace App\Pdf;

use TCPDF;

class ViremenetSalaire extends TCPDF
{

  // Constructor to receive the data
  // Header personnalisé
  public function Header()
  {
    if ($this->getPage() === 1) {
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
