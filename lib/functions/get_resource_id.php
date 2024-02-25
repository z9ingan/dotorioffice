<?

if (!function_exists('get_resource_id')) {
    function get_resource_id($resource) {
        return is_resource($resource) ? (int)$resource : null;
    }
}

?>