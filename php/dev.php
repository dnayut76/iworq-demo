<?php
/**
 * dev.php
 *
 * Development utilities — include this file in local/dev environments only.
 * Remove or exclude this file from production builds.
 */

/**
 * Prints any value wrapped in <pre> tags for readable browser output.
 * Arrays and objects are formatted with print_r(); all other types are printed directly.
 *
 * @param mixed $output  The value to display.
 */
function printPre(mixed $output): void {
    echo '<pre>';

    if (is_array($output) || is_object($output)) {
        print_r($output);
    } else {
        print($output);
    }

    echo '</pre>';
}