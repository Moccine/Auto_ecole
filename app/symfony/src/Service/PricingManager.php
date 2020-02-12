<?php

namespace App\Service;

use App\Utils\DateHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PricingManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var DateHelper
     */
    private $dateHelper;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * PricingManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param DateHelper             $dateHelper
     */
    public function __construct(EntityManagerInterface $entityManager, DateHelper $dateHelper, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->dateHelper = $dateHelper;
        $this->translator = $translator;
    }

    /**
     * @param Product        $product
     * @param Domain         $domain
     * @param \DateTime|null $fromDate
     * @param \DateTime      $toDate
     *
     * @return float|int|null
     *
     * @throws \Exception
     */
    public function getBookingPrice(Product $product, Domain $domain, \DateTime $fromDate = null, \DateTime $toDate = null)
    {
        try {
            $week = (null == $fromDate || null == $toDate) ? $product->getWeekMin() : $this->dateHelper->getNumberOfWeek($fromDate, $toDate);
            $currentDate = new \DateTime();
            $currentDate = $currentDate->format('Y') === '2019' ? new \DateTime('2020-01-01') : $currentDate;
            $year = (null === $fromDate) ? $currentDate->format('Y') : $fromDate->format('Y');
            /** @var ProductPricing $productPricing */
            $productPricing = $this->entityManager->getRepository(ProductPricing::class)->findPriceByProduct($product, $week, $year);
            $price = $productPricing->getPriceByCurrency($domain->getCurrency());
        } catch (\Exception $e) {
            throw new \Exception($this->translator->trans('booking.error.calculate_product_price'));
        }

        return $price * $week ?? 0;
    }
}
