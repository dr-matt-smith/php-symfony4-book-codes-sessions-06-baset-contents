<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

use App\Entity\Product;

/**
 * @Route("/basket", name="basket_")
 */
class BasketController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $template = 'basket/index.html.twig';
        $args = [];
        return $this->render($template, $args);
    }

    /**
     * @Route("/clear", name="clear")
     */
    public function clear()
    {
        $session = new Session();
        $session->remove('basket');

        return $this->redirectToRoute('basket_index');
    }

    /**
     * @Route("/add/{id}", name="add")
     */
    public function addToBasket(Product $product)
    {
        // default - new empty array
        $products = [];

        // if 'products' array in session, retrieve and store in $products
        $session = new Session();
        if ($session->has('basket')) {
            $products = $session->get('basket');
        }

        // get ID of product
        $id = $product->getId();

        // only try to add to array if not already in the array
        if (!array_key_exists($id, $products)) {
            // append $product to our list
            $products[$id] = $product;

            // store updated array back into the session
            $session->set('basket', $products);
        }

        return $this->redirectToRoute('basket_index');
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function deleteAction(int $id)
    {
        // default - new empty array
        $products = [];

        // if 'products' array in session, retrieve and store in $products
        $session = new Session();
        if ($session->has('basket')) {
            $products = $session->get('basket');
        }

        // only try to remove if it's in the array
        if (array_key_exists($id, $products)) {
            // remove entry with $id
            unset($products[$id]);

            if (sizeof($products) < 1) {
                return $this->redirectToRoute('basket_clear');
            }

            // store updated array back into the session
            $session->set('basket', $products);
        }
        return $this->redirectToRoute('basket_index');
    }
}
