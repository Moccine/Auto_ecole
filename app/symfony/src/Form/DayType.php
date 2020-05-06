<?php

namespace App\Form;

use App\Constant\Hours;
use App\Entity\Course;
use App\Entity\Day;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class DayType extends AbstractType
{

    const HOUR = 22;
    const DAY = 7;
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;
    const SUNDAY = 7;
    const DAYS = [
        self::MONDAY => 'days.monday',
        self::TUESDAY => 'days.tuesday',
        self::WEDNESDAY => 'days.wednesday',
        self::THURSDAY => 'days.thursday',
        self::FRIDAY => 'days.friday',
        self::SATURDAY => 'days.saturday',
        self::SUNDAY => 'days.sunday',
    ];
    /** @var TranslatorInterface */
    private $translator;
    /** @var EntityManagerInterface */
    private $em;

    /**
     * DayType constructor.
     * @param TranslatorInterface $translator
     * @param EntityManagerInterface $em
     */
    public function __construct(TranslatorInterface $translator, EntityManagerInterface $em)
    {
        $this->translator = $translator;
        $this->em = $em;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dayName = isset($options['day_name']) ? $options['day_name'] : null;
        /** @var \DateTime $date */
        $date = isset($options['date']) ? $options['date'] : null;
        $instructor = isset($options['instructor']) ? $options['instructor'] : null;
        $student = isset($options['student']) ? $options['student'] : null;
        $mettingPoint = isset($options['metting-point']) ? $options['metting-point'] : null;
        $dateSlug = $date->format('Y_m_d');
        if ($student instanceof User) {
           $studentCourses = $this->em->getRepository(Course::class)->findCourseByDateAndUser($student);
        }


        if ($instructor instanceof User) {
            $instructorCourses = $this->em->getRepository(Course::class)->findOneBy([
                'courseDate' => $date,
                'instructor' => $instructor,
            ]);
        }
        $attr = [
            'class' => 'course-hour',
            'data-instructor' => $instructor->getId(),
            'data-datetime' => $date->format('Y/m/d'),
            'data-user' => $student->getId(),
            'data-metting-point' => $mettingPoint->getId(),
        ];
       // dd($this->setdayData(Hours::HOURS_9, $date, $studentCourses));
        $builder
            ->add('name', HiddenType::class, [
                'mapped' => false,
                'data' => sprintf('%s %s', $this->translator->trans(self::DAYS[$date->format('N')]), $date->format('Y/m/d')),
                'label' => sprintf('%s %s', $this->translator->trans(self::DAYS[$date->format('N')]), $date->format('Y/m/d'))

            ])
            ->add($dateSlug . '_hour_7', CheckboxType::class, [
                'mapped' => false,
                'label' => sprintf('%d : 00', Hours::HOURS_7),
                'attr' => array_merge($attr, ['data-hour' => Hours::HOURS_7], $this->setdayData(Hours::HOURS_7, $date, $studentCourses))

            ])->add($dateSlug . '_hour_8', CheckboxType::class, [
                'mapped' => false,
                'label' => sprintf('%d : 00', Hours::HOURS_8),
                'attr' => array_merge($attr, ['data-hour' => Hours::HOURS_8], $this->setdayData(Hours::HOURS_8, $date, $studentCourses))

            ])->add($dateSlug . '_hour_9', CheckboxType::class, [
                'mapped' => false,
                'label' => sprintf('%d : 00', Hours::HOURS_9),
                'attr' => array_merge($attr, ['data-hour' => Hours::HOURS_9], $this->setdayData(Hours::HOURS_9, $date, $studentCourses))

            ])->add($dateSlug . '_hour_10', CheckboxType::class, [
                'mapped' => false,
                'label' => sprintf('%d : 00', Hours::HOURS_10),
                'attr' => array_merge($attr, ['data-hour' => Hours::HOURS_10], $this->setdayData(Hours::HOURS_10, $date, $studentCourses))

            ])->add($dateSlug . '_hour_11', CheckboxType::class, [
                'mapped' => false,
                'label' => sprintf('%d : 00', Hours::HOURS_11),
                'attr' => array_merge($attr, ['data-hour' => Hours::HOURS_11], $this->setdayData(Hours::HOURS_11, $date, $studentCourses))

            ])->add($dateSlug . '_hour_12', CheckboxType::class, [
                'mapped' => false,
                'label' => sprintf('%d : 00', Hours::HOURS_12),
                'attr' => array_merge($attr, ['data-hour' => Hours::HOURS_12], $this->setdayData(Hours::HOURS_12, $date, $studentCourses))

            ])->add($dateSlug . '_hour_13', CheckboxType::class, [
                'mapped' => false,
                'label' => sprintf('%d : 00', Hours::HOURS_13),
                'attr' => array_merge($attr, ['data-hour' => Hours::HOURS_13], $this->setdayData(Hours::HOURS_13, $date, $studentCourses))

            ])->add($dateSlug . '_hour_14', CheckboxType::class, [
                'mapped' => false,
                'label' => sprintf('%d : 00', Hours::HOURS_14),
                'attr' => array_merge($attr, ['data-hour' => Hours::HOURS_14], $this->setdayData(Hours::HOURS_14, $date, $studentCourses))

            ])->add($dateSlug . '_hour_15', CheckboxType::class, [
                'mapped' => false,
                'label' => sprintf('%d : 00', Hours::HOURS_15),
                'attr' => array_merge($attr, ['data-hour' => Hours::HOURS_15], $this->setdayData(Hours::HOURS_15, $date, $studentCourses))

            ])->add($dateSlug . '_hour_7', CheckboxType::class, [
                'mapped' => false,
                'label' => sprintf('%d : 00', Hours::HOURS_16),
                'attr' => array_merge($attr, ['data-hour' => Hours::HOURS_16], $this->setdayData(Hours::HOURS_16, $date, $studentCourses))

            ])->add($dateSlug . '_hour_17', CheckboxType::class, [
                'mapped' => false,
                'label' => sprintf('%d : 00', Hours::HOURS_17),
                'attr' => array_merge($attr, ['data-hour' => Hours::HOURS_17], $this->setdayData(Hours::HOURS_17, $date, $studentCourses))

            ])->add($dateSlug . '_hour187', CheckboxType::class, [
                'mapped' => false,
                'label' => sprintf('%d : 00', Hours::HOURS_18),
                'attr' => array_merge($attr, ['data-hour' => Hours::HOURS_18], $this->setdayData(Hours::HOURS_18, $date, $studentCourses))

            ])->add($dateSlug . '_hour_19', CheckboxType::class, [
                'mapped' => false,
                'label' => sprintf('%d : 00', Hours::HOURS_19),
                'attr' => array_merge($attr, ['data-hour' => Hours::HOURS_19], $this->setdayData(Hours::HOURS_19, $date, $studentCourses))

            ])->add($dateSlug . '_hour_20', CheckboxType::class, [
                'mapped' => false,
                'label' => sprintf('%d : 00', Hours::HOURS_20),
                'attr' => array_merge($attr, ['data-hour' => Hours::HOURS_20], $this->setdayData(Hours::HOURS_20, $date, $studentCourses))

            ]);
    }

    /**
     * @param $hour
     * @param \DateTime $date
     * @param array $courses
     * @return array
     */
    public  function setdayData($hour, \DateTime $date,  array $courses){
        foreach ($courses as $course){
            $currentDate = $course->getCourseDate();
            $courseDate = $date->setTime($hour, 0, 0);
            if($courseDate == $currentDate ){
                return [
                    'checked' => 'checked',
                    'data-value' => 1,
                ];
            }
        }
        return [
            'data-value' => 0,
        ];
    }
    /**
     * @param $hour
     * @param \DateTime $date
     * @param array $courses
     * @return array
     */
    public  function setInstructordayData($hour, \DateTime $date,  array $courses){
        foreach ($courses as $course){
            $currentDate = $course->getCourseDate();
            $courseDate = $date->setTime($hour, 0, 0);
            if($courseDate == $currentDate ){
                return [
                    'checked' => 'checked',
                    'data-value' => 1,
                ];
            }
        }
        return [
            'data-value' => 0,
        ];
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Day::class,
            'day_name' => false,
            'date' => false,
            'instructor' => false,
            'student' => false,
            'metting-point' => false,
        ]);
    }
}
