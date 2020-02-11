<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class InstructorHourType extends AbstractType
{
    /** @var EntityManager */
    private $entityManager;
    /** @var TranslatorInterface $translator */
    private $translator;

    /**
     * InstructorHourType constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $currentDate = new \DateTime();
        $now = new \DateTime();
        $lasttDate = $now->add(new \DateInterval('P7D'));
        $offset = $currentDate->diff($lasttDate);
        /** @var User $inspector */
        $instructor = $options['instructor'];
        $student = $options['student'];

        //rechercher les date du moniteur
        $courses = $this->entityManager->getRepository(Course::class)->findBy([
            'instructor' => $instructor
        ]);
        /** @var Course $course */
        for ($day = 0; $day < $offset->days; $day++) {
            $date = $currentDate->add(new \DateInterval('P1D'));
            //$date->add(new \DateInterval('PT10H30S'));
            for ($hour = 7; $hour < 22; $hour++) { //l'heure
                $checkboxValue = false;
                $date->setTime($hour, 0, 0);
                $label = sprintf('%s H - %s %s %s',
                    $hour,
                    substr($this->translator->trans(Course::DAYS[$date->format('N')]), 0, 3),
                    $date->format('j'),
                    substr($this->translator->trans(Course::MONTHS[$date->format('n')]), 0, 3));
                $fieldName = sprintf('hours-%d-%d', $day, $hour);
                $attr = [

                    'class' => 'course-hour',
                    'data-instructor' => $instructor->getId(),
                    'data-hour' => $hour,
                    'mapped' => false,
                    'data-dateTime' => $date->format('d/m/Y'),

                ];

                foreach ($courses as $course) {
                    if ($course->getCourseDate() == $date) {
                        $checkboxValue = true;
                        if ($student != $course->getStudent() or $course->getStatus() ==2) {
                            $attr['disabled'] = 'disabled';
                        }
                    }
                }

                $builder
                    ->add($fieldName, CheckboxType::class,
                        [
                            'label' => $label,
                            'data' => $checkboxValue,
                            'attr' => $attr
                        ]);

            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'instructor' => null,
            'student' => null,

        ]);
    }
}
