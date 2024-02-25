<?

if (!function_exists('get_resource_type')) {
    function get_resource_type($resource) {
        return is_resource($resource) ? get_resource_type($resource) : null;
    }
}

?>