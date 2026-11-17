<?php

namespace iutnc\deefy\Dispatch;

use iutnc\deefy\Exception\RouterException;

class Dispatcher
{
    protected string $action;
    protected array $actions = [];

    public function __construct()
    {
        $action = $_GET['action'] ?? 'default';

        $this->action = is_string($action) ? $action : '';
    }

    public function addRoute(string $action, string $actionClass): self
    {
        $this->actions[$action] = $actionClass;

        return $this;
    }

    public function run(): string
    {
        if (isset($this->actions[$this->action])) {

            $classe = $this->actions[$this->action];

            $action = new $classe();

            return $action->execute();
        }

        throw new RouterException('Action inconnue');
    }
}