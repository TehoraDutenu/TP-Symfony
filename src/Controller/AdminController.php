<?php

namespace App\Controller;

use App\Entity\Rent;
use App\Entity\User;
use App\Form\UserType;
use App\Entity\TypeRent;
use App\Entity\UserInfo;
use App\Repository\RentRepository;
use App\Repository\UserRepository;
use App\Repository\TypeRentRepository;
use App\Repository\UserInfoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin')]
class AdminController extends AbstractController
{
    private UserPasswordHasherInterface $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    // - ADMIN
    #[Route('/admin', name: 'app_admin_index', methods: ['GET'])]
    public function indexAdmin(UserRepository $userRepository): Response
    {
        $admins = $userRepository->getUsersByRole('ROLE_ADMIN');
        return $this->render('user/admin/index.html.twig', [
            'admins' => $admins
        ]);
    }

    // -- Ajouter
    #[Route('/admin/new', name: 'app_admin_new', methods: ['GET', 'POST'])]
    public function newAdmin(Request $request, UserRepository $userRepository, UserInfoRepository $userInfoRepository): Response
    {
        $user = new User();
        $form = $this->createForm(AdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userInfo = new UserInfo();
            $userInfo->setLastname($form->get('UserInfo')->get('lastname')->getData());
            $userInfo->setFirstname($form->get('UserInfo')->get('firstname')->getData());
            $userInfo->setAddress($form->get('UserInfo')->get('address')->getData());
            $userInfo->setZipCode($form->get('UserInfo')->get('zipcode')->getData());
            $userInfo->setCity($form->get('UserInfo')->get('city')->getData());
            $userInfo->setCountry($form->get('UserInfo')->get('country')->getData());
            $userInfo->setPhone($form->get('UserInfo')->get('phone')->getData());

            $userInfoRepository->save($userInfo, true);
            $user->setUserInfo($userInfoRepository->find($userInfo->getId()));

            $user->setRoles(['ROLE_ADMIN']);
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $user->setPassword($this->encoder->hashPassword($user, $plainPassword));
            }
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/admin/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // -- Afficher
    #[Route('/admin/{id}', name: 'app_admin_show', methods: ['GET'])]
    public function showAdmin(User $user): Response
    {
        return $this->render('user/admin/show.html.twig', [
            'user' => $user,
        ]);
    }

    // -- Modifier
    #[Route('/admin/{id}/edit', name: 'app_admin_edit', methods: ['GET', 'POST'])]
    public function editAdmin(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(AdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $user->setPassword($this->encoder->hashPassword($user, $plainPassword));
            }
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/admin/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // -- Supprimer
    #[Route('/admin/{id}', name: 'app_admin_delete', methods: ['POST'])]
    public function deleteAdmin(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
    }


    // - OFFICE
    #[Route('/office', name: 'app_office_index', methods: ['GET'])]
    public function indexOffice(UserRepository $userRepository): Response
    {
        $offices = $userRepository->getUsersByRole('ROLE_OFFICE');
        return $this->render('user/office/index.html.twig', [
            'offices' => $offices
        ]);
    }

    // -- Ajouter
    #[Route('/office/new', name: 'app_office_new', methods: ['GET', 'POST'])]
    public function newOffice(Request $request, UserRepository $userRepository, UserInfoRepository $userInfoRepository): Response
    {
        $user = new User();
        $form = $this->createForm(OfficeType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userInfo = new UserInfo();
            $userInfo->setLastname($form->get('UserInfo')->get('lastname')->getData());
            $userInfo->setFirstname($form->get('UserInfo')->get('firstname')->getData());
            $userInfo->setAddress($form->get('UserInfo')->get('address')->getData());
            $userInfo->setZipCode($form->get('UserInfo')->get('zipcode')->getData());
            $userInfo->setCity($form->get('UserInfo')->get('city')->getData());
            $userInfo->setCountry($form->get('UserInfo')->get('country')->getData());
            $userInfo->setPhone($form->get('UserInfo')->get('phone')->getData());

            $userInfoRepository->save($userInfo, true);
            $user->setUserInfo($userInfoRepository->find($userInfo->getId()));

            $user->setRoles(['ROLE_OFFICE']);
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $user->setPassword($this->encoder->hashPassword($user, $plainPassword));
            }
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_office_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/office/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // -- Afficher
    #[Route('/office/{id}', name: 'app_office_show', methods: ['GET'])]
    public function showOffice(User $user): Response
    {
        return $this->render('user/office/show.html.twig', [
            'user' => $user,
        ]);
    }

    // -- Modifier
    #[Route('/office/{id}/edit', name: 'app_office_edit', methods: ['GET', 'POST'])]
    public function editOffice(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(OfficeType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $user->setPassword($this->encoder->hashPassword($user, $plainPassword));
            }
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_office_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/office/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // -- Supprimer
    #[Route('/office/{id}', name: 'app_office_delete', methods: ['POST'])]
    public function deleteOffice(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_office_index', [], Response::HTTP_SEE_OTHER);
    }



    // - PROPRIÉTAIRE
    #[Route('/proprietaire', name: 'app_proprietaire_index', methods: ['GET'])]
    public function indexProprietaire(UserRepository $userRepository): Response
    {
        $proprietaires = $userRepository->getUsersByRole('ROLE_PROPRIETAIRE');
        return $this->render('user/proprietaire/index.html.twig', [
            'proprietaires' => $proprietaires
        ]);
    }

    // -- Ajouter
    #[Route('/proprietaire/new', name: 'app_proprietaire_new', methods: ['GET', 'POST'])]
    public function newProprietaire(Request $request, UserRepository $userRepository, UserInfoRepository $userInfoRepository): Response
    {
        $user = new User();
        $form = $this->createForm(ProprietaireType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userInfo = new UserInfo();
            $userInfo->setLastname($form->get('UserInfo')->get('lastname')->getData());
            $userInfo->setFirstname($form->get('UserInfo')->get('firstname')->getData());
            $userInfo->setAddress($form->get('UserInfo')->get('address')->getData());
            $userInfo->setZipCode($form->get('UserInfo')->get('zipcode')->getData());
            $userInfo->setCity($form->get('UserInfo')->get('city')->getData());
            $userInfo->setCountry($form->get('UserInfo')->get('country')->getData());
            $userInfo->setPhone($form->get('UserInfo')->get('phone')->getData());

            $userInfoRepository->save($userInfo, true);
            $user->setUserInfo($userInfoRepository->find($userInfo->getId()));

            $user->setRoles(['ROLE_PROPRIETAIRE']);
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $user->setPassword($this->encoder->hashPassword($user, $plainPassword));
            }
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_proprietaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/proprietaire/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // -- Afficher
    #[Route('/proprietaire/{id}', name: 'app_proprietaire_show', methods: ['GET'])]
    public function showProprietaire(User $user): Response
    {
        return $this->render('user/proprietaire/show.html.twig', [
            'user' => $user,
        ]);
    }

    // -- Modifier
    #[Route('/proprietaire/{id}/edit', name: 'app_proprietaire_edit', methods: ['GET', 'POST'])]
    public function editProprietaire(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(ProprietaireType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $user->setPassword($this->encoder->hashPassword($user, $plainPassword));
            }
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_proprietaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/proprietaire/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // -- Supprimer
    #[Route('/proprietaire/{id}', name: 'app_proprietaire_delete', methods: ['POST'])]
    public function deleteProprietaire(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_proprietaire_index', [], Response::HTTP_SEE_OTHER);
    }


    // - LOCATAIRE
    #[Route('/locataire', name: 'app_locataire_index', methods: ['GET'])]
    public function indexLocataire(UserRepository $userRepository): Response
    {
        $locataires = $userRepository->getUsersByRole('ROLE_LOCATAIRE');
        return $this->render('user/locataire/index.html.twig', [
            'locataires' => $locataires
        ]);
    }

    // -- Ajouter
    #[Route('/locataire/new', name: 'app_locataire_new', methods: ['GET', 'POST'])]
    public function newLocataire(Request $request, UserRepository $userRepository, UserInfoRepository $userInfoRepository): Response
    {
        $user = new User();
        $form = $this->createForm(LocataireType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userInfo = new UserInfo();
            $userInfo->setLastname($form->get('UserInfo')->get('lastname')->getData());
            $userInfo->setFirstname($form->get('UserInfo')->get('firstname')->getData());
            $userInfo->setAddress($form->get('UserInfo')->get('address')->getData());
            $userInfo->setZipCode($form->get('UserInfo')->get('zipcode')->getData());
            $userInfo->setCity($form->get('UserInfo')->get('city')->getData());
            $userInfo->setCountry($form->get('UserInfo')->get('country')->getData());
            $userInfo->setPhone($form->get('UserInfo')->get('phone')->getData());

            $userInfoRepository->save($userInfo, true);
            $user->setUserInfo($userInfoRepository->find($userInfo->getId()));

            $user->setRoles(['ROLE_LOCATAIRE']);
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $user->setPassword($this->encoder->hashPassword($user, $plainPassword));
            }
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_locataire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/locataire/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // -- Afficher
    #[Route('/locataire/{id}', name: 'app_locataire_show', methods: ['GET'])]
    public function showLocataire(User $user): Response
    {
        return $this->render('user/locataire/show.html.twig', [
            'user' => $user,
        ]);
    }

    // -- Modifier
    #[Route('/locataire/{id}/edit', name: 'app_locataire_edit', methods: ['GET', 'POST'])]
    public function editLocataire(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(LocataireType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $user->setPassword($this->encoder->hashPassword($user, $plainPassword));
            }
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_locataire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/locataire/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // -- Supprimer
    #[Route('/locataire/{id}', name: 'app_locataire_delete', methods: ['POST'])]
    public function deleteLocataire(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_locataire_index', [], Response::HTTP_SEE_OTHER);
    }


    // - LOCATIONS
    // - Afficher les locations par type
    #[Route('/rent', name: 'app_rent_index', methods: ['GET'])]
    public function indexRent(RentRepository $rentRepository, TypeRentRepository $typeRentRepository): Response
    {
        // - Récupérer la liste des types
        $types = $typeRentRepository->findAll();
        $rents = $rentRepository->getRentByType($types);

        return $this->render('rent/index.html.twig', [
            'rents' => $rents,
        ]);
    }

    // -- Afficher une location
    #[Route('/rent/{id}', name: 'app_rent_show', methods: ['GET'])]
    public function showRent(Rent $rent): Response
    {
        return $this->render('user/rent/show.html.twig', [
            'rent' => $rent,
        ]);
    }

    // -- Ajouter
    #[Route('/rent/new', name: 'app_rent_new', methods: ['GET', 'POST'])]
    public function newRent(Request $request, RentRepository $rentRepository, TypeRentRepository $typeRentRepository): Response
    {
        $rent = new Rent();
        $form = $this->createForm(RentType::class, $rent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typeRent = new TypeRent();
            $typeRent->setLabel($form->get('TypeRent')->get('label')->getData());
            $typeRent->setImagePath($form->get('TypeRent')->get('imagePath')->getData());
            $typeRent->setPrice($form->get('TypeRent')->get('price')->getData());

            $typeRentRepository->save($typeRent, true);
            $rent->setTypeRent($typeRentRepository->find($typeRent->getId()));

            $rentRepository->save($rent, true);

            return $this->redirectToRoute('app_rent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/rent/new.html.twig', [
            'rent' => $rent,
            'form' => $form,
        ]);
    }

    // -- Modifier
    #[Route('/rent/{id}/edit', name: 'app_rent_edit', methods: ['GET', 'POST'])]
    public function editRent(Request $request, Rent $rent, RentRepository $rentRepository, TypeRentRepository $typeRentRepository): Response
    {
        $form = $this->createForm(RentType::class, $rent);
        $form->handleRequest($request);

        $rentRepository->save($rent, true);

        return $this->redirectToRoute('app_rent_index', [], Response::HTTP_SEE_OTHER);


        return $this->renderForm('user/rent/edit.html.twig', [
            'rent' => $rent,
            'form' => $form,
        ]);
    }

    // -- Supprimer
    #[Route('/rent/{id}', name: 'app_rent_delete', methods: ['POST'])]
    public function deleteRent(Request $request, Rent $rent, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $rent->getId(), $request->request->get('_token'))) {
            $entityManager->remove($rent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_rent_index', [], Response::HTTP_SEE_OTHER);
    }
}
