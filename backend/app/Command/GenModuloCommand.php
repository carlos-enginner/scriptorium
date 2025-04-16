<?php

declare(strict_types=1);

namespace App\Command;

use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Symfony\Component\Console\Input\InputArgument;

#[Command(name: "gen:module", description: "Create a new clean arch module")]
class GenModuloCommand extends HyperfCommand
{
    public function __construct()
    {
        parent::__construct('gen:module');
    }

    protected function configure()
    {
        $this->setDescription('Create a new clean arch module.')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the module (e.g. Book)');
    }

    public function handle()
    {
        $name = ucfirst($this->input->getArgument('name'));
        $basePath = BASE_PATH . "/app/{$name}";

        $structure = [
            "Domain/Entity/{$name}.php" => "<?php\n\nnamespace App\\{$name}\\Domain\\Entity;\n\nclass {$name} {}\n",
            "Domain/Repository/{$name}RepositoryInterface.php" => "<?php\n\nnamespace App\\{$name}\\Domain\\Repository;\n\ninterface {$name}RepositoryInterface {}\n",
            "Http/Controller/{$name}Controller.php" => "<?php\n\nnamespace App\\{$name}\\Http\\Controller;\n\nclass {$name}Controller {}\n",
            "Infra/Model/{$name}Model.php" => "<?php\n\nnamespace App\\{$name}\\Infra\\Model;\n\nclass {$name}Model {}\n",
            "Infra/Repository/{$name}Repository.php" => "<?php\n\nnamespace App\\{$name}\\Infra\\Repository;\n\nclass {$name}Repository {}\n",
            "UseCase/GetAll{$name}UseCase.php" => "<?php\n\nnamespace App\\{$name}\\UseCase;\n\nclass GetAll{$name}UseCase {}\n",
            "UseCase/Create{$name}UseCase.php" => "<?php\n\nnamespace App\\{$name}\\UseCase;\n\nclass Create{$name}UseCase {}\n",
            "UseCase/Update{$name}UseCase.php" => "<?php\n\nnamespace App\\{$name}\\UseCase;\n\nclass Update{$name}UseCase {}\n",
            "UseCase/Delete{$name}UseCase.php" => "<?php\n\nnamespace App\\{$name}\\UseCase;\n\nclass Delete{$name}UseCase {}\n",
        ];

        foreach ($structure as $relativePath => $content) {
            $fullPath = $basePath . '/' . $relativePath;
            $dir = dirname($fullPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            file_put_contents($fullPath, $content);
        }

        $this->output->writeln("<info>Module '{$name}' generated successfully.</info>");
    }
}
