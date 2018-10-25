<?php
class SimpleView
{
    /**
     * SimpleViews very simple view rendering function
     * It takes a PHP template file path as a parameter and renders it, if the file exists
     * Otherwise it will output a "Template does not exist" error.
     *
     * $data can be anything you like (array, string, object etc) and will be available inside the template
     *
     * @param string $path
     * @param any $data
     */
    public function render(string $path, $data) : void
    {
        if (file_exists($path)) {
            // Output the template
            require($path);
            return;
        } else {
            // Otherwise, render a very simple error
            echo "Template does not exist";
        }
    }
}