<?php

namespace Dhii\Action;

use Exception as RootException;
use Dhii\Util\String\StringableInterface as Stringable;
use InvalidArgumentException;

/**
 * Functionality for action normalization.
 *
 * @since [*next-version*]
 */
trait NormalizeActionCapableTrait
{
    /**
     * Normalizes a value into an action.
     *
     * @param ActionInterface|mixed $action The value to normalize to an action.
     *
     * @throws InvalidArgumentException If the action cannot be normalized.
     *
     * @return ActionInterface The normalized action.
     */
    protected function _normalizeAction($action)
    {
        if (!($action instanceof ActionInterface)) {
            throw $this->_createInvalidArgumentException($this->__('Invalid action'), null, null, $action);
        }

        return $action;
    }

    /**
     * Creates a new Dhii invalid argument exception.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable|int|float|bool|null $message  The message, if any.
     * @param int|float|string|Stringable|null      $code     The numeric error code, if any.
     * @param RootException|null                    $previous The inner exception, if any.
     * @param mixed|null                            $argument The invalid argument, if any.
     *
     * @return InvalidArgumentException The new exception.
     */
    abstract protected function _createInvalidArgumentException(
        $message = null,
        $code = null,
        RootException $previous = null,
        $argument = null
    );

    /**
     * Translates a string, and replaces placeholders.
     *
     * @since [*next-version*]
     * @see sprintf()
     * @see _translate()
     *
     * @param string $string  The format string to translate.
     * @param array  $args    Placeholder values to replace in the string.
     * @param mixed  $context The context for translation.
     *
     * @return string The translated string.
     */
    abstract protected function __($string, $args = [], $context = null);
}
