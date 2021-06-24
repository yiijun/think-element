<?php
namespace backend\plug;

interface PluginInterface
{
    public function html();

    public function data();

    public function func();
}