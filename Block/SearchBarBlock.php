<?php

namespace Bkstg\SearchBundle\Block;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Sonata\BlockBundle\Templating\TwigEngine;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SearchBarBlock extends AbstractBlockService
{
    private $form;
    private $url_generator;

    public function __construct(
        $name,
        TwigEngine $templating,
        FormFactoryInterface $form,
        UrlGeneratorInterface $url_generator
    ) {
        $this->form = $form;
        $this->url_generator = $url_generator;
        parent::__construct($name, $templating);
    }


    public function execute(BlockContextInterface $context, Response $response = null)
    {
        $builder = $this->form->createBuilder();
        $builder
            ->add('search', TextType::class)
            ->setAction($this->url_generator->generate('bkstg_search_search'))
            ->setMethod('GET')
        ;

        $form = $builder->getForm();
        return $this->renderResponse($context->getTemplate(), [
            'block' => $context->getBlock(),
            'settings' => $context->getSettings(),
            'form' => $form->createView(),
        ], $response);
    }

    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'template' => '@BkstgSearch/Block/search_bar.html.twig',
        ]);
    }
}
