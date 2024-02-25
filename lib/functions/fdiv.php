<?

if (!function_exists('fdiv')) {
    function fdiv($dividend, $divisor) {
        return $divisor === 0 ? NAN : $dividend / $divisor;
    }
}

?>