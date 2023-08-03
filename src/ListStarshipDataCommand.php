<?php

namespace William\SevencooksTestTask;

use GuzzleHttp\Exception\GuzzleException;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use William\SevencooksTestTask\ApiClient\StarshipApiClient;
use William\SevencooksTestTask\DataObject\Cargo;
use William\SevencooksTestTask\DataObject\Pilot;
use William\SevencooksTestTask\DataObject\Starship;
use William\SevencooksTestTask\Sanitizers\StarshipDataSanitizer;
use William\SevencooksTestTask\Services\StarshipService;

class ListStarshipDataCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('starships:list')
            ->setDescription('This command will fetch Starships data from an endpoint and will display as a list.')
            ->addArgument(
                'no_of_starships',
                InputArgument::REQUIRED,
                'Specify number of Starships that you want to fetch from the API.'
            )
        ;
    }

    /**
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** Initialization process starts */
        $noOfStarships = $input->getArgument('no_of_starships');
        $config = Yaml::parse(file_get_contents(__DIR__ . '/../config.yml'));
        $configSanitizer = $config['settings']['sanitizer'];
        $configMonolog = $config['settings']['monolog'];
        $fullLogPath = __DIR__ . '/../log/';
        $cargoTypes = [
            'Food',
            'Weapons',
            'Raw Materials',
            'Robots',
            'Luxury Goods',
        ];

        $logger = new Logger($configMonolog['name']);
        $logger->pushHandler(new StreamHandler($fullLogPath . $configMonolog['path'], Level::Warning));

        $starshipApiClient = new StarshipApiClient('https://swapi.dev/api/');
        $starshipSanitizer = new StarshipDataSanitizer($configSanitizer, $logger);
        $starships = $starshipApiClient->getStarships('starships', $noOfStarships);
        $sanitizedStarships = [];
        /** Initialization process ends */

        // initializing progress bar
        $progressBar = new ProgressBar($output, 100);
        $progressBar->setMessage("System will now make API requests to fetch and aggregate data.");
        $progressBar->start();

        foreach ($starships as $starship) {
            // showing progress as per loop
            $progressBar->advance(100 / $noOfStarships);
            // sanitizing the data received from the API
            $sanitizedStarshipData = $starshipSanitizer->sanitizeData($starship);
            // creating a new Starship object from sanitized data
            $starshipService = new StarshipService(new Starship());
            $starshipObj = $starshipService->createAndReturnStarship($sanitizedStarshipData);

            // if there is pilots assigned to the Starship, getting data of those pilots
            if (count($sanitizedStarshipData['pilots'])) {
                foreach ($sanitizedStarshipData['pilots'] as $pilotUrl) {
                    $pilotData = $starshipApiClient->getPilotInfo($pilotUrl);
                    $pilot = new Pilot();
                    $pilot->setName($pilotData['name']);
                    $pilot->setHeight((int)$pilotData['height']);
                    $starshipObj->addPilot($pilot);
                }
            }

            // adding random cargo object to the starship
            $numberOfCargo = rand(0, 2);
            for ($x = 0; $x <= $numberOfCargo; $x++) {
                $randomCargoTypeKey = array_rand($cargoTypes);
                $starshipObj->addCargo(
                    new Cargo($cargoTypes[$randomCargoTypeKey], rand(10, 999)),
                    $logger
                );
            }

            $sanitizedStarships[] = $starshipObj;
        }

        // ending the progress bar
        $progressBar->finish();

        usort($sanitizedStarships, function ($a, $b) {
            return $b->getMaxSpeed() - $a->getMaxSpeed();
        });

        $output->writeln(PHP_EOL . "<fg=black;bg=cyan>Printing $noOfStarships Starship info from the API</>");
        foreach ($sanitizedStarships as $key => $sanitizedStarship) {
            $output->writeln(sprintf("<info>Name: %s</info>", $sanitizedStarship->getName()));
            $output->writeln(sprintf("<info>Model: %s</info>", $sanitizedStarship->getModel()));
            $output->writeln(sprintf("<info>Length: %s</info>", $sanitizedStarship->getLength()));
            $output->writeln(sprintf("<info>Cargo Capacity: %s</info>", $sanitizedStarship->getCargoCapacity()));
            $output->writeln(sprintf("<info>Crew Size: %s</info>", $sanitizedStarship->getCrewSize()));
            $output->writeln(sprintf("<info>Max Speed: %s</info>", $sanitizedStarship->getMaxSpeed()));

            foreach ($sanitizedStarship->getPilots() as $k => $pilot) {
                $pilotSerial = $k + 1;
                $output->writeln(sprintf("<info>Pilot$pilotSerial Name: %s</info>", $pilot->getName()));
                $output->writeln(sprintf("<info>Pilot$pilotSerial Height: %scm</info>", $pilot->getHeight()));
            }

            $output->writeln(sprintf("<question>Is carrying cargo?: %s</question>", count($sanitizedStarship->getCargo()) ? 'Yes' : 'No'));
            foreach ($sanitizedStarship->getCargo() as $k => $cargo) {
                $cargoSerial = $k + 1;
                $output->writeln(sprintf("<info>Cargo$cargoSerial Name: %s</info>", $cargo->getType()));
                $output->writeln(sprintf("<info>Cargo$cargoSerial Weight: %stons</info>", $cargo->getWeight()));
            }

            ($key === 0) ?: $output->writeln(sprintf("<bg=yellow;options=bold>This Starship is %s percent slower than the fastest Starship.</>", $sanitizedStarship->getSpeedPercentComparison($sanitizedStarships[0]->getMaxSpeed())));
            $output->writeln(sprintf("<comment>=================================================</comment>"));
        }

        return Command::SUCCESS;
    }
}