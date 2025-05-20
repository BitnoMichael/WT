<?php
declare(strict_types=1);
require_once './.constants/Constants.php';

class TemplateRenderer
{
    protected array $vars = [];
    protected array $collections = [];

    public function assign(string $key, mixed $value): void
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
    public function clear(): void
    {
        $this->collections = [];
        $this->vars = [];
    }

    public function render($path): string
    {
        $fp = fopen($path, "r") or die("can't read stdin");
        $output= fread($fp, filesize($path));
        fclose($fp);

        // Подстановка файла
        $output = preg_replace_callback('/{% file (.*?) %}/s', function ($matches) {
            $path = trim($matches[1]);
            $fp = fopen($path, "r") or die("can't read stdin");
            $output= fread($fp, filesize($path));
            fclose($fp);
            return $output;
        }, $output);

        // Обработка условий
        $output = preg_replace_callback('/{% if (.*?) %}(.*?){% else %}(.*?){% endif %}/s', function ($matches) {
            $condition = trim($matches[1]);
            $content = $matches[2];
            $elseContent = $matches[3];
            if ($this->evaluateCondition($condition)) {
                return $content;
            }
            else
            {
                return $elseContent;
            }
        }, $output);

        $output = preg_replace_callback('/{% if (.*?) %}(.*?){% endif %}/s', function ($matches) {
            $condition = trim($matches[1]);
            $content = $matches[2];
            if ($this->evaluateCondition($condition)) {
                return $content;
            }
            else
            {
                return '';
            }
        }, $output);

        // Обработка циклов
        $output = preg_replace_callback('/{% for (.*?) in (.*?) %}(.*?){% endfor %}/s', function ($matches) {
            $item = trim($matches[1]);
            $array = trim($matches[2]);
            $content = $matches[3];

            $result = '';
            if (isset($this->collections[$array])) 
            {
                foreach ($this->collections[$array] as $value) 
                {
                    if (is_object($value))
                    {
                        $objectPropertiesKeys = array_keys(get_object_vars($value));
                        $objectPropertiesValues = get_object_vars($value);

                        foreach ($objectPropertiesKeys as $properyName) 
                        {
                            $this->assign($item . '.' . $properyName, $objectPropertiesValues[$properyName]);
                        }

                        $methods = get_class_methods($value);

                        foreach ($methods as $method)
                        {
                            $reflection = new ReflectionMethod($value, $method);
                            if ($reflection->getNumberOfRequiredParameters() > 0 || str_starts_with($method, "__construct"))
                                continue;
                            $this->assign($item . '.' . $method . '()', $reflection->invoke($value));
                        }
                    }
                    else if (is_array($value))
                    {
                        foreach (array_keys($value) as $properyName) 
                        {
                            $this->assign($item . '.' . $properyName, $value[$properyName]);
                        }
                    }
                    else if (is_string($value))
                    {
                        $this->assign($item, $value);
                    }
                    else
                    {
                        die("WRONG TEMPLATE ARGUMENT");
                    }
                    $result .= $this->renderString($content);
                }
            }
            return $result;
        }, $output);
        
        // Обработка переменных
        $output = $this->renderString($output);

        //mjml
        $output = preg_replace_callback('/{% mjml %}(.*?){% endmjml %}/s', function ($matches) {
            $content = "'" . trim($matches[1]) . "'";
            // header("Content-Type: text/plain; charset=utf-8");
            // echo $content;
            // die;
            $command = "echo " . $content . " | mjml -i -";
            $html = shell_exec($command);
            return trim($html);
        }, $output);

        return $output;
    }

    protected function renderString(string $string): string
    {
        foreach ($this->vars as $key => $value) {
            $placeholder = '{% ' . $key . ' %}';
            $string = str_replace($placeholder, htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'), $string);
        }
        return $string;
    }

    protected function evaluateCondition(string $condition): bool
    {
        return !empty($this->vars[$condition]);
    }
}