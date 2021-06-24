<?php

namespace backend\plug;

class Plugins
{
    public static $plugins = [];

    public static function start($plugins = []): array
    {
        $namespace = "\\backend\\plug\\";
        foreach ($plugins as $key => $value) {
            $namespace .= $value . '\\' . 'Plugin';
            if (!in_array($value, self::$plugins)) {
                self::$plugins[$value] = new $namespace();
            }
        }

        $plugin_html = '';
        $plugin_data = [];
        $plugin_func = '';

        foreach (self::$plugins as $key => $plug) {
            $plugin_html .= $plug->html();
            $plugin_data += $plug->data();
            $plugin_func .= $plug->func() . ',';
        }

        return [
            'plugin_html' => $plugin_html,
            'plugin_data' => json_encode($plugin_data,256),
            'plugin_func' => trim($plugin_func,',')
        ];
    }
}