<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Form\MenuType;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
// Needed packages for Lock
use Symfony\Component\Lock\Key;
use Symfony\Component\Lock\Lock;
// Allow to store lock state on MySQL using Doctrine
use Symfony\Component\Lock\Store\DoctrineDbalStore;

// Instanciate elements
function get_lock(string $name)
{
    // Store used to store the lock state
    $store = new DoctrineDbalStore($_ENV['DATABASE_URL']);
    // Create table if not exists
    try {
        $store->createTable();
    } catch (\Exception $e) {
        // Table already exists
    }
    $key = new Key($name); // key used to identify the lock
    $lock = new Lock(
        $key,
        $store, // store used to store the lock state
        20,  // time before lock is released
        false // autoRelease if instance is destroyed
    );
    return $lock;
}

#[Route('/admin/menu')]
class CRUDMenuController extends AbstractController
{
    #[Route('/', name: 'app_admin_menu_index', methods: ['GET'])]
    public function index(MenuRepository $menuRepository): Response
    {
        return $this->render('admin_menu/index.html.twig', [
            'menus' => $menuRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_menu_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Add current logged user as author
            // $user = $entityManager->getRepository(User::class)->findOneBy(['username' =>
            // $this->getUser()->getUserIdentifier()]);
            $entityManager->persist($menu);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_menu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_menu/new.html.twig', [
            'menu' => $menu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_menu_show', methods: ['GET'])]
    public function show(Menu $menu): Response
    {
        return $this->render('admin_menu/show.html.twig', [
            'menu' => $menu,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_menu_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Menu $menu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get lock for this article editing
            $edit_lock = get_lock("menu_edit_".$menu->getId());

            // Case lock is acquired from another user, request will wait
            // Case not locked, lock it to avoid concurrent editing
            if(!$edit_lock->isAcquired()) {
                $edit_lock->acquire(true);
                $entityManager->flush();
                // Case no release, wait for ttl
                $edit_lock->release();

            }
            return $this->redirectToRoute('app_admin_menu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_menu/edit.html.twig', [
            'menu' => $menu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_menu_delete', methods: ['POST'])]
    public function delete(Request $request, Menu $menu, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$menu->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($menu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
