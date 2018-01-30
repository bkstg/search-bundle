<?php

namespace Bkstg\SearchBundle\Twig;

use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;

class SearchResultExtension extends \Twig_Extension
{
    private $em;
    private $twig;

    public function __construct(
        EntityManagerInterface $em,
        Environment $twig
    ) {
        $this->em = $em;
        $this->twig = $twig;
    }

    public function getFunctions()
    {
        return [
            new \Twig_Function('bkstg_search_result', [$this, 'renderSearchResult'], ['is_safe' => ['html']]),
        ];
    }

    public function renderSearchResult($entity)
    {
        // Build available templates and resolve one.
        $templates = $this->getTemplates($entity);
        $template = $this->twig->resolveTemplate($templates);

        // Render the template with the result.
        return $template->render(['result' => $entity]);
    }

    /**
     * Builds an array of templates to look for when rendering a result.
     */
    public function getTemplates($entity)
    {
        $templates = [];

        // If this is an entity make a guess.
        if (null !== $metadata = $this->em->getClassMetadata(get_class($entity))) {
            // Explode name.
            $parts = explode('\\', $metadata->getName());

            // Traverse into parts until Entity is reached.
            $folder = '';
            while (end($parts) != 'Entity') {
                $folder = array_pop($parts) . DIRECTORY_SEPARATOR . $folder;
            }

            // Pop off Entity and concatenate remaining namespace.
            array_pop($parts);
            $namespace = '@' . (str_replace('Bundle', '', implode('', $parts)));

            // Create a guessed template.
            $templates[] = $namespace . DIRECTORY_SEPARATOR . $folder . 'search-result.html.twig';
        }

        // Absolute rock-bottom fallback.
        $templates[] = '@BkstgSearch/Search/search-result.html.twig';

        return $templates;
    }

    public function getName()
    {
        return 'bkstg_search';
    }
}
