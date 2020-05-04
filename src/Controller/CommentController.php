<?php

namespace App\Controller;

use App\Entity\Comic;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CommentController extends AbstractController
{

    /**
     * @Route("/comics/{id}/comment",
     *     name="comment_form_comic",
     *     requirements={"id"= "\d+"})
     *
     * @Template
     */
    public function form(Comic $comic, Request $request)
    {
        if ($this->getUser()) {
            $entityManager = $this->getDoctrine()->getManager();

            $comment = new Comment();
            $comment->setComic($comic)
              ->setAuthor($this->getUser());

            $form = $this->createForm(CommentType::class, $comment);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($comment);
                $entityManager->flush();

                $html = $this->renderView('comment/block_comment.html.twig', ['comment' => $comment]);
                return $this->json(['success' => 1, 'html' => $html]);
            }

            return [
              'comment' => $comment,
              'form' => $form->createView(),
            ];
        }

        return $this->render('comment/nouser.html.twig');
    }

    /**
     * @Route("/comment/{id}/delete",
     *     name="comment_delete",
     *     requirements={"id"= "\d+"})
     *
     * @Template
     */
    public function delete(Comment $comment)
    {
        $comic = $comment->getComic();

        if($this->getUser() === $comment->getAuthor()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        $url = $this->generateUrl('comic', $comic->getRouteParams());
        return $this->redirect($url);
    }

}
