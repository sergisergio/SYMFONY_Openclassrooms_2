<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Entity\Application;
use App\Entity\Category;
use App\Entity\Image;
use App\Entity\AdvertSkill;
use App\Entity\Skill;
use App\Repository\AdvertRepository;
use App\Service\PurgerAdvert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("")
 */
class AdvertController extends AbstractController
{
    /**
     * Constructeur
     *
     * @param EntityManagerInterface $em
     * @param AdvertRepository $repository
     */
    public function __construct(EntityManagerInterface $em, AdvertRepository $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    /**
     * Retourne la page d'accueil des annonces
     *
     * @Route("/{page}", name="home", requirements={"page" = "\d+"}, defaults={"page" = 1})
     *
     * @param $page
     * @return Response
     */
    public function index($page)
    {
        if ($page < 1) {
            throw $this->createNotFoundException('Page "'.$page.'" inexistante.');
        }

        $nbPerPage = 3;

        $listAdverts = $this->repository->getAdverts($page, $nbPerPage);
        $nbPages = ceil(count($listAdverts) / $nbPerPage);


        if ($page > $nbPages) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        return $this->render('Advert/index.html.twig', [
            'listAdverts' => $listAdverts,
            'nbPages' => $nbPages,
            'page' => $page
        ]);
    }

    /**
     * Retourne une annonce en particulier
     *
     * @Route("/view/{id}", name="view", requirements={"id" = "\d+"})
     *
     * @param $id
     * @return Response
     */
    public function view(Advert $advert)
    {

        //$advert = $this->repository->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        $listApplications = $this->em
            ->getRepository(Application::class)
            ->findBy(['advert' => $advert]);

        $listAdvertSkills = $this->em
            ->getRepository(AdvertSkill::class)
            ->findBy(['advert' => $advert]);

        return $this->render('Advert/view.html.twig', [
            'advert'  => $advert,
            'listApplications' => $listApplications,
            'listAdvertSkills' => $listAdvertSkills,
        ]);
    }

    /**
     * Function de test d'une route avec plusieurs paramètres
     *
     * @Route("/view/{year}/{slug}.{format}", name="advert_view_slug", name="oc_advert_view_slug", requirements={
     *   "year"   = "\d{4}",
     *   "format" = "html|xml"
     * }, defaults={"format" = "html"})
     *
     * @param $slug
     * @param $year
     * @param $format
     * @return Response
     */
    public function viewSlug($slug, $year, $format)
    {
        return new Response(
            "On pourrait afficher l'annonce correspondant au
            slug '".$slug."', créée en ".$year." et au format ".$format."."
        );
    }

    /**
     * Permet d'ajouter une annonce
     *
     * @Route("/add", name="advert_add")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function add(Request $request)
    {
        $advert = new Advert();
        $advert->setTitle('Recherche développeur Angular');
        $advert->setAuthor('Alexandre');
        $advert->setContent('Nous recherchons un développeur Angular débutant sur Paris');
        $advert->setSlug('dev');

        if ($request->isMethod('POST')) {
            $this->addFlash('notice', 'Annonce bien enregistrée.');
            return $this->redirectToRoute('view', ['id' => $advert->getId()]);
        }
        return $this->render('Advert/add.html.twig');
    }

    /**
     * Permet de modifier une annonce
     *
     * @Route("/edit/{id}", name="edit", requirements={"id" = "\d+"})
     *
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit($id, Request $request)
    {
        $advert = $this->repository->find($id);
        if (null == $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }
        if ($request->isMethod('POST')) {
            $this->addFlash('notice', 'Annonce bien modifiée.');
            return $this->redirectToRoute('view', array('id' => $advert->getId()));
        }
        return $this->render('Advert/edit.html.twig', [
        'advert' => $advert
        ]);
    }

    /**
     * Permet de supprime une annonce
     *
     * @Route("/delete/{id}", name="delete", requirements={"id" = "\d+"})
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Advert $advert)
    {
        //$advert = $this->repository->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }
        $this->em->remove($advert);
        $this->em->flush();

        return $this->redirectToRoute('home');
    }

    /**
     * Permet d'afficher un nombre déterminé d'annonces dans le aside
     *
     * @param $limit
     * @return Response
     */
    public function menuAction($limit)
    {
        $listAdverts = $this->repository->findBy(
            [],['date' => 'desc'], $limit, 0
        );

        return $this->render('Advert/menu.html.twig', [
        'listAdverts' => $listAdverts
        ]);
    }

    /**
     * Permet de supprimer les annonces qui n'ont aucune candidature après un certain nombre de jours
     *
     * @Route("/purge/{days}", name="purge", requirements={"days" = "\d+"})
     *
     * @param $days
     * @param PurgerAdvert $purger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function purge($days, PurgerAdvert $purger)
    {
        $purger->purge($days);
        $this->addFlash('info', 'Annonces purgées');

        return $this->redirectToRoute('home');
    }
}