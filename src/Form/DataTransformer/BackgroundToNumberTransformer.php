<?php
namespace App\Form\DataTransformer;

use App\Entity\Background;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class BackgroundToNumberTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  Issue|null $issue
     * @return string
     */
    public function transform($issue)
    {
        if (null === $issue) {
            return "";
        }

        return $issue->getId();
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $number
     * @return Issue|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $background = $this->entityManager->getRepository('App:Background')->findOneBy(['id' => $id]);

        if (null === $background) {
            throw new TransformationFailedException(sprintf(
                'Le problème avec le numéro "%s" ne peut pas être trouvé!',
                $id
            ));
        }

        return $background;
    }
}