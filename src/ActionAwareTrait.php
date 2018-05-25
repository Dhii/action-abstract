<?php

namespace Dhii\Action;

use InvalidArgumentException;

/**
 * Functionality for awareness of an action.
 *
 * @since [*next-version*]
 */
trait ActionAwareTrait
{
    /**
     * The action associated with this instance.
     *
     * @since [*next-version*]
     *
     * @var ActionInterface|null
     */
    protected $action;

    /**
     * Retrieves the action associated with this instance.
     *
     * @since [*next-version*]
     *
     * @return ActionInterface|null The action associated with this instance, if any.
     */
    protected function _getAction()
    {
        return $this->action;
    }

    /**
     * Associates an action with this instance.
     *
     * @param ActionInterface|null $action The action to set.
     *
     * @throws InvalidArgumentException If the action is invalid.
     */
    protected function _setAction($action)
    {
        if (!is_null($action)) {
            $action = $this->_normalizeAction($action);
        }

        $this->action = $action;
    }

    /**
     * Normalizes a value into an action.
     *
     * @param ActionInterface|mixed $action The value to normalize to an action.
     *
     * @throws InvalidArgumentException If the action cannot be normalized.
     *
     * @return ActionInterface The normalized action.
     */
    abstract protected function _normalizeAction($action);
}
