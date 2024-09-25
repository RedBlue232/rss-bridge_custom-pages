<?php

class CloudForceOneBridge extends BridgeAbstract {
    const NAME = 'Cloudflare Threat Intelligence';
    const URI = 'https://www.cloudflare.com/fr-fr/threat-intelligence/';
    const DESCRIPTION = 'Bridge for CloudForce One Threat Intelligence updates';
    const MAINTAINER = 'Red';
    const PARAMETERS = []; 

    public function collectData() {
        $html = getSimpleHTMLDOM(self::URI); 

        // Parcours des éléments
        foreach ($html->find('div.col-lg-4.col-md-6.col-sm-12') as $element) { 
            $item = [];

            // Titre de l'article
            $titleElement = $element->find('h5.headline-5.black-900.mb1', 0);
            if ($titleElement) {
                $item['title'] = $titleElement->plaintext;
            }

            // Lien de l'article
            $linkElement = $element->find('a.learn-more', 0);
            if ($linkElement) {
                $item['uri'] = urljoin(self::URI, $linkElement->href); 
            }

            // Image de l'article
            $imageElement = $element->find('img', 0);
            if ($imageElement) {
                $item['enclosures'] = [$imageElement->src]; 
            }

            // Description de l'article
            $descriptionElement = $element->find('p.black-900.f2.fwnormal.lh-7', 0);
            if ($descriptionElement) {
                $item['content'] = $descriptionElement->plaintext;
            } else {
                $item['content'] = 'Description non disponible'; 
            }

            $this->items[] = $item;
        }
    }
}
