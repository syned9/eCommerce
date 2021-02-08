<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nom', TextType::class,[
            'label'=>'Nom du produit : '
        ])
        ->add('description',TextareaType::class,[
            'label'=>'Descritpion du produit : '
        ])
        ->add('quantite',IntegerType::class,[
            'label'=>'Quatitité : '
        ])
        ->add('prix',IntegerType::class,[
            'label'=>'Prix : '
        ])
        ->add('categorie')
        ->add('disponibilite', ChoiceType::class, [
            'label'=>'Disponibilité : ',
            'choices'=>[
                 ''=>null,
                "Oui"=>true, 
                "Non"=>false
            ]
            
        ])
        ->add('urlImage', TextType::class,[
            'label'=>'Ajouter une image'
        ])
        ->add('submit', SubmitType::class,[
            'label'=>'Ajouter le nouveau produit'
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}