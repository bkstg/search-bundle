<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgSearchBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
            new \Twig_Function('bkstg_search_result_render', [$this, 'renderSearchResult'], ['is_safe' => ['html']]),
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
     *
     * @param mixed $entity
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
            while ('Entity' != end($parts)) {
                $folder = array_pop($parts) . DIRECTORY_SEPARATOR . $folder;
            }

            // Pop off Entity and concatenate remaining namespace.
            array_pop($parts);
            $namespace = '@' . (str_replace('Bundle', '', implode('', $parts)));

            // Create a guessed template.
            $templates[] = $namespace . DIRECTORY_SEPARATOR . $folder . '_search_result.html.twig';
        }

        // Absolute rock-bottom fallback.
        $templates[] = '@BkstgSearch/Search/_search_result.html.twig';

        return $templates;
    }

    public function getName()
    {
        return 'bkstg_search';
    }
}
