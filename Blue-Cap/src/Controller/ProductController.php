<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Entity\CategoryProduct;
use App\Repository\ProductRepository;
use App\Repository\CategoryProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/admin", name="product_admin", methods={"GET"})
     */
    public function admin(ProductRepository $productRepository): Response
    {
        return $this->render('product/admin.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $imageProduct = $form->get('imageProduct')->getData();
            if ($imageProduct) {
            $originalFilename = pathinfo($imageProduct->getClientOriginalName(),
           PATHINFO_FILENAME);
          
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageProduct->guessExtension();
            
            try {
            $imageProduct->move(
            $this->getParameter('images_directory'),
            $newFilename
            );
            } catch (FileException $e) {
            }
            $product->setImageProduct($newFilename);
            }
           

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageProduct = $form->get('imageProduct')->getData();
            if ($imageProduct) {
            $originalFilename = pathinfo($imageProduct->getClientOriginalName(),
           PATHINFO_FILENAME);
          
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageProduct->guessExtension();
            
            try {
            $imageProduct->move(
            $this->getParameter('images_directory'),
            $newFilename
            );
            } catch (FileException $e) {
            }
            $product->setImageProduct($newFilename);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_admin', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/", name="product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository, CategoryProductRepository $categoryProductRepository, Request $request): Response
    {
        //Récupération du nombre d'élements par page
        $limit = 2;

        //Récupération du numéro de la page
        $page = (int)$request->query->get("page", 1);

        //récupération des filtres
        $filters = $request->get("product_category");
        
        
  

        //Récuperation des produits de la page
        $products = $productRepository->getPaginatedProduct($page, $limit, $filters);
        
        //Récupération du nobre total d'annonces
        $total = $productRepository->getTotalProduct($filters);
        dd($total);
        $category_products = $categoryProductRepository->findAll();

        

        

       // vérification si on a une requête ajax
        if($request->get("ajax")) {
            return "ok";
            
        }
        
      

        
        

        return $this->render('product/index.html.twig', compact('products', 'total', 'limit', 'page', 'filters', 'category_products')
    );





        //récupération des filtres
        // $filters = $request->get("product_category");
        

        
        

        // vérification si on a une requête ajax
        // if($request->get("ajax")) {
        //     return $this->render('product/index.html.twig', [
        //         'products' => $productRepository->getFilters($filters),
        //         'category_products' => $categoryProductRepository->findAll(),
        //     ]);
        //     dump($filters);
        // }
        
        // else{
       
        // return $this->render('product/index.html.twig', [
        //     'products' => $productRepository->findAll(),
        //     'category_products' => $categoryProductRepository->findAll(),
        

        // ]);
    // }
    }
}
