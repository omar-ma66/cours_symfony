<?php

namespace App\Controller\Admin;

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
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkTo(SomeCrudController::class, 'The Label', 'fas fa-list');
    }
}
