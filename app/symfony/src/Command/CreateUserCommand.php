<?php

namespace App\Command;

use App\Service\UserManipulator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create:user';
    /**
     * @var UserManipulator
     */
    public $userManipulator;

    /**
     * @param UserManipulator $userManipulator
     */
    public function __construct(UserManipulator $userManipulator)
    {
        parent::__construct();

        $this->userManipulator = $userManipulator;
    }


    protected function configure()
    {
        $this
            ->setDescription('Create a user.')
            ->addArgument('username', InputArgument::REQUIRED, 'The username')
            ->addArgument('email', InputArgument::REQUIRED, 'The email')
            ->addArgument('password', InputArgument::REQUIRED, 'The password')
            ->addOption('super-admin', null, InputOption::VALUE_NONE, 'Set the user as super admin')
            ->addOption('inactive', null, InputOption::VALUE_NONE, 'Set the user as inactive')
            ->setHelp(<<<'EOT'
The <info>fos:user:create</info> command creates a user:

  <info>php %command.full_name% matthieu</info>

This interactive shell will ask you for an email and then a password.

You can alternatively specify the email and password as the second and third arguments:

  <info>php %command.full_name% matthieu matthieu@example.com mypassword</info>

You can create a super admin via the super-admin flag:
  <info>php %command.full_name% admin --super-admin</info>

You can create an inactive user (will not be able to log in):

  <info>php %command.full_name% thibault --inactive</info>

EOT
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $inactive = $input->getOption('inactive');
        $superadmin = $input->getOption('super-admin');

        $this->userManipulator->create($username, $password, $email, !$inactive, $superadmin);

        $output->writeln(sprintf('Created user <comment>%s</comment>', $username));

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = [];

        if (!$input->getArgument('username')) {
            $question = new Question('Please choose a username:');
            $question->setValidator(function ($username) {
                if (empty($username)) {
                    throw new \Exception('Username can not be empty');
                }

                return $username;
            });
            $questions['username'] = $question;
        }

        if (!$input->getArgument('email')) {
            $question = new Question('Please choose an email:');
            $question->setValidator(function ($email) {
                if (empty($email)) {
                    throw new \Exception('Email can not be empty');
                }

                return $email;
            });
            $questions['email'] = $question;
        }

        if (!$input->getArgument('password')) {
            $question = new Question('Please choose a password:');
            $question->setValidator(function ($password) {
                if (empty($password)) {
                    throw new \Exception('Password can not be empty');
                }

                return $password;
            });
            $question->setHidden(true);
            $questions['password'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}
