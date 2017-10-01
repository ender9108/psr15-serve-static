<?php
namespace EnderLab;

use GuzzleHttp\Psr7\Response;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ServeStaticMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    private $staticDirectory;

    /**
     * ServeStaticMiddleware constructor.
     * @param string $staticDirectory
     */
    public function __construct(string $staticDirectory)
    {
        $this->staticDirectory = trim($staticDirectory, '/').'/';
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $path = trim($request->getUri()->getPath(), '/');
        $info = parse_url($path);

        if (isset($info['path']) && file_exists($this->staticDirectory.$info['path'])) {
            $response = new Response();

            ob_start();
            include($this->staticDirectory.$info['path']);
            $content = ob_get_contents();
            ob_end_clean();

            $response = $response->withHeader('Content-type', mime_content_type($this->staticDirectory.$info['path']));
            $response->getBody()->write($content);
            return $response;
        }

        return $delegate->process($request);
    }
}