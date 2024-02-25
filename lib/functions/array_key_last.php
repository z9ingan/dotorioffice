<?

if (!function_exists('array_key_last')) {
    function array_key_last($array) {
        end($array);
        return key($array);
    }
}

?>