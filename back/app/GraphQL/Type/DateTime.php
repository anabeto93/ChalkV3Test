<?php


namespace App\GraphQL\Type;

use Carbon\Carbon;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;

class DateTime extends ScalarType {
    const FORMAT = 'Y-m-d H:i:s';

    private static $_instance = null;

    /**
     * @var string
     */
    public $name = "DateTime";

    /**
     * DateTime constructor.
     */
    public function __construct() {
        Utils::invariant($this->name, 'Type must be named.');
    }

    protected function __clone() {}

    static public function type() {
        if(is_null(self::$_instance))
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @var string
     */
    public $description = "The `DateTime` scalar type represents textual data, represented as UTF-8 character sequences. The DateTime type has the format YYYY-MM-DD HH:MM:SS";

    /**
     * Serializes an internal value to include in a response.
     *
     * @param mixed $value
     * @return mixed
     */
    public function serialize($value) {
        return Carbon::createFromFormat(self::FORMAT, $value);
    }

    /**
     * Parses an externally provided value (query variable) to use as an input
     *
     * @param mixed $value
     * @return mixed
     */
    public function parseValue($value) {
        return Carbon::createFromFormat(self::FORMAT, $value);
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input
     *
     * @param \GraphQL\Language\AST\Node $valueNode
     * @return mixed
     */
    public function parseLiteral($valueNode) {
        return Carbon::createFromFormat(self::FORMAT, $valueNode->value);
    }
}