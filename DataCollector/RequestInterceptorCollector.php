<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace Darkilliant\Bundle\RequestInterceptorDebuggerBundle\DataCollector;

use Buzz\Browser;
use Darkilliant\Bundle\RequestInterceptorDebuggerBundle\Event\RequestDefinitionInterceptEvent;
use Darkilliant\Bundle\RequestInterceptorDebuggerBundle\Logger\CurlCommandLogger;
use Darkilliant\CurlCommandGenerator\Definition\Factory\BuzzDefinitionFactory;
use Darkilliant\CurlCommandGenerator\Generator\CommandGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * RequestInterceptorCollector
 *
 * @author Jean Pasqualini <jpasqualini75@gmail.com>
 * @package Darkilliant\Bundle\CurlCommandGeneratorBundle\DataCollector;
 */
class RequestInterceptorCollector extends DataCollector
{
    protected $client;

    protected $definitionCollection = array();

    protected $requestDefinitionFactory;

    protected $curlCommandGenerator;

    /**
     * @param Browser $browser
     * @param BuzzDefinitionFactory $requestDefinitionFactory
     * @param CommandGenerator $commandGenerator
     * @param CurlCommandLogger $commandLogger
     */
    public function __construct(Browser $browser, BuzzDefinitionFactory $requestDefinitionFactory, CommandGenerator $commandGenerator)
    {
        $this->client = $browser->getClient();

        $this->requestDefinitionFactory = $requestDefinitionFactory;

        $this->curlCommandGenerator = $commandGenerator;
    }

    /**
     * notify data collector new request intercepted
     *
     * @param RequestDefinitionInterceptEvent $event
     */
    public function onRequestDefinitionIntercepted(RequestDefinitionInterceptEvent $event)
    {
        $definition = $event->getDefinition();

        $this->definitionCollection[] = $definition;
    }

    /**
     * @return array
     */
    public function getCommandCollection()
    {
        return $this->data['commandCollection'];
    }

    /**
     * Collect request intercepted and build data for view
     *
     * @param Request $request
     * @param Response $response
     * @param \Exception|null $exception
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = array();

        $curlCommandCollection = array();

        foreach($this->definitionCollection as $definition)
        {
            $curlCommandCollection[] = $this->curlCommandGenerator->generateCommand($definition);
        }

        $this->data['commandCollection'] = $curlCommandCollection;
    }

    /**
     * @return string name of data collector
     */
    public function getName()
    {
        return 'darkilliant_request_interceptor_debugger.request_interceptor_collector';
    }
}