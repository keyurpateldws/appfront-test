<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Commands\UpdateProductCommand;
use App\Contracts\CommandBus;
use Illuminate\Support\Facades\Validator;

class UpdateProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:update {id} {--name=} {--description=} {--price=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update a product with the specified details';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        protected CommandBus $commandBus
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->argument('id');
        
        // Validate name if provided
        if ($this->option('name')) {
            $validator = Validator::make(
                ['name' => $this->option('name')],
                ['name' => 'required|min:3']
            );
            
            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $error) {
                    $this->error($error);
                }
                return 1;
            }
        }

        try {
            $command = new UpdateProductCommand(
                id: (int) $id,
                name: $this->option('name'),
                description: $this->option('description'),
                price: $this->option('price') ? (float) $this->option('price') : null
            );

            $this->commandBus->dispatch($command);
            $this->info("Product updated successfully.");
            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to update product: " . $e->getMessage());
            return 1;
        }
    }
}