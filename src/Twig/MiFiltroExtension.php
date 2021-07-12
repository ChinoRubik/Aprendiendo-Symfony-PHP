<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MiFiltroExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('multiplicar', [$this, 'multiplicar']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('multiplicar', [$this, 'multiplicar']),
        ];
    }

    public function multiplicar($numero)
    {
        $tabla= "<h2>Tabla del $numero</h2>";
        for($i = 1 ; $i< 11 ; $i++){
            $tabla.="$numero x $i = ".($numero*$i)."<br>";
        }

        return $tabla;
    }
}
