<?php

declare(strict_types=1);

namespace Arachne\Security\Authorization;

use Nette\Security\IIdentity;
use Nette\Security\Permission as BasePermission;
use Nette\Utils\Callback;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class Permission extends BasePermission
{
    /**
     * @var IIdentity|null
     */
    private $identity;

    public function setIdentity(?IIdentity $identity = null): void
    {
        $this->identity = $identity;
    }

    /**
     * Allows one or more Roles access to [certain $privileges upon] the specified Resource(s).
     * If $assertion is provided, then it must return TRUE in order for rule to apply.
     *
     * @param string|array|Permission::ALL $roles
     * @param string|array|Permission::ALL $resources
     * @param string|array|Permission::ALL $privileges
     * @param callable|null                $assertion
     *
     * @return static
     */
    public function allow($roles = self::ALL, $resources = self::ALL, $privileges = self::ALL, $assertion = null): Permission
    {
        if ($assertion !== null) {
            $assertion = function () use ($assertion) {
                return Callback::invoke($assertion, $this->identity, $this->getQueriedResource(), $this->getQueriedRole());
            };
        }

        parent::allow($roles, $resources, $privileges, $assertion);

        return $this;
    }

    /**
     * Denies one or more Roles access to [certain $privileges upon] the specified Resource(s).
     * If $assertion is provided, then it must return TRUE in order for rule to apply.
     *
     * @param string|array|Permission::ALL $roles
     * @param string|array|Permission::ALL $resources
     * @param string|array|Permission::ALL $privileges
     * @param callable|null                $assertion
     *
     * @return static
     */
    public function deny($roles = self::ALL, $resources = self::ALL, $privileges = self::ALL, $assertion = null): Permission
    {
        if ($assertion !== null) {
            $assertion = function () use ($assertion) {
                return Callback::invoke($assertion, $this->identity, $this->getQueriedResource(), $this->getQueriedRole());
            };
        }

        parent::deny($roles, $resources, $privileges, $assertion);

        return $this;
    }
}
