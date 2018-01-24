<?php

namespace Modules\Admin\Console;

use Illuminate\Console\Command;
use Nwidart\Modules\Facades\Module;
use PhpParser\Node\Expr\AssignOp\Mod;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PublishTests extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:publish-tests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish a module\'s tests to the application';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $module = false;
        if ($this->argument('module')) {
            $module = $this->argument('module');
        }

        $map = $this->getModuleMap($module);

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->load(base_path('phpunit.xml'));

        $root = $dom->documentElement;

        $testSuiteNode = $root->getElementsByTagName('testsuites')[0];
        if (!$testSuiteNode) {
            $this->error('Something is not quite right with your phpunit.xml file');
            return false;
        }

        $testSuites = $root->getElementsByTagName('testsuite');

        $removableNodes = [];
        $removableModules = $module ? [strtolower($module)] : $this->getModuleNames();

        foreach ($testSuites as $testSuite) {
            if (in_array($testSuite->getAttribute('name'), $removableModules)) {
                $removableNodes[] = $testSuite;
            }
        }

        foreach ($removableNodes as $node) $testSuiteNode->removeChild($node);

        foreach ($map as $name => $path) {
            $newTestSuite = $dom->createElement('testsuite');
            $newTestSuite->setAttribute('name', $name);

            $directory = $dom->createElement('directory', $path);
            $directory->setAttribute('suffix', 'Test.php');

            $newTestSuite->appendChild($directory);

            $testSuiteNode->appendChild($newTestSuite);
        }

        $dom->save(base_path('phpunit.xml'));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'Publishable module'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }

    /**
     * @param bool $module
     * @return array
     */
    private function getModuleMap($module = false)
    {
        $modules = Module::collections();

        $map = [];
        foreach ($modules as $name => $class) {
            if ($module) {
                if (strtolower($name) === strtolower($module)) {
                    $map[strtolower($name)] = './vendor/netcore/module-' . strtolower($name) . '/Tests';
                }
            } else {
                $map[strtolower($name)] = './vendor/netcore/module-' . strtolower($name) . '/Tests';
            }
        }

        return $map;
    }

    /**
     * @return array
     */
    private function getModuleNames()
    {
        $modules = Module::collections();

        $names = [];
        foreach ($modules as $name => $class) {
            $names[] = strtolower($name);
        }

        return $names;
    }
}
