<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\EventListener\ValidateRequestListener;
use Symfony\Component\Routing\Attribute\Route;

final class BookController extends AbstractController
{

// ----------------------------------------------------------------------------------------------

    #[Route('/livres/test/{id}', name: 'app_test_relation', requirements: ['id' => '\d+'])]
    public function affiche(AuthorRepository $authorRepository, Author $author,int $id): Response
    {

          $auteur =    $authorRepository->find($id);
                        
          dd($auteur->getFirstName(),$auteur->getBooks()->toArray()[0]->getTitle());

                            $auteur->delete();
        // $books = $author->getBooks()->toArray();
        // // dd($author,$books);
        // //dd($books[0]->getCategories()[0]->getName());

        // if (empty($books))
        //     return new Response("cet Auteur na pas encore de livre ");

        // $livre1 =      $books[0];
        // $titre = $livre1->getTitle();
        // $categorie   = $livre1->getCategories();
        // $nom = "";
        // foreach ($categorie as $categorie1) {
        //     $nom      .= ";" . $categorie1->getName();
        // }

        // $out = $titre . ";" . $nom;



        // return new Response($out);
    }
// ----------------------------------------------------------------------------------------------

    #[Route('/livres/ajout', name: 'app_book_add')]
    public function add(EntityManagerInterface $em, CategoryRepository $categoryRepository)
    {
        $auteur1 = new Author();
        $auteur1->setFirstName("Antoine");
        $auteur1->setLastName("De Saint-Exupéry");


        $auteur2 = new Author();
        $auteur2->setFirstName("Ray");
        $auteur2->setLastName("Bradbury");

        $allCategory = $categoryRepository->findAll();

        //  2 classique 0 sf 

        $livre1 = new Book();
        $livre1->setTitle("Le Petit Prince");
        $livre1->setStock(4);
        $livre1->setDescription('Un conte poétique et philosophique.');
        $livre1->setAuthor($auteur1);
        $livre1->addCategory($allCategory[2]);

        $livre2 = new Book();
        $livre2->setTitle("Fahrenheit 451");
        $livre2->setStock(1);
        $livre2->setDescription("Dans un futur où les livres sont interdits...");
        $livre2->setAuthor($auteur2);
        $livre2->addCategory($allCategory[0]);
        $livre2->addCategory($allCategory[1]);

        $em->persist($auteur1);
        $em->persist($auteur2);
        $em->persist($livre1);
        $em->persist($livre2);

        $em->flush();

        return new Response("Livres ajoutes avec succes");
    }
// ----------------------------------------------------------------------------------------------

    #[Route('/livres/init', name: 'app_book_init')]
    public function init(EntityManagerInterface $em)
    {
        $herbert = new Author();
        $herbert->setFirstName('Frank');
        $herbert->setLastName('Herbert');

        $orwell = new Author();
        $orwell->setFirstName('George');
        $orwell->setLastName('Orwell');


        $asimov = new Author();
        $asimov->setFirstName('Isaac');
        $asimov->setLastName('Asimov');

        // 2. Créer des catégories
        $sf = new Category();
        $sf->setName('Science-Fiction');

        $dystopie = new Category();
        $dystopie->setName('Dystopie');

        $classique = new Category();
        $classique->setName('Classique');

        $dune = new Book();
        $dune->setTitle('Dune');
        $dune->setDescription('Un chef-d\'œuvre de la science-fiction.');
        $dune->setStock(5);
        $dune->setAuthor($herbert);
        $dune->addCategory($sf);
        $dune->addCategory($classique);



        $book1984  = new Book();
        $book1984->setTitle('1984');
        $book1984->setDescription('Un roman d\'anticipation de George Orwell.');
        $book1984->setStock(3);
        $book1984->setAuthor($orwell);
        $book1984->addCategory($dystopie);
        $book1984->addCategory($classique);



        $fondation = new Book();
        $fondation->setTitle('Fondation');
        $fondation->setDescription('Le début de la saga Fondation.');
        $fondation->setStock(2);
        $fondation->setAuthor($asimov);
        $fondation->addCategory($sf);


        $em->persist($herbert);
        $em->persist($orwell);
        $em->persist($asimov);

        $em->persist($sf);
        $em->persist($dystopie);
        $em->persist($classique);

        $em->persist($dune);
        $em->persist($book1984);
        $em->persist($fondation);

        $em->flush();

        return new Response("Les Données sont enregistrées ");
    }

// ----------------------------------------------------------------------------------------------

