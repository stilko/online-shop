<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CartProduct
 *
 * @ORM\Table(name="cart_product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CartProductRepository")
 */
class CartProduct
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @return Cart
     */
    public function getCart(): Cart
    {
        return $this->cart;
    }

    /**
     * @param Cart $cart
     * @return $this
     */
    public function setCart(Cart $cart)
    {
        $this->cart = $cart;
        return $this;

    }
//    /**
//     * Get product
//     *
//     * @return \stdClass
//     */
//    public function getProduct()
//    {
//        return $this->product;
//    }
//
//    /**
//     * Set product
//     *
//     * @param \stdClass $product
//     *
//     * @return CartProduct
//     */
//    public function setProduct($product)
//    {
//        $this->product = $product;
//
//        return $this;
//    }
//
    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     *
     * @param Product $product
     * @return $this
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @var Cart
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Cart")
     * @ORM\JoinColumn(name="cart_id", referencedColumnName="id")
     */
    private $cart;

    /**
     * @var Product
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="cartProduct",fetch="EAGER")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;


    /**
     * @var int
     *
     * @ORM\Column(name="qty", type="integer")
     */
    private $qty;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set qty
     *
     * @param integer $qty
     *
     * @return CartProduct
     */
    public function setQty($qty)
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * Get qty
     *
     * @return int
     */
    public function getQty()
    {
        return $this->qty;
    }
}

