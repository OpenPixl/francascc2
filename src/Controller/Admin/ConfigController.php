<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Config;
use App\Form\Admin\ConfigType;
use App\Repository\Admin\ConfigRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/opadmin/config")
 */
class ConfigController extends AbstractController
{
    /**
     * @Route("/", name="op_admin_config_index", methods={"GET"})
     */
    public function index(ConfigRepository $configRepository): Response
    {
        return $this->render('admin/config/index.html.twig', [
            'configs' => $configRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="op_admin_config_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $config = new Config();
        $form = $this->createForm(ConfigType::class, $config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $vignetteFile */
            $vignetteFile = $form->get('headerFile')->getData();

            if ($vignetteFile) {
                $originalVignetteFilename = pathinfo($vignetteFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeVignetteFilename = $slugger->slug($originalVignetteFilename);
                $newVignetteFilename = $safeVignetteFilename . '-' . uniqid() . '.' . $vignetteFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $vignetteFile->move(
                        $this->getParameter('config_directory'),
                        $newVignetteFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $config->setVignetteName($newVignetteFilename);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($config);
            $entityManager->flush();

            return $this->redirectToRoute('admin_config_index');
        }

        return $this->render('admin/config/new.html.twig', [
            'config' => $config,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="op_admin_config_show", methods={"GET"})
     */
    public function show(Config $config): Response
    {
        return $this->render('admin/config/show.html.twig', [
            'config' => $config,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="op_admin_config_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Config $config, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ConfigType::class, $config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $vignetteFile */
            $vignetteFile = $form->get('headerFile')->getData();

            if ($vignetteFile) {
                $originalVignetteFilename = pathinfo($vignetteFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeVignetteFilename = $slugger->slug($originalVignetteFilename);
                $newVignetteFilename = $safeVignetteFilename . '-' . uniqid() . '.' . $vignetteFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $vignetteFile->move(
                        $this->getParameter('config_directory'),
                        $newVignetteFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $config->setVignetteName($newVignetteFilename);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('op_admin_config_edit', [
                "id" => $config->getId()
            ]);
        }

        return $this->render('admin/config/edit.html.twig', [
            'config' => $config,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="op_admin_config_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Config $config): Response
    {
        if ($this->isCsrfTokenValid('delete'.$config->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($config);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_config_index');
    }

    public function VignetteShow(){
        $config = $this
            ->getDoctrine()
            ->getRepository(Config::class)
            ->findOneBy(['id'=> 1])
        ;

        return $this->render('admin/config/vignetteshow.html.twig',[
            'config' => $config,
        ]);
    }
}
