<?php
// src/EventListener/ExceptionListener.php
namespace App\EventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use App\Exception\EntityValueResolver;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Psr\Log\LoggerInterface;
class ExceptionListener
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }
    public function __invoke(ExceptionEvent $event): void
    {
        
        // You get the exception object from the received event
        $exception = $event->getThrowable();
        $message = sprintf(
        'My Error says: %s with code: %s, in file %s, at line %s',
        $exception->getMessage(),
        $exception->getCode(),
        $exception->getFile(),
        $exception->getLine()
        );

        $this->logger->error($message);
        // get instance of the exception

        if ($exception instanceof EntityValueResolver) {
            $this->logger->debug("TESTING");
        }

        // Customize your response object to display the exception details
        $response = new Response();
        $response->setContent($message);
        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            // get request path without query
            $code = $exception->getStatusCode();
            $path = $event->getRequest()->getPathInfo();
            if ($code == 404 && in_array($path, ["/index","/home"])) {
                // redirect
                $response = new RedirectResponse('/');
                $event->setResponse($response);
                return;
            } 

            if ($code == 404 && str_starts_with($path, "/blog")) {
                // redirect
                $response = new RedirectResponse('/blog');
                $event->setResponse($response);
                return;
            }

            if ($code == 404 && str_starts_with($path, "/admin")) {
                // redirect to dashboard if any admin route is not found
                $response = new RedirectResponse('/admin/dashboard');
                $event->setResponse($response);
                return;
            }
            
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
        // sends the modified response object to the event
        $event->setResponse($response);
    }
}