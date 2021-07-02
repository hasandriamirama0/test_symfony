<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// 1. Include Required Namespaces
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\PostalCode;

class CompanyFormType extends AbstractType {
    private $em;

    /**
     * The Type requires the EntityManager as argument in the constructor. It is autowired
     * in Symfony 3.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // 1. Remove the dependent select from the original buildForm as this will be
        // dinamically added later and the trigger as well
        $builder->add('name')
                ->add('type')
                ->add('description')
                ->add('postal_code')
                ->add('city');

        // 2. Add 2 event listeners for the form
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }

    protected function addElements(FormInterface $form, PostalCode $postal_code = null) {
        // 3. Add the postal code element
        $form->add('postal_code', EntityType::class, array(
            'required' => true,
            'data' => $postal_code,
            'placeholder' => 'Select a Postal Code ...',
            'class' => 'App:PostalCode'
        ));

        // Cities empty, unless there is a selected postal code (Edit View)
        $cities = array();

        // If there is a postal code stored in the Company entity, load the cities of it
        if ($postal_code) {
            // Fetch Cities if there's a selected postal code
            $repoCity = $this->em->getRepository('App:City');

            $cities = $repoCity->createQueryBuilder("q")
                ->where("q.postal_code = :postal_code_id")
                ->setParameter("postal_code_id", $postal_code->getId())
                ->getQuery()
                ->getResult();
        }

        // Add the Cities field with the properly data
        $form->add('city', EntityType::class, array(
            'required' => true,
            'placeholder' => 'Select a Postal Code first ...',
            'class' => 'App:City',
            'choices' => $cities
        ));
    }

    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();

        // Search for selected postal code and convert it into an Entity
        $postal_code = $this->em->getRepository('App:PostalCode')->find($data['postal_code']);

        $this->addElements($form, $postal_code);
    }

    function onPreSetData(FormEvent $event) {
        $company = $event->getData();
        $form = $event->getForm();

        // When you create a new company, the postal code is always empty
        $postal_code = $company->getPostalCode() ? $company->getPostalCode() : null;

        $this->addElements($form, $postal_code);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Company'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'company';
    }
}
