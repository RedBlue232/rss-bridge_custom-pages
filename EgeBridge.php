<?php

class EgeBridge extends BridgeAbstract {
    const NAME = 'EGE Actualités';
    const URI = 'https://www.ege.fr/actualites';
    const DESCRIPTION = 'Récupère les dernières actualités de l\'École de Guerre Économique';
    const MAINTAINER = 'Red';
    const PARAMETERS = [];

    public function collectData() {
        $html = getSimpleHTMLDOM(self::URI);

        foreach ($html->find('.views-row') as $element) {
            $item = [];

            // Titre
            $titleElement = $element->find('.card__title a', 0);
            if ($titleElement) {
                $relativeUrl = $titleElement->href;
                if (strpos($relativeUrl, '/actualites/') === 0) {
                    // Remove the leading /actualites
                    $relativeUrl = substr($relativeUrl, strlen('/actualites'));
                }
                $item['title'] = $titleElement->plaintext;
                $item['uri'] = self::URI . $relativeUrl;
            }

            // Date
            $dateElement = $element->find('.card__date time', 0);
            if ($dateElement) {
                $item['timestamp'] = strtotime($dateElement->datetime);
            }

            // Contenu
            $contentElement = $element->find('.card__body', 0);
            if ($contentElement) {
                $item['content'] = $contentElement->innertext;
            }

            $this->items[] = $item;
        }
    }
}
?>