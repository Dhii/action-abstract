<?php

namespace Dhii\Action;

use InvalidArgumentException;
use Exception as RootException;

/**
 * Functionality for awareness of an action factory.
 *
 * @since [*next-version*]
 */
trait ActionFactoryAwareTrait
{
    /**
     * The action factory associated with this instance.
     *
     * @since [*next-version*]
     *
     * @var ActionFactoryInterface|null
     */
    protected $actionFactory;

    /**
     * Retrieves the action factory associated with this instance.
     *
     * @since [*next-version*]
     *
     * @throws RootException If action factory cannot be retrieved.
     *
     * @return ActionFactoryInterface|null The action factory associated with this instance, if any.
     */
    protected function _getActionFactory()
    {
        return $this->actionFactory;
    }

    /**
     * Associates an action factory with this instance.
     *
     * @since [*next-version*]
     *
     * @param ActionFactoryInterface|null $factory The action factory to set.
     *
     * @throws InvalidArgumentException If the action factory is invalid.
     */
    protected function _setActionFactory($factory)
    {
        if (!is_null($factory)) {
            $factory = $this->_normalizeActionFactory($factory);
        }

        $this->actionFactory = $factory;
    }

    /**
     * Normalizes a value into an action factory.
     *
     * @param ActionFactoryInterface|null $factory The action factory to normalize.
     *
     * @throws InvalidArgumentException If the action factory is invalid.
     *
     * @return ActionFactoryInterface The normalized factory.
     */
    abstract protected function _normalizeActionFactory($factory);
}
