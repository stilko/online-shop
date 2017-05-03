<?php
 namespace AppBundle\Service;


use Doctrine\ORM\EntityManager;

class PriceCalculator
{
    /**
     * @var EntityManager
     */
    protected $emanager;
    protected $promotion;

    /**
     * PriceCalculator constructor.
     * @param EntityManager $emanager
     */
    public function __construct(EntityManager $emanager)
    {
        $this->emanager = $emanager;
       $this->initMaxPromotion();
    }

    /**
     * @param $price
     */

    public function calculate($price){
    return $price - $price*($this->promotion / 100);
    }

    private function initMaxPromotion()
    {
        $this->promotion = $this->emanager->getRepository('AppBundle:Promotions')->fetchBiggestPromotion();
    }
}