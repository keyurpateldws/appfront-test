<?php

namespace App\Services;

use App\Contracts\CommandBus as CommandBusContract;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommandBus implements CommandBusContract
{
    public function __construct(
        protected Container $container
    ) {}

    public function dispatch(object $command): void
    {
        $handlerClass = $this->getHandlerClass($command);
        
        try {
            $handler = $this->container->make($handlerClass);
            $handler->handle($command);
        } catch (ModelNotFoundException $e) {
            Log::error("Resource not found while processing command", [
                'command' => get_class($command),
                'error' => $e->getMessage()
            ]);
            throw $e;
        } catch (\InvalidArgumentException $e) {
            Log::warning("Invalid argument while processing command", [
                'command' => get_class($command),
                'error' => $e->getMessage()
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error("Failed to dispatch command", [
                'command' => get_class($command),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    protected function getHandlerClass(object $command): string
    {
        $commandClass = get_class($command);
        $commandName = class_basename($commandClass);
        
        // Remove 'Command' suffix if it exists
        $handlerName = str_replace('Command', '', $commandName);
        
        // Replace 'Commands' namespace with 'Handlers'
        return str_replace('Commands', 'Handlers', $commandClass) . 'Handler';
    }
} 