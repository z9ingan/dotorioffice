<?

if (!function_exists('get_debug_type')) {
    function get_debug_type($value) {
        return is_object($value) ? get_class($value) : gettype($value);
    }
}
?>