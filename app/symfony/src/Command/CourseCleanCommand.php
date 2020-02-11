<?php

namespace App\Command;

use App\Entity\Card;
use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CourseCleanCommand extends Command
{
    protected static $defaultName = 'app:course:clean';
    /** @var EntityManagerInterface */
    private $em;

    /**
     * CourseCleanCommand constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'suprimmr les reservation non payé et ont expiré')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $now = new \DateTime();
        $now->add(new \DateInterval('P1D'))->setTime(0,0,0);
        // recuperer les courses inferieur ou égale à cette date
        $cards = $this->em->getRepository(Card::class)->findBy([
            'status' => Card::PENDING,
            'shop' => null
        ]);

          foreach($cards as $card){
              /** @var Course $course */
              foreach ($card->getCourses() as $course){
                  if($course->getCourseDate() <= $now){
                      $this->em->remove($course);
                      $this->em->flush();
                  }
              }

              if ($card->getCourses()->count() === 0){
                  $this->em->remove($card);
                  $this->em->flush();
              }

          }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
