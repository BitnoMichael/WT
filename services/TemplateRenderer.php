<?php

class TemplateRenderer
{
    protected $vars = [];
    protected $collections = [];

    public function __construct()
    {
    }

    public function assign($key, $value)
    {
        if (is_array($value))
        {
            $this->collections[$key] = $value;
        }
        else
        {
            $this->vars[$key] = $value;
        }
    }
    public function clear()
    {
        $this->collections = [];
        $this->vars = [];
    }

    public function render($path)
    {
        $fp = fopen($path, "r") or die("can't read stdin");
        $output= fread($fp, filesize($path));
        fclose($fp);

        // Обработка условий
        $output = preg_replace_callback('/{% if (.*?) %}(.*?){% endif %}/s', function ($matches) {
            $condition = trim($matches[1]);
            $content = $matches[2];
            if ($this->evaluateCondition($condition)) {
                return $content;
            }
            return '';
        }, $output);

        // Обработка циклов
        $output = preg_replace_callback('/{% for (.*?) in (.*?) %}(.*?){% endfor %}/s', function ($matches) {
            $item = trim($matches[1]);
            $array = trim($matches[2]);
            $content = $matches[3];

            $result = '';
            if (isset($this->collections[$array])) {
                foreach ($this->collections[$array] as $value) {
                    $this->assign($item, $value);
                    $result .= $this->renderString($content);
                }
            }
            return $result;
        }, $output);

        // Обработка переменных
        $output = $this->renderString($output);

        return $output;
    }

    protected function renderString($string)
    {
        foreach ($this->vars as $key => $value) {
            $placeholder = '{{ ' . $key . ' }}';
            $string = str_replace($placeholder, htmlspecialchars($value, ENT_QUOTES, 'UTF-8'), $string);
        }
        return $string;
    }

    protected function evaluateCondition($condition)
    {
        return !empty($this->vars[$condition]);
    }
}