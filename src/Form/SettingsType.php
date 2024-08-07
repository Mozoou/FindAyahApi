<?php

namespace App\Form;

use App\Service\QuranDataFetcher\ChapterFetcher;
use App\Service\QuranDataFetcher\Model\Chapter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingsType extends AbstractType
{
    public function __construct(private ChapterFetcher $chapterFetcher)
    {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('questions', ChoiceType::class, [
                'label' => 'Number of questions',
                'choices' => [
                    1 => 1,
                    3 => 3,
                    5 => 5,
                ],
                'choice_attr' => function ($choice, string $key, mixed $value) {
                    return ['class' => 'question-checkbox w-4 h-4 text-yellow-600 bg-gray-100 border-gray-300 rounded focus:ring-yellow-500 dark:focus:ring-yellow-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500'];
                },
                'expanded' => true,
                'multiple' => false
            ])
            ->add('chapters', ChoiceType::class, [
                'label' => 'Chapters',
                'choices' => $this->chapterFetcher->fetchAllChapters(),
                'choice_label' => function (Chapter $choice, string $key, mixed $value): string {
                    return $choice->__toString();
                },
                'choice_attr' => function ($choice, string $key, mixed $value) {
                    return ['class' => 'chapter-checkbox w-4 h-4 text-yellow-600 bg-gray-100 border-gray-300 rounded focus:ring-yellow-500 dark:focus:ring-yellow-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500'];
                },
                'choice_value' => function (?Chapter $entity): int {
                    return $entity ? $entity->getNumber() : 0;
                },
                'expanded' => true,
                'multiple' => true
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Start',
                'attr' => [
                    'class' => 'w-full block text-white bg-green-900 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-yellow-400 dark:hover:bg-yellow-500 dark:focus:ring-yellow-800'
                ],
                'row_attr' => [
                    'class' => 'mt-20'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'attr' => [
                'class' => 'flex flex-col justify-center align-center'
            ]
        ]);
    }
}
