<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class ArtworkFormSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SET_DATA => 'postSetData'
        ];
    }

    public function postSetData(FormEvent $event): void
    {
        $form = $event->getForm();
        $entity = $form->getData();

        $constraints = $entity->getId() ? [] : [
            new NotBlank([
                'message' => "L'image est obligatoire."
            ]),
            new Image([
                'mimeTypesMessage' => "Vous devez sélectionner une image.",
                'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp']
            ])
        ];

        $form->add('picture', FileType::class, [
            'data_class' => null,
            'constraints' => $constraints
        ]);
    }
}
