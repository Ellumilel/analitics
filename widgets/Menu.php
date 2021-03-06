<?php

namespace app\widgets;

use Yii;

class Menu extends \dmstr\widgets\Menu
{
    protected function isItemActive($item)
    {
        if (isset($item['url']) && is_array($item['url']) && isset($item['url'][0])) {

            $route = $item['url'][0];
            if ($route[0] !== '/' && Yii::$app->controller) {
                $route = Yii::$app->controller->module->getUniqueId() . '/' . $route;
            }

            $arrayRoute = explode('/', ltrim($route, '/'));
            $arrayThisRoute = explode('/', $this->route);
            if (!empty($arrayRoute[1]) && !empty($arrayThisRoute[1]) && ($arrayRoute[1] !== $arrayThisRoute[1])) {
                return false;
            }
            if ($arrayRoute[0] !== $arrayThisRoute[0]) {
                return false;
            }
            unset($item['url']['#']);
            if (count($item['url']) > 1) {
                foreach (array_splice($item['url'], 1) as $name => $value) {
                    if ($value !== null && (!isset($this->params[$name]) || $this->params[$name] != $value)) {
                        return false;
                    }
                }
            }
            return true;
        }
        return false;
    }
}
