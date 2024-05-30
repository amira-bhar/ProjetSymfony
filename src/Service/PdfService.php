<?php

namespace App\Service;

use FPDF;
use App\Entity\Recipe;

class PdfService
{
    public function generateRecipePdf(Recipe $recipe): string
    {
        // Create a new FPDF instance
        $pdf = new FPDF();
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('Arial', 'B', 16);

        // Add header
        $pdf->Cell(0, 10, "L'AtelierGourmand", 0, 1, 'C');

        // Add title
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10,$recipe->getName(), 0, 1, 'C');


        // Add recipe details 
        $pdf->SetFont('Arial', '', 12);
        $pdf->Ln(10);

        if ($recipe->getTime() !== null) {
            $pdf->Cell(0, 10, "Time: " . $recipe->getTime() . " minutes", 0, 1);
        }
    

        if ($recipe->getDifficulty() !== null) {
            $pdf->Cell(0, 10, "Difficulty: " . $recipe->getDifficulty() . "/5", 0, 1);
        }
  

        if ($recipe->getNbPeople() !== null) {
            $pdf->Cell(0, 10, "Serves: " . $recipe->getNbPeople() . " people", 0, 1);
        }


        if ($recipe->getPrice() !== null) {
            $pdf->Cell(0, 10, "Price: $" . $recipe->getPrice(), 0, 1);
        }
    

        // Add description
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, "Description:", 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 10, $recipe->getDescription());

        // Add ingredients
        if (count($recipe->getIngredients()) > 0) {
            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, "Ingredients:", 0, 1);
            $pdf->SetFont('Arial', '', 12);
            foreach ($recipe->getIngredients() as $ingredient) {
                $pdf->Cell(0, 10, "- " . $ingredient->getName(), 0, 1);
            }
        }


        // Output PDF as a string
        return $pdf->Output('S');
    }
}
