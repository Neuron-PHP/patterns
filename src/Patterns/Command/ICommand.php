<?php

namespace Neuron\Patterns\Command;

/**
 * Command pattern interface for encapsulating executable operations.
 * 
 * The ICommand interface defines the contract for command objects in the
 * command pattern implementation. Commands encapsulate a request as an object,
 * allowing you to parameterize clients with different requests, queue or log
 * requests, and support undoable operations.
 * 
 * Key benefits of the Command pattern:
 * - Decouples the invoker from the receiver of the request
 * - Supports parameterization of objects with operations
 * - Allows queuing, logging, and undo operations
 * - Enables macro command composition
 * - Supports transactional behavior
 * 
 * Command implementations should:
 * - Encapsulate all information needed to perform an action
 * - Be stateless or maintain minimal state
 * - Handle parameter validation within the execute method
 * - Return appropriate results or throw exceptions on failure
 * 
 * @package Neuron\Patterns\Command
 * 
 * @example
 * ```php
 * // Example command implementation
 * class EmailCommand implements ICommand
 * {
 *     private $mailer;
 *     
 *     public function __construct(IMailer $mailer)
 *     {
 *         $this->mailer = $mailer;
 *     }
 *     
 *     public function execute(?array $params = null): mixed
 *     {
 *         $to = $params['to'] ?? throw new InvalidArgumentException('Missing recipient');
 *         $subject = $params['subject'] ?? 'No Subject';
 *         $body = $params['body'] ?? '';
 *         
 *         return $this->mailer->send($to, $subject, $body);
 *     }
 * }
 * 
 * // Usage with command invoker
 * $command = new EmailCommand($mailer);
 * $invoker = new Invoker();
 * $invoker->setCommand($command);
 * $result = $invoker->execute(['to' => 'user@example.com', 'subject' => 'Hello']);
 * ```
 */

interface ICommand
{
	/**
	 * @param array|null $params
	 * @return mixed
	 */

	public function execute( ?array $params = null ): mixed;
}
