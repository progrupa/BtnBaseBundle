<?php

namespace Btn\BaseBundle\Helper;

use Symfony\Component\HttpFoundation\Response;

// json templating helper
class Templating
{
    /**
     * Renders JSON for our View wrapper.
     *
     * @param array    $array
     * @param Response $response
     *
     * @return Response A Response instance
     */
    public function renderJson($content = '', $verdict = 'success', $custom = array())
    {
        $result = array(
            'verdict' => $verdict,
            'content' => $content
        );

        if (!empty($custom) && is_array($custom)) {
            $result = array_merge($result, $custom);
        }

        return $this->json($result);
    }

    /**
     * Renders array into JSON.
     *
     * @param array    $array
     * @param Response $response
     *
     * @return Response A Response instance
     */
    public function json(array $array, Response $response = null)
    {
        if (null === $response) {
            $response = new Response();
        }

        $response->setContent(json_encode($array));
        $response->headers->set('Content-type', 'text/plain');

        return $response;
    }
}
