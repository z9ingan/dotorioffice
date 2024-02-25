<?

if (!function_exists('str_contains')) {
    function str_contains ($haystack, $needle)
    {
        return empty($needle) || strpos($haystack, $needle) !== false;
    }
}

?>