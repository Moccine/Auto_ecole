<?php

namespace App\Utils;

use Symfony\Contracts\Translation\TranslatorInterface;

class DateHelper
{
    private $translator;

    /**
     * DateHelper constructor.
     *
     * @param $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     *
     * @return float
     *
     * @throws \Exception
     */
    public function getNumberOfWeek(\DateTime $fromDate, \DateTime $toDate)
    {
        if ($toDate <= $fromDate) {
            throw new \Exception($this->translator->trans('api.product.error.incalculable_number_of_weeks'));
        }

        return floor(($toDate->diff($fromDate)->days + 1) / 7);
    }
}
