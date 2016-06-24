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

namespace Darkilliant\Bundle\RequestInterceptorDebuggerBundle\Logger;

use Darkilliant\Bundle\RequestInterceptorDebuggerBundle\Event\RequestDefinitionInterceptEvent;
use Darkilliant\CurlCommandGenerator\Generator\CommandGenerator;
use Psr\Log\LoggerInterface;

/**
 * CurlCommandLogger
 *
 * @author Jean Pasqualini <jpasqualini75@gmail.com>
 */
class CurlCommandLogger
{
    protected $curlCommandGenerator;

    /** @var $logger LoggerInterface */
    protected $logger;

    public function __construct(CommandGenerator $commandGenerator)
    {
        $this->curlCommandGenerator = $commandGenerator;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param RequestDefinitionInterceptEvent $event
     */
    public function onRequestDefinitionIntercepted(RequestDefinitionInterceptEvent $event)
    {
        $this->logger->info('curl : '.$this->curlCommandGenerator->generateCommand($event->getDefinition()));
    }
}