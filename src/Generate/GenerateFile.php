<?php

namespace Webapi\Generate;

use Exception;
use Illuminate\Support\Facades\Artisan;

/**
 * Class GenerateFile
 * @package Webapi\Generate
 * @author mr.sirichai janpan <boy.sirichai@bitkub.com>
 */
class GenerateFile implements GenerateFileInterface
{
    /**
     * set replace string
     * @var string
     */
    protected $replace;

    /**
     * set replace small case
     * @var string
     */
    protected $replaceSmall;

    /**
     * set replace snagecase
     * @var string
     */
    protected $replaceSnake;

    /**
     * set replace url
     * @var string
     */
    protected $replaceUrl;

    /**
     * set action
     * @var string
     */
    protected $action;

    /**
     * set config
     * @var array
     */
    protected $config;

    /**
     * set file name
     * @var string
     */
    protected $filename;

    /**
     * set path
     * @var string
     */
    protected $path;

    /**
     * set style
     * @var string
     */
    protected $style;
    /**
     * set data for write file
     * @var string
     */
    protected $data;

    /** @var string
     * set namespace of controller
     */
    protected $controllerNamespace = 'App\Http\Controllers';

    /**
     * @var string[]
     */
    protected $needDuplicate = array(
        'Request' => 'requestType',
        'Lang'    => 'configLang',
    );

    /**
     * set config path
     * @var array
     */
    protected $configPath = array(

        'Request'            => array(
            'resource' => 'template/Request.php',
            'target'   => 'app/Http/Requests/',
            'needDir'  => true,
        ),
        'Controller'         => array(
            'resource'  => 'template/Controller.php',
            'target'    => 'app/Http/Controllers/',
            'needDir'   => true,
            'namespace' => 'App\Http\Controllers',
        ),
        'Route'              => array(
            'resource' => 'template/Route.php',
            'target'   => 'Routes/',
            'needDir'  => true,
        ),
        'Service'        => array(
            'resource' => 'template/Service.php',
            'target'   => 'app/Services/',
            'needDir'  => true,
        ),
    );

    /**
     * @var bool[]
     */
    protected $requestType = array(
        'Index'  => true,
        'Store'  => true,
        'Show'   => true,
        'Update' => true,
        'Delete' => true,
    );

    /**
     * GenerateFile constructor.
     * @param string $namespace
     */
    public function __construct($namespace = '')
    {
        $this->setReplaceConfig($namespace);

        if (!empty(config('generate.template'))) {
            $this->configPath = config('generate.template');
        }
        $this->useRepository = config('generate.using_repository');

    }


    /**
     * @param $replace
     */
    protected function setReplaceConfig($replace)
    {
        $this->replace      = ucfirst($replace);
        $this->replaceSmall = strtolower($replace);
        $this->replaceSnake = self::strCamelCase($replace);
        $this->replaceUrl   = $this->urlGenerate($this->replaceSnake);
    }


    /**
     * @param array $config
     */
    public function setConfig($config = array())
    {
        $this->config = $config;
    }


    /**
     * @param string $path
     */
    public function setPath($path = '')
    {
        $this->path = $path;
    }


    /**
     * @param string $filename
     */
    public function setFilename($filename = '')
    {
        $this->filename = $filename;
    }


    /**
     * @return string
     */
    public function getFullFileName()
    {
        return $this->path . '/' . $this->filename;
    }

    /**
     * @param $data
     * @return false|int
     */
    public function writeFile($data)
    {
        if (!is_dir($this->path)) {
            mkdir($this->path, 0777, true);
        }
        $filename = $this->getFullFileName();
        return file_put_contents($filename, $data . "\r\n", FILE_APPEND);
    }


