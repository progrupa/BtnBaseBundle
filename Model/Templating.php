<?php

namespace Btn\BaseBundle\Model;

use Symfony\Component\HttpFoundation\Response;

// json templating helper
class Templating
{
    /**
     * Renders JSON for our View wrapper.
     *
     * @param array    $array
     * @param Response $response
     * @param int      $status
     *
     * @return Response A Response instance
     */
    public function renderJson($content = '', $verdict = 'success', $custom = array(), $status = 200)
    {
        $result = array(
            'verdict' => $verdict,
            'content' => $content
        );

        if (!empty($custom) && is_array($custom)) {
            $result = array_merge($result, $custom);
        }

        return $this->json($result, null, $status);
    }

    /**
     * Renders array into JSON.
     *
     * @param array    $array
     * @param Response $response
     * @param int      $status
     *
     * @return Response A Response instance
     */
    public function json(array $array, Response $response = null, $status = 200)
    {
        if (null === $response) {
            $response = new Response();
        }

        $response->setContent(json_encode($array));
        $response->headers->set('Content-type', 'text/plain');
        $response->setStatusCode($status);

        return $response;
    }
}
