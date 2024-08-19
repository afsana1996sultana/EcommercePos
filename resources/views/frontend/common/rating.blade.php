<?php

// Define a function to calculate and display the average rating
function displayAverageRating($data) {
    $totalRating = 0;
    $reviewCount = 0;

    foreach ($data as $value) {
        if ($value->status == 1) {
            $totalRating += $value->rating;
            $reviewCount++;
        }
    }

    // Calculate the average rating, considering division by zero
    $averageRating = $reviewCount > 0 ? $totalRating / $reviewCount : 0;

    // Generate the HTML for displaying the average rating
    $html = '<div class="product__rating">';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $averageRating) {
            $html .= '<i class="fa fa-star"></i>';
        } elseif ($i - 0.5 == $averageRating) {
            $html .= '<i class="fa fa-star-half-stroke"></i>';
        } else {
            $html .= '<i class="fa fa-star-o"></i>';
        }
    }
    $html .= '</div>';

    return $html;
}

// Usage example:
$data = /* Your data here */;
echo displayAverageRating($data);

?>
