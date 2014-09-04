<?php

namespace Btn\BaseBundle\Form\Type;

use Symfony\Component\Form\AbstractType as BaseAbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use Btn\BaseBundle\Provider\EntityProviderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Btn\BaseBundle\Assetic\Loader\AssetLoaderInterface;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractType extends BaseAbstractType
{
    /** @var string $class */
    protected $class;
    /** @var \Doctrine\ORM\EntityManager $entityManager */
    protected $entityManager;
    /** @var \Btn\BaseBundle\Provider\EntityProviderInterface $entityProvider */
    protected $entityProvider;
    /** @var \Symfony\Component\Translation\TranslatorInterface $translator */
    protected $translator;
    /** @var \Btn\BaseBundle\Assetic\Loader\AssetLoaderInterface $assetLoader */
    protected $assetLoader;
    /** @var \Symfony\Component\Routing\RouterInterface $router */
    protected $router;

    /**
     *
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     *
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    /**
     *
     */
    public function setEntityProvider(EntityProviderInterface $entityProvider)
    {
        $this->entityProvider = $entityProvider;

        return $this;
    }

    /**
     *
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;

        return $this;
    }

    /**
     *
     */
    public function setAssetLoader(AssetLoaderInterface $assetLoader)
    {
        $this->assetLoader = $assetLoader;

        return $this;
    }

    /**
     *
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        if ($this->class) {
            $resolver->setDefaults(array(
                'data_class' => $this->class,
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        if (!$this->translator) {
            throw new \Exception('Translator was not injectet into form');
        }

        return $this->translator->trans($id, $parameters, $domain, $locale);
    }
}
