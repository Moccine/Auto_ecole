<?php

namespace App\Twig;

use App\Entity\Course;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CourseExtension extends AbstractExtension
{
    /**
     * @var TranslatorInterface  $translator
     */
    private $translator;

    /**
     * CourseExtension constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('price', [$this, 'formatPrice']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('course_date', [$this, 'displayCourseDate']),
        ];
    }

    public function displayCourseDate(Course $course)
    {
        $date = $course->getCourseDate();

        return  sprintf('%s H - %s %s %s',
            $date->format('H'),
            $this->translator->trans(Course::DAYS[$date->format('N')]),
            $date->format('j'),
            $this->translator->trans(Course::MONTHS[$date->format('n')]));
    }

    public function formatPrice($number, $decimals = 2, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        ;

        return sprintf('%s %s', $price, $this->translator->trans('app.devise'));
    }
}
