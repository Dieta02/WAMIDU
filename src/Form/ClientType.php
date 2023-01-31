<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        function getRandomStr($n)
        {
            // Stockez toutes les lettres possibles dans une chaîne.
            $str = '012345679012345679ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomStr = '';

            // Générez un index aléatoire de 0 à la longueur de la chaîne -1.
            for ($i = 0; $i < $n; $i++) {
                $index = rand(0, strlen($str) - 1);
                $randomStr .= $str[$index];
            }

            return $randomStr;
        }

        $builder
            ->add('name')
            ->add('surname')
            ->add('email')
            ->add('codeUser', null, ['attr' => ['value' => getRandomStr(8), 'readonly' => 'readonly']])
            //->add('codePin')
            ->add('ticket', null, ['attr' => ['min' => 0, 'max' => 50]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}