    /**
     * @param string $input
     * @return string
     */
    protected function strCamelCase($input = '')
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }

    /**
     *
     */
    public function execute()
    {
        foreach ($this->configPath as $key => $list) {
            # reset action
            $this->action = '';
            if (array_key_exists($key, $this->needDuplicate)) {
                $property = $this->needDuplicate[$key];
                $this->processDuplicate($key, $property, $list);
            } else {
                $filename = $this->checkFilename($key);
                $this->processReadWriteFile($filename, $list);
            }
        }
    }

    /**
     * @param $key
     * @return string
     */
    protected function checkFilename($key)
    {
        if (array_key_exists($key, $this->noNeedKey)) {
            return $this->replace . '.php';
        }
        return $this->replace . ucfirst($key) . '.php';
    }

    /**
     * @param $key
     * @param $property
     * @param $list
     * @throws Exception
     */
    protected function processDuplicate($key, $property, $list)
    {
        if ($key === 'Lang') {
            foreach ($this->configLang as $key => $value) {
                $path    = $list['target'] . '/' . $key;
                $newFile = $this->readAndReplaceFile($list['resource']);

                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                $fullPath = $path . '/' . strtolower($this->replace) . '.php';
                file_put_contents($fullPath, $newFile);
                $this->printLine($fullPath);
            }

        } else {
            foreach ($this->{$property} as $action => $need) {
                if ($need === true) {
                    $this->action = ucfirst($action);
                    $filename     = ucfirst($action) . $this->replace . ucfirst($key) . '.php';
                    $this->processReadWriteFile($filename, $list);
                }
            }
        }

    }

    /**
     * @param $filename
     * @param $list
     * @throws Exception
     */
    protected function processReadWriteFile($filename, $list)
    {
        $newFile = $this->readAndReplaceFile($list['resource']);
        if (isset($list['namespace'])) {
            $this->controllerNamespace = $list['namespace'];
        } else {
            $this->controllerNamespace = $this->controllerNamespace . '\\' . $this->replace;
        }
        if ($this->useRepository === true) {
            $this->repositoryNamespace = $this->repositoryNamespace . '\\' . $this->replace . 'Repository';
        } else {
            $this->repositoryNamespace = 'App\Repositories\\' . $this->replace . '\\' . $this->replace . 'RepositoryEloquent as ' . $this->replace . 'Repository';

        }
        if ($list['needDir'] === true) {
            $path = $this->path . '/' . $list['target'] . '/' . $this->replace;
        } else {
            $path = $this->path . '/' . $list['target'] . '/';
        }

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }


        $fullPath = $path . '/' . $filename;
        file_put_contents($fullPath, $newFile);
        $this->printLine($filename);
    }


    /**
     * @param string $text
     */
    protected function printLine($text = '')
    {
        echo "\r\n";
        echo "Write file \e[44m" . $text . " success \e[49m";
    }


    /**
     * @param $config
     * @return string|string[]
     * @throws Exception
     */
    protected function readAndReplaceFile($config)
    {
        if (file_exists((__DIR__ . '/' . $config))) {
            $file = file_get_contents(__DIR__ . '/' . $config);
            return $this->replaceFile($file);
        }
        throw new \Exception("Can't read file :" . $config);

    }

    /**
     * @param string $input
     * @return mixed|string|string[]
     */
    protected function urlGenerate($input = '')
    {
        return str_replace("_", '-', $input);
    }


    /**
     * @param string $file
     * @return string|string[]
     */
    protected function replaceFile($file = '')
    {
        $file = str_replace(array("{replace}"), $this->replace, $file);
        $file = str_replace(array("{replace_sm}"), $this->replaceSmall, $file);
        $file = str_replace(array("{replace_snc}"), $this->replaceSnake, $file);
        $file = str_replace(array("{replace_url}"), $this->replaceUrl, $file);
        $file = str_replace(array("{action}"), ucfirst($this->action), $file);
        $file = str_replace(array("{controller_namespace}"), $this->controllerNamespace, $file);
        $file = str_replace(array("{repository}"), $this->repositoryNamespace, $file);
        return $file;
    }

    /**
     * @param string $path
     */
    public function appendRoute($path = 'api')
    {
        if (file_exists(base_path("routes/{$path}.php"))) {
            $data = "\r\n";
            $data .= "# {$this->replace} \r\n";
            $data .= "require base_path('routes/{$this->replace}/{$this->replace}Route.php');";
            file_put_contents(base_path("routes/{$path}.php"), $data . "\r\n", FILE_APPEND);
        }
    }
}
