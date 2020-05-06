<?php

namespace App\Command;

use App\Entity\Course;
use App\Service\PackageManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateCourseStatusCommand extends Command
{
    /** @var PackageManager */
    //private $packageManager;
    /** @var EntityManager */
    protected $em;

    protected static $defaultName = 'update.course.status';

    /**
     * UpdateCourseStatusCommand constructor.
     * @param PackageManager $packageManager
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
        //$this->packageManager = $packageManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        //

        $now = new \DateTime();
        $courses = $this->em->getRepository(Course::class)->findCourseByDate($now->add(new \DateInterval('P1D'))->setTime(0, 0, 0));
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
