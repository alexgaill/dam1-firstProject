<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                // Modifie la valeur du label de l'input
                'label' => "Titre",
                // Gère les attributs de l'input
                'attr' => [
                    'placeholder' => "Titre"
                ],
                // Gère les attributs de la div contenant l'input, le label, l'help, et le message d'erreur
                'row_attr' => [
                    'class' => 'mb-3 form-floating'
                ],
                'help' => "Indiquez le titre de l'article",
                'help_attr' => [
                    'class' => 'bg-info text-white'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => "Contenu"
            ])
            // L'EntityType permet de générer un champ <select>.
            // Comme son nom l'indique, l'EntityType fait référence à une entité 
            // on doit donc lui associer l'entité grâce à l'option class.
            // On doit également définir qu'elle information s'affiche pour chaque <option> grâce à l'option choice_label
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name'
            ])
            ->add('picture', FileType::class, [
                'label' => "Image d'en-tête d'article",
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(
                        mimeTypes:["image/jpeg", "image/png"],
                        mimeTypesMessage: "Le format attendu est jpg, jpeg ou png"
                    )
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "modifier"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
