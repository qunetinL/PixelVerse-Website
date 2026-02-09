<?php

namespace PixelVerseApp\Controllers;

class PageController extends BaseController
{
    public function galerie()
    {
        $this->render('pages/galerie', [
            'title' => 'Galerie des Personnages - PixelVerse'
        ]);
    }

    public function contact()
    {
        $this->render('pages/contact', [
            'title' => 'Contactez-nous - PixelVerse'
        ]);
    }

    public function mentionsLegales()
    {
        $this->render('pages/mentions-legales', [
            'title' => 'Mentions Légales - PixelVerse'
        ]);
    }

    public function cgv()
    {
        $this->render('pages/cgv', [
            'title' => 'Conditions Générales de Vente - PixelVerse'
        ]);
    }

    public function enSavoirPlus()
    {
        $this->render('pages/en-savoir-plus', [
            'title' => 'En Savoir Plus - PixelVerse'
        ]);
    }
}
