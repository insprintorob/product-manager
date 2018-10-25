<?php
namespace ProductManager;

class SimpleView
{
    /**
     * SimpleViews very simple view rendering function
     * It takes a PHP template file path as a parameter and renders it if the file exists
     * Otherwise it will output a "Template does not exist" error.
     *
     * $data can be anything you like (array, string, object etc) and will be available inside the template
     *
     * @param string $path
     * @param any $data
     */
    public function render(string $path, $data) : string
    {
        if (file_exists($path)) {
            ob_start();
            require($path);
            $output = ob_get_clean();
            return $output;
        } else {
            // Otherwise, render a very simple error
            return "Template does not exist";
        }
    }
}