    #[route('/livres', name: 'app_book_index')]
    public function index(BookRepository $bookRepository,CategoryRepository $categoryRepository): Response
    {
        $categories  = $categoryRepository->findAll();
        $books =$bookRepository->findAll();

        return $this->render('book/index.html.twig', [
            'books' => $books,
            'categories'=>$categories
        ]);
    }
    // ---------------------------------------------------------------------------------------

    #[route('/livres/{id}', name: 'app_book_show', requirements: ['id' => '\d{1,4}'], methods: ['GET', 'POST'])]
    public function show(int $id,BookRepository $bookRepository): Response
    {
      
    $book = $bookRepository->find($id);
                if(!$book)
                    {
                       throw $this->createNotFoundException("Le livre num $id n'existe pas ");
                    }
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }
    // ---------------------------------------------------------------------------------------

    // ---------------------------------------------------------------------------------------


    #[route('/livres/page/{page}', name: 'app_book_list', requirements: ['page' => '\d+'], methods: ['GET'])]
    public function list(int $page = 1): Response
    {

        return new Response("Page numéro $page de la liste des livres ");
    }
    // ---------------------------------------------------------------------------------------

    #[route('/auteur/{name}', name: 'app_author_show')]
    public function showAuteur(string $name): Response
    {

        return new Response("Page de l' auteur $name ");
    }
    // ---------------------------------------------------------------------------------------

    #[route('/categorie/{categorie}/livre/{id}', name: 'app_book_by_category')]
    public function showByCategory(string $categorie, int $id): Response
    {

        return new Response("vous avez choisi la catégorie [ $categorie ] numero de livre [$id]");
    }
    // ---------------------------------------------------------------------------------------
     // ---------------------------------------------------------------------------------------

     #[route('/livres/categories/{id}',name:'app_select_categorie')]
    public function bycategories(int $id,CategoryRepository $categoryRepository,BookRepository $bookRepository)
    {
             $mycategory =   $categoryRepository->find($id);
                       
                $livres= $bookRepository->mafunc(  $mycategory );

               return $this->render('book/categories.html.twig',['livres'=>$livres,'categories'=>$mycategory]);
    }
    // ---------------------------------------------------------------------------------------
    #[route('/livres/disponible/{id}',name:'app_book_disponible')]
    public function dispo(int $id,BookRepository $bookRepository)
    {
            $books = $bookRepository->findbyStock($id);
         
                      if(!$books)
                        throw $this->createNotFoundException("Aucun livre trouvé");
             return $this->render('book/disponible.html.twig',['books'=>$books ,"taille"=>$id]);       
    }

    // ---------------------------------------------------------------------------------------
    #[route('/auteur/{id}/livres',name:'app_book_by_author',requirements:['id'=>'\d+'])]
    public function findAuteur(int $id,BookRepository $bookRepository ,AuthorRepository $authorRepository)
    {
  
        $auteur = $authorRepository->find($id);

  
        if(!$auteur)
            throw $this->createNotFoundException("l'auteur n'est pas trouver");

        $books = $bookRepository->findByAuteur($auteur);
       
         if(!$books)
            throw $this->createNotFoundException("pas de livre trouver");
        
        return  $this->render('book/auteur.html.twig',  
         [
            "auteur"=>$auteur,
            "livres"=>$books
         ]);
    }

    // ---------------------------------------------------------------------------------------

    #[route('/livres/titre/{slug}',name:'app_titre_book')]
    public function byTitre(string $slug,BookRepository $bookRepository,AuthorRepository $authorRepository):Response
    {
        
         $auteur =   $authorRepository->find(1) ;
        $livres  = $bookRepository->findBy(['author'=>$auteur],['title'=>'ASC']);
        $book = $bookRepository->findOneBy(['title'=>$slug],['id'=>'ASC']);

    //    dd($book,$auteur,$livres);
                if(!$book)
                    throw $this->createNotFoundException('pas de livre avec ce titre trouver');

        return $this->redirectToRoute('app_book_show',['id'=>$book->getId()]);
    }

    // ---------------------------------------------------------------------------------------
// php bin/console dbal:run-sql "SELECT * FROM book WHERE title = 'Mon Beau Livre'"

}
