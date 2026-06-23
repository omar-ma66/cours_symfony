<?php

namespace App\Controller\Admin;
use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Borrowing;
use App\Entity\Category;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\BorrowingRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractDashboardController
{

     
public function __construct(
    private BookRepository $bookRepository,
    private UserRepository $userRepository,
    private BorrowingRepository $borrowingRepository,
) {}

public function index(): Response
{
    return $this->render('admin/dashboard.html.twig', [
        'bookCount'        => $this->bookRepository->count([]),
        'userCount'        => $this->userRepository->count([]),
        'activeBorrowings' => $this->borrowingRepository->count(['returnedAt' =>null]),
    ]);
}   



    public function configureDashboard(): Dashboard
    {
  
    return Dashboard::new()
        ->setTitle('📚 Bibliotech — Administration')
        ->setFaviconPath('favicon.ico')
        ->renderContentMaximized();  // Le contenu utilise toute la largeur
     
    }

  public function configureMenuItems(): iterable
{
    yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-tachometer-alt');

    yield MenuItem::section('Catalogue');
    yield MenuItem::linkTo(BookCrudController::class, 'Livres', 'fa fa-book');
    yield MenuItem::linkTo(AuthorCrudController::class, 'Auteurs', 'fa fa-pen-nib');
    yield MenuItem::linkTo(CategoryCrudController::class, 'Catégories', 'fa fa-tag');

    yield MenuItem::section('Utilisateurs & Emprunts');
    yield MenuItem::linkTo(UserCrudController::class, 'Utilisateurs', 'fa fa-users');
    yield MenuItem::linkTo(BorrowingCrudController::class, 'Emprunts', 'fa fa-book-reader');

    yield MenuItem::section('Navigation');
    yield MenuItem::linkToRoute('Retour au site', 'fa fa-arrow-left', 'app_home');
}
}
