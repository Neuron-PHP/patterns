[![CI](https://github.com/Neuron-PHP/patterns/actions/workflows/ci.yml/badge.svg)](https://github.com/Neuron-PHP/patterns/actions)
[![codecov](https://codecov.io/gh/Neuron-PHP/patterns/graph/badge.svg)](https://codecov.io/gh/Neuron-PHP/patterns)
# Neuron-PHP Patterns

A comprehensive design patterns library for PHP 8.0+ that provides robust implementations of common software design patterns including Singleton, Registry, Observer, Command, and Criteria patterns.

## Table of Contents

- [Installation](#installation)
- [Patterns Overview](#patterns-overview)
- [Registry Pattern](#registry-pattern)
- [Singleton Pattern](#singleton-pattern)
- [Observer Pattern](#observer-pattern)
- [Command Pattern](#command-pattern)
- [Criteria Pattern](#criteria-pattern)
- [IRunnable Interface](#irunnable-interface)
- [Usage Examples](#usage-examples)
- [Testing](#testing)
- [Best Practices](#best-practices)
- [More Information](#more-information)

## Installation

### Requirements

- PHP 8.0 or higher
- Extensions: curl, json
- Composer

### Install via Composer

```bash
composer require neuron-php/patterns
```

## Patterns Overview

The Patterns component provides production-ready implementations of:

- **Registry**: Global object storage and service locator
- **Singleton**: Single instance management with multiple storage backends
- **Observer**: Event notification between objects
- **Command**: Encapsulation of operations as objects
- **Criteria**: Flexible filtering and selection of entities

## Registry Pattern

The Registry pattern provides centralized storage for objects and acts as a service locator throughout your application.

### Basic Usage

```php
use Neuron\Patterns\Registry;

// Get the registry instance (singleton)
$registry = Registry::getInstance();

// Store objects
$registry->set('database', $dbConnection);
$registry->set('cache', $cacheManager);
$registry->set('config.app', $appConfig);

// Retrieve objects
$db = $registry->get('database');
$cache = $registry->get('cache');

// Check existence
if ($registry->has('cache')) {
    // Cache is available
}

// Remove objects
$registry->remove('temp.data');

// Clear all objects
$registry->reset();
```

### Nested Keys

```php
// Support for dot notation
$registry->set('services.email.smtp', $smtpService);
$registry->set('services.email.templates', $templateEngine);

// Retrieve nested values
$smtp = $registry->get('services.email.smtp');
```

### Service Locator Pattern

```php
class ServiceContainer
{
    private Registry $registry;

    public function __construct()
    {
        $this->registry = Registry::getInstance();
        $this->registerServices();
    }

    private function registerServices(): void
    {
        // Register core services
        $this->registry->set('logger', new Logger());
        $this->registry->set('mailer', new Mailer());
        $this->registry->set('cache', new CacheManager());

        // Register factories
        $this->registry->set('db.factory', function($config) {
            return new DatabaseConnection($config);
        });
    }

    public function get(string $service)
    {
        $service = $this->registry->get($service);

        // Resolve factories
        if (is_callable($service)) {
            return $service($this->registry->get('config'));
        }

        return $service;
    }
}
```

## Singleton Pattern

The Singleton pattern ensures a class has only one instance and provides global access to it. The component includes multiple storage backends.

### Available Storage Backends

- **Memory**: In-process memory storage (default)
- **Session**: PHP session-based storage
- **Memcache**: Memcache server storage
- **Redis**: Redis server storage

### Memory Singleton

```php
use Neuron\Patterns\Singleton\Memory;

class Configuration extends Memory
{
    private array $settings = [];

    public function set(string $key, $value): void
    {
        $this->settings[$key] = $value;
    }

    public function get(string $key)
    {
        return $this->settings[$key] ?? null;
    }
}

// Usage
$config = Configuration::getInstance();
$config->set('app.name', 'My Application');

// Same instance everywhere
$config2 = Configuration::getInstance();
echo $config2->get('app.name'); // "My Application"
```

### Session Singleton

```php
use Neuron\Patterns\Singleton\Session;

class UserSession extends Session
{
    private ?User $user = null;

    public function login(User $user): void
    {
        $this->user = $user;
        $this->serialize(); // Persist to session
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function logout(): void
    {
        $this->user = null;
        $this->invalidate(); // Clear from session
    }
}

// Usage
session_start();
$session = UserSession::getInstance();
$session->login($user);

// Available across requests
$session = UserSession::getInstance();
$currentUser = $session->getUser();
```

### Redis Singleton

```php
use Neuron\Patterns\Singleton\Redis;

class GlobalCache extends Redis
{
    private array $cache = [];

    protected function getRedisKey(): string
    {
        return 'app:global:cache';
    }

    public function set(string $key, $value, int $ttl = 3600): void
    {
        $this->cache[$key] = [
            'value' => $value,
            'expires' => time() + $ttl
        ];
        $this->serialize(); // Persist to Redis
    }

    public function get(string $key)
    {
        if (!isset($this->cache[$key])) {
            return null;
        }

        if ($this->cache[$key]['expires'] < time()) {
            unset($this->cache[$key]);
            return null;
        }

        return $this->cache[$key]['value'];
    }
}

// Shared across application instances
$cache = GlobalCache::getInstance();
$cache->set('api.token', $token, 7200);
```

### Memcache Singleton

```php
use Neuron\Patterns\Singleton\Memcache;

class SharedState extends Memcache
{
    private array $state = [];

    protected function getMemcacheKey(): string
    {
        return 'app:shared:state';
    }

    public function setState(string $key, $value): void
    {
        $this->state[$key] = $value;
        $this->serialize(); // Persist to Memcache
    }

    public function getState(string $key)
    {
        return $this->state[$key] ?? null;
    }
}

// Shared across servers
$state = SharedState::getInstance();
$state->setState('maintenance.mode', true);
```

## Observer Pattern

The Observer pattern defines a one-to-many dependency between objects, allowing multiple observers to be notified of state changes.

### Basic Implementation

```php
use Neuron\Patterns\Observer\ObservableTrait;
use Neuron\Patterns\Observer\IObserver;

// Observable class
class Product
{
    use ObservableTrait;

    private string $name;
    private float $price;

    public function setPrice(float $price): void
    {
        $oldPrice = $this->price;
        $this->price = $price;

        // Notify observers of price change
        $this->notifyObservers($this, $oldPrice, $price);
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}

// Observer implementation
class PriceWatcher implements IObserver
{
    public function observableUpdate($observable, ...$params): void
    {
        [$oldPrice, $newPrice] = $params;

        if ($newPrice < $oldPrice) {
            $discount = (($oldPrice - $newPrice) / $oldPrice) * 100;
            echo "Price dropped by {$discount}%!\n";
        }
    }
}

// Usage
$product = new Product();
$watcher = new PriceWatcher();

$product->addObserver($watcher);
$product->setPrice(99.99);  // Initial price
$product->setPrice(79.99);  // Triggers: "Price dropped by 20%!"

// Clean up
$product->removeObserver($watcher);
```

### Multiple Observers

```php
class Stock
{
    use ObservableTrait;

    private int $quantity = 0;

    public function updateQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
        $this->notifyObservers($this, $quantity);
    }
}

class LowStockAlert implements IObserver
{
    private int $threshold;

    public function __construct(int $threshold = 10)
    {
        $this->threshold = $threshold;
    }

    public function observableUpdate($observable, ...$params): void
    {
        $quantity = $params[0];

        if ($quantity < $this->threshold) {
            $this->sendAlert("Low stock warning: {$quantity} items remaining");
        }
    }

    private function sendAlert(string $message): void
    {
        // Send email, SMS, etc.
        echo "ALERT: {$message}\n";
    }
}

class StockLogger implements IObserver
{
    public function observableUpdate($observable, ...$params): void
    {
        $quantity = $params[0];
        error_log("Stock updated: {$quantity} items");
    }
}

// Usage
$stock = new Stock();
$stock->addObserver(new LowStockAlert(5));
$stock->addObserver(new StockLogger());

$stock->updateQuantity(3); // Triggers both observers
```

## Command Pattern

The Command pattern encapsulates operations as objects, allowing you to parameterize clients with different requests, queue operations, and support undo operations.

### Command Interface

```php
use Neuron\Patterns\Command\ICommand;

class CreateUserCommand implements ICommand
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(?array $params = null): mixed
    {
        $user = new User(
            $params['name'] ?? throw new \InvalidArgumentException('Name required'),
            $params['email'] ?? throw new \InvalidArgumentException('Email required'),
            $params['password'] ?? throw new \InvalidArgumentException('Password required')
        );

        return $this->repository->save($user);
    }
}
```

### Command Invoker

```php
use Neuron\Patterns\Command\Invoker;

$invoker = new Invoker();

// Set and execute command
$invoker->setCommand(new CreateUserCommand($userRepo));
$user = $invoker->execute([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'secret123'
]);
```

### Command Factory

```php
use Neuron\Patterns\Command\Factory;

class CommandFactory extends Factory
{
    protected array $commands = [
        'user.create' => CreateUserCommand::class,
        'user.delete' => DeleteUserCommand::class,
        'user.update' => UpdateUserCommand::class,
        'email.send' => SendEmailCommand::class,
    ];

    public function createCommand(string $name): ICommand
    {
        $class = $this->commands[$name] ?? throw new \InvalidArgumentException("Unknown command: {$name}");

        return $this->container->get($class);
    }
}

// Usage
$factory = new CommandFactory();
$command = $factory->createCommand('user.create');
$result = $command->execute($params);
```

### Macro Commands

```php
class MacroCommand implements ICommand
{
    private array $commands = [];

    public function addCommand(ICommand $command): void
    {
        $this->commands[] = $command;
    }

    public function execute(?array $params = null): mixed
    {
        $results = [];

        foreach ($this->commands as $command) {
            $results[] = $command->execute($params);
        }

        return $results;
    }
}

// Usage
$macro = new MacroCommand();
$macro->addCommand(new ValidateUserCommand());
$macro->addCommand(new CreateUserCommand());
$macro->addCommand(new SendWelcomeEmailCommand());
$macro->addCommand(new LogRegistrationCommand());

$results = $macro->execute($userData);
```

### Undoable Commands

```php
interface IUndoableCommand extends ICommand
{
    public function undo(): void;
}

class DeleteFileCommand implements IUndoableCommand
{
    private string $filepath;
    private ?string $backupContent = null;

    public function __construct(string $filepath)
    {
        $this->filepath = $filepath;
    }

    public function execute(?array $params = null): mixed
    {
        if (file_exists($this->filepath)) {
            $this->backupContent = file_get_contents($this->filepath);
            unlink($this->filepath);
            return true;
        }
        return false;
    }

    public function undo(): void
    {
        if ($this->backupContent !== null) {
            file_put_contents($this->filepath, $this->backupContent);
        }
    }
}

// Command history for undo support
class CommandHistory
{
    private array $history = [];

    public function execute(ICommand $command, ?array $params = null): mixed
    {
        $result = $command->execute($params);

        if ($command instanceof IUndoableCommand) {
            $this->history[] = $command;
        }

        return $result;
    }

    public function undo(): void
    {
        $command = array_pop($this->history);

        if ($command instanceof IUndoableCommand) {
            $command->undo();
        }
    }
}
```

## Criteria Pattern

The Criteria pattern provides a way to filter collections of objects using composable criteria.

### Basic Criteria

```php
use Neuron\Patterns\Criteria\ICriteria;
use Neuron\Patterns\Criteria\Base;

class ActiveCriteria extends Base
{
    public function meetCriteria(array $entities): array
    {
        return array_filter($entities, function($entity) {
            return $entity->isActive();
        });
    }
}

class PremiumCriteria extends Base
{
    public function meetCriteria(array $entities): array
    {
        return array_filter($entities, function($entity) {
            return $entity->isPremium();
        });
    }
}

// Usage
$users = User::all();
$activeCriteria = new ActiveCriteria();
$activeUsers = $activeCriteria->meetCriteria($users);
```

### KeyValue Criteria

```php
use Neuron\Patterns\Criteria\KeyValue;

// Filter by exact key-value match
$adminCriteria = new KeyValue('role', 'admin');
$admins = $adminCriteria->meetCriteria($users);

// Filter by status
$publishedCriteria = new KeyValue('status', 'published');
$publishedPosts = $publishedCriteria->meetCriteria($posts);
```

### Logical Criteria Combinations

```php
use Neuron\Patterns\Criteria\AndCriteria;
use Neuron\Patterns\Criteria\OrCriteria;
use Neuron\Patterns\Criteria\NotCriteria;

// AND combination
$activeCriteria = new KeyValue('status', 'active');
$premiumCriteria = new KeyValue('type', 'premium');
$activePremium = new AndCriteria($activeCriteria, $premiumCriteria);
$result = $activePremium->meetCriteria($users);

// OR combination
$adminCriteria = new KeyValue('role', 'admin');
$moderatorCriteria = new KeyValue('role', 'moderator');
$staffCriteria = new OrCriteria($adminCriteria, $moderatorCriteria);
$staff = $staffCriteria->meetCriteria($users);

// NOT criteria
$notBanned = new NotCriteria(new KeyValue('status', 'banned'));
$activeUsers = $notBanned->meetCriteria($users);
```

### Complex Criteria Composition

```php
// Find active premium users who are not admins
$active = new KeyValue('status', 'active');
$premium = new KeyValue('subscription', 'premium');
$notAdmin = new NotCriteria(new KeyValue('role', 'admin'));

$criteria = new AndCriteria(
    $active,
    new AndCriteria($premium, $notAdmin)
);

$targetUsers = $criteria->meetCriteria($allUsers);
```

### Custom Criteria

```php
class DateRangeCriteria extends Base
{
    private \DateTime $start;
    private \DateTime $end;
    private string $field;

    public function __construct(string $field, \DateTime $start, \DateTime $end)
    {
        $this->field = $field;
        $this->start = $start;
        $this->end = $end;
    }

    public function meetCriteria(array $entities): array
    {
        return array_filter($entities, function($entity) {
            $date = $entity->{$this->field};
            return $date >= $this->start && $date <= $this->end;
        });
    }
}

// Filter orders by date range
$lastWeek = new DateRangeCriteria(
    'createdAt',
    new \DateTime('-7 days'),
    new \DateTime('now')
);
$recentOrders = $lastWeek->meetCriteria($orders);
```

## IRunnable Interface

The IRunnable interface provides a contract for executable objects.

```php
use Neuron\Patterns\IRunnable;

class DataProcessor implements IRunnable
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function run(): void
    {
        foreach ($this->data as $item) {
            $this->process($item);
        }
    }

    private function process($item): void
    {
        // Processing logic
    }
}

// Usage with task runner
class TaskRunner
{
    private array $tasks = [];

    public function addTask(IRunnable $task): void
    {
        $this->tasks[] = $task;
    }

    public function runAll(): void
    {
        foreach ($this->tasks as $task) {
            $task->run();
        }
    }
}

$runner = new TaskRunner();
$runner->addTask(new DataProcessor($data));
$runner->addTask(new CacheWarmer());
$runner->addTask(new EmailQueue());
$runner->runAll();
```

## Usage Examples

### Service Locator with Registry

```php
class Application
{
    public function bootstrap(): void
    {
        $registry = Registry::getInstance();

        // Register core services
        $registry->set('config', new Configuration());
        $registry->set('logger', new Logger());
        $registry->set('db', new DatabaseConnection($registry->get('config')));
        $registry->set('cache', new CacheManager());

        // Register factories
        $registry->set('user.repository', function() use ($registry) {
            return new UserRepository($registry->get('db'));
        });
    }

    public function getService(string $name)
    {
        $service = Registry::getInstance()->get($name);

        // Resolve factories
        if (is_callable($service)) {
            return $service();
        }

        return $service;
    }
}
```

### Event-Driven Architecture with Observer

```php
class EventDrivenSystem
{
    use ObservableTrait;

    public function processOrder(Order $order): void
    {
        // Process the order
        $order->process();

        // Notify all observers
        $this->notifyObservers('order.processed', $order);
    }
}

class InventoryManager implements IObserver
{
    public function observableUpdate($observable, ...$params): void
    {
        [$event, $order] = $params;

        if ($event === 'order.processed') {
            foreach ($order->getItems() as $item) {
                $this->decrementStock($item->getSku(), $item->getQuantity());
            }
        }
    }
}

class EmailNotifier implements IObserver
{
    public function observableUpdate($observable, ...$params): void
    {
        [$event, $order] = $params;

        if ($event === 'order.processed') {
            $this->sendOrderConfirmation($order);
        }
    }
}

// Setup
$system = new EventDrivenSystem();
$system->addObserver(new InventoryManager());
$system->addObserver(new EmailNotifier());
$system->addObserver(new ShippingNotifier());

// Process order triggers all observers
$system->processOrder($order);
```

## Testing

### Testing Singletons

```php
use PHPUnit\Framework\TestCase;

class SingletonTest extends TestCase
{
    protected function tearDown(): void
    {
        // Clean up singleton instances between tests
        MyAppConfig::getInstance()->invalidate();
    }

    public function testSingleInstance(): void
    {
        $instance1 = MyAppConfig::getInstance();
        $instance2 = MyAppConfig::getInstance();

        $this->assertSame($instance1, $instance2);
    }

    public function testPersistence(): void
    {
        $config = MyAppConfig::getInstance();
        $config->set('test.key', 'test.value');

        $config2 = MyAppConfig::getInstance();
        $this->assertEquals('test.value', $config2->get('test.key'));
    }
}
```

### Testing Observers

```php
class ObserverTest extends TestCase
{
    public function testObserverNotification(): void
    {
        $observable = new TestObservable();

        $observer = $this->createMock(IObserver::class);
        $observer->expects($this->once())
                 ->method('observableUpdate')
                 ->with($observable, 'test', 'data');

        $observable->addObserver($observer);
        $observable->triggerEvent('test', 'data');
    }
}
```

### Testing Commands

```php
class CommandTest extends TestCase
{
    public function testCommandExecution(): void
    {
        $repository = $this->createMock(UserRepository::class);
        $repository->expects($this->once())
                   ->method('save')
                   ->willReturn(true);

        $command = new CreateUserCommand($repository);
        $result = $command->execute([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $this->assertTrue($result);
    }
}
```

## Best Practices

### Registry Usage

```php
// Good: Clear service names
$registry->set('database.connection', $db);
$registry->set('cache.manager', $cache);

// Avoid: Unclear or conflicting names
$registry->set('db', $db);  // Too generic
$registry->set('temp', $data);  // Unclear purpose
```

### Singleton Design

```php
// Good: Stateless or minimal state
class Logger extends Memory
{
    private string $logFile;

    public function log(string $message): void
    {
        // Stateless operation
        file_put_contents($this->logFile, $message, FILE_APPEND);
    }
}

// Avoid: Heavy state in singletons
class BadSingleton extends Memory
{
    private array $heavyData = [];  // Can grow unbounded
    private array $connections = []; // Resource management issues
}
```

### Observer Pattern

```php
// Good: Specific observer interfaces
interface OrderObserver
{
    public function onOrderCreated(Order $order): void;
    public function onOrderShipped(Order $order): void;
    public function onOrderCancelled(Order $order): void;
}

// Good: Clear event data
$this->notifyObservers('order.status.changed', $order, $oldStatus, $newStatus);

// Avoid: Generic updates without context
$this->notifyObservers($someData);  // What changed?
```

### Command Pattern

```php
// Good: Self-contained commands
class SendEmailCommand implements ICommand
{
    private Mailer $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function execute(?array $params = null): mixed
    {
        // Validate params
        $this->validate($params);

        // Execute with error handling
        try {
            return $this->mailer->send(
                $params['to'],
                $params['subject'],
                $params['body']
            );
        } catch (\Exception $e) {
            // Handle appropriately
            throw new CommandException('Email send failed', 0, $e);
        }
    }
}
```

## More Information

- **Neuron Framework**: [neuronphp.com](http://neuronphp.com)
- **GitHub**: [github.com/neuron-php/patterns](https://github.com/neuron-php/patterns)
- **Packagist**: [packagist.org/packages/neuron-php/patterns](https://packagist.org/packages/neuron-php/patterns)

## License

MIT License - see LICENSE file for details